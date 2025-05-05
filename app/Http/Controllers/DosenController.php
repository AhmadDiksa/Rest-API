<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'nidn' => 'required|string|max:50|unique:dosen,nidn',
            'email' => 'required|email|unique:dosen,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dosen = Dosen::create($request->all());
            return response()->json($dosen, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menambahkan data dosen', 'error' => $e->getMessage()], 500);
        }
    }

    // PUT: Mengupdate data dosen
    public function update(Request $request, $id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'sometimes|required|string|max:255',
            'nidn' => 'sometimes|required|string|max:50|unique:dosen,nidn,' . $id,
            'email' => 'sometimes|required|email|unique:dosen,email,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dosen->update($request->all());
            return response()->json($dosen, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal mengupdate data dosen', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE: Menghapus data dosen
    public function destroy($id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        try {
            $dosen->delete();
            return response()->json(["message" => "Data berhasil dihapus"], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus data dosen', 'error' => $e->getMessage()], 500);
        }
    }
}
