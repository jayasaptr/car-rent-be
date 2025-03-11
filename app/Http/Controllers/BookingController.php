<?php

namespace App\Http\Controllers;

use App\Jobs\SendBookingNotification;
use App\Models\Booking;
use App\Models\Car;
use App\Notifications\BookingStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{

    public function show($id)
    {
        $booking = Booking::with(['user', 'car'])->find($id);

        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        return response()->json($booking);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $car = Car::findOrFail($request->car_id);

        // Cek apakah mobil tersedia
        if ($car->availability_status !== 'available') {
            return response()->json(['message' => 'Car is already booked'], 400);
        }

        // Hitung total harga berdasarkan durasi
        $days = (strtotime($request->end_date) - strtotime($request->start_date)) / (60 * 60 * 24);
        $totalPrice = $car->price_per_day * $days;

        // Simpan booking
        $booking = Booking::create([
            'user_id' => $request->user_id,
            'car_id' => $request->car_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // Update status mobil
        $car->update(['availability_status' => 'booked']);

        return response()->json(['message' => 'Booking created', 'booking' => $booking], 201);
    }

    public function updateBookingStatus(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $request->validate([
            'status' => 'required|in:pending,confirmed,completed,canceled',
        ]);

        $booking->update(['status' => $request->status]);

        // Dispatch notifikasi ke queue
        dispatch(new SendBookingNotification($booking));

        return response()->json(['message' => 'Booking status updated successfully']);
    }
}
