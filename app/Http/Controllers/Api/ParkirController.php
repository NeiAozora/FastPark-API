<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AreaParkir;
use Illuminate\Support\Facades\Validator;

class ParkirController extends Controller
{
    // GET /api/parkir
    public function index()
    {
        $data = AreaParkir::all();
        return response()->json($data);
    }

    // POST /api/parkir
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_area' => 'required|string|max:100',
            'persentase_penuh' => 'required|numeric|between:0,100.00',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $area = AreaParkir::create($request->only(['nama_area', 'persentase_penuh']));
        return response()->json($area, 201);
    }

    // GET /api/parkir/{id}
    public function show($id)
    {
        $area = AreaParkir::find($id);
        if (!$area) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($area);
    }

    // PUT /api/parkir/{id}
    public function update(Request $request, $id)
    {
        $area = AreaParkir::find($id);
        if (!$area) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_area' => 'sometimes|required|string|max:100',
            'persentase_penuh' => 'sometimes|required|numeric|between:0,100.00',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $area->update($request->only(['nama_area', 'persentase_penuh']));
        return response()->json($area);
    }

    // DELETE /api/parkir/{id}
    public function destroy($id)
    {
        $area = AreaParkir::find($id);
        if (!$area) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $area->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
