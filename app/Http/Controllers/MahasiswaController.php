<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MahasiswaController extends Controller
{
    // GET: Menampilkan semua data mahasiswa dan filter berdasarkan nama
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        if ($request->has('nama')) {
            $nama = $request->input('nama');
            $query->where('nama', 'like', '%' . $nama . '%');
        }

        return response()->json($query->get(), 200);
    }

    // POST: Menambahkan data mahasiswa
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string|max:50|unique:mahasiswa,nim',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|string|max:10',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'program_studi' => 'required|string|max:255',
            'angkatan' => 'required|integer',
            'email' => 'required|email|unique:mahasiswa,email',
            'status' => 'required|string|max:50',
            'agama' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $mahasiswa = Mahasiswa::create($request->all());
            return response()->json($mahasiswa, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan data mahasiswa', 'error' => $e->getMessage()], 500);
        }
    }

    // GET: Menampilkan data mahasiswa berdasarkan NIM
    public function showByNim(string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        return response()->json($mahasiswa, 200);
    }

    // PUT: Mengupdate data mahasiswa
    public function update(Request $request, $id)
    {
        $mahasiswa = Mahasiswa::find($id);
        if (!$mahasiswa) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        $validator = Validator::make($request->all(), [
            'nim' => 'sometimes|required|string|max:50|unique:mahasiswa,nim,' . $id,
            'nama' => 'sometimes|required|string|max:255',
            'jenis_kelamin' => 'sometimes|required|string|max:10',
            'alamat' => 'sometimes|required|string',
            'tanggal_lahir' => 'sometimes|required|date',
            'program_studi' => 'sometimes|required|string|max:255',
            'angkatan' => 'sometimes|required|integer',
            'email' => 'sometimes|required|email|unique:mahasiswa,email,' . $id,
            'status' => 'sometimes|required|string|max:50',
            'agama' => 'sometimes|required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $mahasiswa->update($request->all());
            return response()->json($mahasiswa, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengupdate data mahasiswa', 'error' => $e->getMessage()], 500);
        }
    }

    // PUT: Mengupdate data mahasiswa berdasarkan NIM
    public function updateByNim(Request $request, string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        $validator = Validator::make($request->all(), [
            'nim' => 'sometimes|required|string|max:50|unique:mahasiswa,nim,' . $mahasiswa->id,
            'nama' => 'sometimes|required|string|max:255',
            'jenis_kelamin' => 'sometimes|required|string|max:10',
            'alamat' => 'sometimes|required|string',
            'tanggal_lahir' => 'sometimes|required|date',
            'program_studi' => 'sometimes|required|string|max:255',
            'angkatan' => 'sometimes|required|integer',
            'email' => 'sometimes|required|email|unique:mahasiswa,email,' . $mahasiswa->id,
            'status' => 'sometimes|required|string|max:50',
            'agama' => 'sometimes|required|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $mahasiswa->update($request->all());
            return response()->json($mahasiswa, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengupdate data mahasiswa', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE: Menghapus data mahasiswa berdasarkan NIM
    public function destroyByNim(string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        try {
            $mahasiswa->delete();
            return response()->json(["message" => "Data berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data mahasiswa', 'error' => $e->getMessage()], 500);
        }
    }
}
