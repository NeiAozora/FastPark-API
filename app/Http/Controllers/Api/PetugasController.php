<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Petugas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class PetugasController extends Controller
{
    // GET /api/petugas
    public function index()
    {
        return response()->json(Petugas::all());
    }

    // POST /api/petugas
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'username' => 'required|string|max:50|unique:petugas,username',
            'password' => 'required|string|min:6',
            'email' => 'required|email|max:100|unique:petugas,email',
            'no_hp' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'required|in:aktif,nonaktif',
            'role' => 'required|in:admin,petugas',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nama', 'username', 'email', 'no_hp', 'status', 'role']);
        $data['password'] = Hash::make($request->password);
        $data['dibuat_pada'] = now();
        $data['terakhir_login'] = null;

        if ($request->hasFile('foto_profil')) {
            $path = $request->file('foto_profil')->store('foto_petugas', 'public');
            $data['foto_profil'] = $path;
        }

        $petugas = Petugas::create($data);
        return response()->json($petugas, 201);
    }

    // GET /api/petugas/{id}
    public function show($id)
    {
        $petugas = Petugas::find($id);
        if (!$petugas) {
            return response()->json(['message' => 'Petugas tidak ditemukan'], 404);
        }
        return response()->json($petugas);
    }

    // PUT /api/petugas/{id}
    public function update(Request $request, $id)
    {
        $petugas = Petugas::find($id);
        if (!$petugas) {
            return response()->json(['message' => 'Petugas tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:100',
            'username' => 'sometimes|required|string|max:50|unique:petugas,username,' . $id,
            'password' => 'nullable|string|min:6',
            'email' => 'sometimes|required|email|max:100|unique:petugas,email,' . $id,
            'no_hp' => 'nullable|string|max:20',
            'foto_profil' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'status' => 'sometimes|required|in:aktif,nonaktif',
            'role' => 'sometimes|required|in:admin,petugas',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $data = $request->only(['nama', 'username', 'email', 'no_hp', 'status', 'role']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('foto_profil')) {
            if ($petugas->foto_profil) {
                Storage::disk('public')->delete($petugas->foto_profil);
            }
            $path = $request->file('foto_profil')->store('foto_petugas', 'public');
            $data['foto_profil'] = $path;
        }

        $petugas->update($data);
        return response()->json($petugas);
    }

    // DELETE /api/petugas/{id}
    public function destroy($id)
    {
        $petugas = Petugas::find($id);
        if (!$petugas) {
            return response()->json(['message' => 'Petugas tidak ditemukan'], 404);
        }

        if ($petugas->foto_profil) {
            Storage::disk('public')->delete($petugas->foto_profil);
        }

        $petugas->delete();
        return response()->json(['message' => 'Petugas berhasil dihapus']);
    }
}
