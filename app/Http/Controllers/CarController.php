<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CarController extends Controller
{
    public function index(Request $request)
    {
        $query = Car::query();

        // Cek apakah semua filter kosong
        $hasFilter = $request->filled('brand') ||
            $request->filled('min_price') ||
            $request->filled('max_price') ||
            $request->filled('availability_status');

        // Jika tidak ada filter, tampilkan semua mobil
        if (!$hasFilter) {
            return response()->json([
                'success' => true,
                'data' => Car::all(), // .Tampilkan semua mobil jika filter kosong
            ]);
        }

        // Filter berdasarkan brand
        if ($request->filled('brand')) {
            $query->where('brand', 'like', '%' . $request->brand . '%');
        }

        // Filter berdasarkan harga
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->whereBetween('price_per_day', [$request->min_price, $request->max_price]);
        }

        // Filter berdasarkan status
        if ($request->filled('availability_status')) {
            $query->where('availability_status', $request->availability_status);
        }

        $cars = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $cars->items(),
            'pagination' => [
                'total' => $cars->total(),
                'per_page' => $cars->perPage(),
                'current_page' => $cars->currentPage(),
                'last_page' => $cars->lastPage(),
            ],
        ]);
    }
}
