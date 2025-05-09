<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Schema(
 *     schema="Mahasiswa",
 *     type="object",
 *     required={"nim", "nama", "jenis_kelamin", "alamat", "tanggal_lahir", "program_studi", "angkatan", "email", "status", "agama"},
 *     @OA\Property(property="nim", type="string", example="1234567890"),
 *     @OA\Property(property="nama", type="string", example="John Doe"),
 *     @OA\Property(property="jenis_kelamin", type="string", example="L"),
 *     @OA\Property(property="alamat", type="string", example="Jl. Merdeka 123"),
 *     @OA\Property(property="tanggal_lahir", type="string", format="date", example="2000-01-01"),
 *     @OA\Property(property="program_studi", type="string", example="Teknik Informatika"),
 *     @OA\Property(property="angkatan", type="integer", example=2020),
 *     @OA\Property(property="email", type="string", example="johndoe@example.com"),
 *     @OA\Property(property="status", type="string", example="Aktif"),
 *     @OA\Property(property="agama", type="string", example="Islam")
 * )
 */

class MahasiswaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/mahasiswa",
     *     tags={"Mahasiswa"},
     *     summary="Menampilkan daftar mahasiswa",
     *     @OA\Parameter(
     *         name="nama",
     *         in="query",
     *         description="Filter berdasarkan nama",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Berhasil")
     * )
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::query();

        if ($request->has('nama')) {
            $query->where('nama', 'like', '%' . $request->input('nama') . '%');
        }

        return response()->json($query->get(), 200);
    }

    /**
     * @OA\Post(
     *     path="/api/mahasiswa",
     *     tags={"Mahasiswa"},
     *     summary="Menambahkan mahasiswa",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nim","nama","jenis_kelamin","alamat","tanggal_lahir","program_studi","angkatan","email","status","agama"},
     *             @OA\Property(property="nim", type="string"),
     *             @OA\Property(property="nama", type="string"),
     *             @OA\Property(property="jenis_kelamin", type="string"),
     *             @OA\Property(property="alamat", type="string"),
     *             @OA\Property(property="tanggal_lahir", type="string", format="date"),
     *             @OA\Property(property="program_studi", type="string"),
     *             @OA\Property(property="angkatan", type="integer"),
     *             @OA\Property(property="email", type="string"),
     *             @OA\Property(property="status", type="string"),
     *             @OA\Property(property="agama", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Data berhasil ditambahkan"),
     *     @OA\Response(response=422, description="Validasi gagal"),
     *     @OA\Response(response=500, description="Kesalahan server")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/mahasiswa/nim/{nim}",
     *     tags={"Mahasiswa"},
     *     summary="Menampilkan data mahasiswa berdasarkan NIM",
     *     @OA\Parameter(
     *         name="nim",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Data ditemukan"),
     *     @OA\Response(response=404, description="Data tidak ditemukan")
     * )
     */
    public function showByNim(string $nim)
    {
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if (!$mahasiswa) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }

        return response()->json($mahasiswa, 200);
    }

    /**
     * @OA\Put(
     *     path="/api/mahasiswa/{id}",
     *     tags={"Mahasiswa"},
     *     summary="Mengupdate data mahasiswa berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Mahasiswa")
     *     ),
     *     @OA\Response(response=200, description="Berhasil diupdate"),
     *     @OA\Response(response=404, description="Data tidak ditemukan")
     * )
     */
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

    /**
     * @OA\Put(
     *     path="/api/mahasiswa/nim/{nim}",
     *     tags={"Mahasiswa"},
     *     summary="Mengupdate data mahasiswa berdasarkan NIM",
     *     @OA\Parameter(
     *         name="nim",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Mahasiswa")
     *     ),
     *     @OA\Response(response=200, description="Berhasil diupdate"),
     *     @OA\Response(response=404, description="Data tidak ditemukan")
     * )
     */
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


    /**
     * @OA\Delete(
     *     path="/api/mahasiswa/nim/{nim}",
     *     tags={"Mahasiswa"},
     *     summary="Menghapus mahasiswa berdasarkan NIM",
     *     @OA\Parameter(
     *         name="nim",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Berhasil dihapus"),
     *     @OA\Response(response=404, description="Data tidak ditemukan")
     * )
     */
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
