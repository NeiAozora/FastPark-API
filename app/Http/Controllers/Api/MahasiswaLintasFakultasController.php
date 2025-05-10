<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DataMahasiswaLintasFakultas;
use App\Models\Petugas;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MahasiswaLintasFakultasController extends Controller
{
    // GET /api/mahasiswa-lintas
    public function index()
    {
        $data = DataMahasiswaLintasFakultas::with('petugas')->get();
        return response()->json($data);
    }

    // POST /api/mahasiswa-lintas
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'foto_mahasiswa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama' => 'required|string|max:100',
            'nim' => 'required|string|max:20|unique:data_mahasiswa_lintas_fakultas,nim',
            'fakultas_asal' => 'required|string|max:100',
            'tujuan_masuk' => 'required|string|max:150',
            'catatan' => 'nullable|string',
            'petugas_id' => 'required|exists:petugas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'nama', 'nim', 'fakultas_asal', 'tujuan_masuk', 'catatan', 'petugas_id'
        ]);
        $data['waktu'] = now();

        if ($request->hasFile('foto_mahasiswa')) {
            $path = $request->file('foto_mahasiswa')->store('foto_mahasiswa', 'public');
            $data['foto_mahasiswa'] = $path;
        }

        $mahasiswa = DataMahasiswaLintasFakultas::create($data);
        return response()->json($mahasiswa, 201);
    }

    // GET /api/mahasiswa-lintas/{id}
    public function show($id)
    {
        $mahasiswa = DataMahasiswaLintasFakultas::with('petugas')->find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json($mahasiswa);
    }

    // PUT /api/mahasiswa-lintas/{id}
    public function update(Request $request, $id)
    {
        $mahasiswa = DataMahasiswaLintasFakultas::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'foto_mahasiswa' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'nama' => 'sometimes|required|string|max:100',
            'nim' => 'sometimes|required|string|max:20|unique:data_mahasiswa_lintas_fakultas,nim,' . $id,
            'fakultas_asal' => 'sometimes|required|string|max:100',
            'tujuan_masuk' => 'sometimes|required|string|max:150',
            'catatan' => 'nullable|string',
            'petugas_id' => 'sometimes|required|exists:petugas,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only([
            'nama', 'nim', 'fakultas_asal', 'tujuan_masuk', 'catatan', 'petugas_id'
        ]);

        if ($request->hasFile('foto_mahasiswa')) {
            if ($mahasiswa->foto_mahasiswa) {
                Storage::disk('public')->delete($mahasiswa->foto_mahasiswa);
            }
            $path = $request->file('foto_mahasiswa')->store('foto_mahasiswa', 'public');
            $data['foto_mahasiswa'] = $path;
        }

        $mahasiswa->update($data);
        return response()->json($mahasiswa);
    }

    // DELETE /api/mahasiswa-lintas/{id}
    public function destroy($id)
    {
        $mahasiswa = DataMahasiswaLintasFakultas::find($id);
        if (!$mahasiswa) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        if ($mahasiswa->foto_mahasiswa) {
            Storage::disk('public')->delete($mahasiswa->foto_mahasiswa);
        }

        $mahasiswa->delete();
        return response()->json(['message' => 'Data berhasil dihapus']);
    }
}
