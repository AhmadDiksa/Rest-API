<?php

namespace App\Http\Controllers;

use App\Models\Dosen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Info(
 *   title="API Dokumentasi Dosen",
 *   version="1.0",
 *   description="Dokumentasi API untuk manajemen data dosen"
 * )
 *
 * @OA\Tag(
 *   name="Dosen",
 *   description="Operasi CRUD untuk data dosen"
 * )
 *
 * @OA\Schema(
 *   schema="Dosen",
 *   type="object",
 *   title="Dosen",
 *   required={"id", "nama", "nidn"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nama", type="string", example="Dr. Ahmad"),
 *   @OA\Property(property="nidn", type="string", example="1234567890"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */

class DosenController extends Controller
{
    // GET: Menampilkan semua data dosen

    /**
     * @OA\Get(
     * path="/api/dosen",
     * tags={"Dosen"},
     * summary="Ambil semua data dosen",
     * @OA\Response(
     * response=200,
     * description="Daftar dosen",
     * @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Dosen"))
      * )
        * )
    */

    public function index()
    {
        return response()->json(Dosen::all(), 200);
    }

    // GET: Menampilkan data dosen berdasarkan ID
    /**
     * @OA\Get(
     *     path="/api/dosen/{id}",
     *     tags={"Dosen"},
     *     summary="Ambil data dosen berdasarkan ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Dosen ditemukan", @OA\JsonContent(ref="#/components/schemas/Dosen")),
     *     @OA\Response(response=404, description="Dosen tidak ditemukan")
     * )
     */
    public function show($id)
    {
        $dosen = Dosen::find($id);
        if (!$dosen) {
            return response()->json(["message" => "Data tidak ditemukan"], 404);
        }
        return response()->json($dosen, 200);
    }

    // POST: Menambahkan data dosen
    /**
     * @OA\Post(
     *     path="/api/dosen",
     *     tags={"Dosen"},
     *     summary="Tambah dosen baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama", "nidn"},
     *             @OA\Property(property="nama", type="string", example="Dr. Ahmad"),
     *             @OA\Property(property="nidn", type="string", example="1234567890")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Dosen ditambahkan"),
     *     @OA\Response(response=400, description="Validasi gagal")
     * )
     */
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
    /**
     * @OA\Put(
     *     path="/api/dosen/{id}",
     *     tags={"Dosen"},
     *     summary="Update data dosen",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama", type="string", example="Dr. Budi"),
     *             @OA\Property(property="nidn", type="string", example="9876543210")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Dosen diperbarui"),
     *     @OA\Response(response=404, description="Dosen tidak ditemukan")
     * )
     */
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
    /**
     * @OA\Delete(
     *     path="/api/dosen/{id}",
     *     tags={"Dosen"},
     *     summary="Hapus dosen",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Dosen dihapus"),
     *     @OA\Response(response=404, description="Dosen tidak ditemukan")
     * )
     */
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
