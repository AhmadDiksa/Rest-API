<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;

class DosenController extends Controller
{
    // GET: Menampilkan semua data dosen
    public function index()
    {
        return response()->json(Dosen::all(), 200);
    }

    // GET: Menampilkan data dosen berdasarkan ID
    public function show($id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }
        return response()->json($dosen, 200);
    }

    // POST: Menambahkan data dosen
    public function store(Request $request)
    {
        $dosen = Dosen::create($request->all());
        return response()->json($dosen, 201);
    }

    // PUT: Mengupdate data dosen
    public function update(Request $request, $id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }
        $dosen->update($request->all());
        return response()->json($dosen, 200);
    }

    // DELETE: Menghapus data dosen
    public function destroy($id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }
        $dosen->delete();
        return response()->json(["message" => "Data berhasil dihapus"], 200);
    }
}