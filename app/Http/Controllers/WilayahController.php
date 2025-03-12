<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use Illuminate\Http\Request;

class WilayahController extends Controller
{
    public function province(Request $request)
    {
        $query = Province::select('id', 'name');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $provinces = $query->get();

        return response()->json([
            'error' => false,
            'message' => 'List of provinces',
            'data' => $provinces->isEmpty() ? [['id' => 0, 'name' => 'Tidak ada']] : $provinces
        ]);
    }

    public function regency(Request $request, $id)
    {
        $query = Regency::select('id', 'name')
            ->where('province_id', $id);

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

        $regencies = $query->get()->map(function ($regency) {
            return [
                'id' => $regency->id,
                'name' => str_replace('Kabupaten ', '', $regency->name) ?: 'Tidak ada'
            ];
        });

        return response()->json([
            'error' => false,
            'message' => 'List of regencies',
            'data' => $regencies->isEmpty() ? [['id' => 0, 'name' => 'Tidak ada']] : $regencies
        ]);
    }

    public function district(Request $request, $id)
    {
        $query = District::select('id', 'name')
            ->where('regency_id', $id);

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->input('search') . '%');
            }

        $districts = $query->get();

        return response()->json([
            'error' => false,
            'message' => 'List of districts',
            'data' => $districts->isEmpty() ? [['id' => 0, 'name' => 'Tidak ada']] : $districts
        ]);
    }
}
