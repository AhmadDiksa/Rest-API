<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;

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
        $mahasiswa = Mahasiswa::create($request->all());
        return response()->json($mahasiswa, 201);
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

        $mahasiswa->update($request->all());
        return response()->json($mahasiswa, 200);
    }

    // PUT: Mengupdate data mahasiswa berdasarkan NIM
    public function updateByNim(Request $request, string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        $mahasiswa->update($request->all());
        return response()->json($mahasiswa, 200);
    }

    // DELETE: Menghapus data mahasiswa berdasarkan NIM
    public function destroyByNim(string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        $mahasiswa->delete();
        return response()->json(["message" => "Data berhasil dihapus"], 200);
    }
}
