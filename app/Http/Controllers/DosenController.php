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
 *   required={"id", "nama_dosen", "nidn"},
 *   @OA\Property(property="id", type="integer", example=1),
 *   @OA\Property(property="nama_dosen", type="string", example="Dr. Ahmad"),
 *   @OA\Property(property="nidn", type="string", example="1234567890"),
 *   @OA\Property(property="email", type="string", example="ahmad@example.com"),
 *   @OA\Property(property="alamat", type="string", example="Jl. Merdeka No. 123"),
 *   @OA\Property(property="program_studi", type="string", example="Teknik Informatika"),
 *   @OA\Property(property="tanggal_lahir", type="string", format="date", example="1980-01-01"),
 *   @OA\Property(property="jenis_kelamin", type="string", enum={"L", "P"}, example="L"),
 *   @OA\Property(property="status", type="string", enum={"Dosen Tetap", "Dosen Tidak Tetap"}, example="Dosen Tetap"),
 *   @OA\Property(property="bidang_keahlian", type="string", example="Kecerdasan Buatan"),
 *   @OA\Property(property="created_at", type="string", format="date-time"),
 *   @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class DosenController extends Controller
{
    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/api/dosen",
     *     tags={"Dosen"},
     *     summary="Ambil semua data dosen",
     *     @OA\Response(
     *         response=200,
     *         description="Daftar dosen",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Dosen"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Dosen::all(), 200);
    }

    /**
     * @OA\Get(
     *     security={{"bearerAuth":{}}},
     *     path="/api/dosen/{id}",
     *     tags={"Dosen"},
     *     summary="Ambil data dosen berdasarkan ID",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
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

    /**
     * @OA\Post(
     *     security={{"bearerAuth":{}}},
     *     path="/api/dosen",
     *     tags={"Dosen"},
     *     summary="Tambah dosen baru",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nama_dosen", "nidn", "email", "alamat", "program_studi", "tanggal_lahir", "jenis_kelamin", "status", "bidang_keahlian"},
     *             @OA\Property(property="nama_dosen", type="string", example="Dr. Diksa"),
     *             @OA\Property(property="nidn", type="string", example="1234567890"),
     *             @OA\Property(property="email", type="string", example="diksa@example.com"),
     *             @OA\Property(property="alamat", type="string", example="Jl. Merdeka No. 123"),
     *             @OA\Property(property="program_studi", type="string", example="Teknik Informatika"),
     *             @OA\Property(property="tanggal_lahir", type="string", format="date", example="1980-05-10"),
     *             @OA\Property(property="jenis_kelamin", type="string", enum={"L", "P"}, example="L"),
     *             @OA\Property(property="status", type="string", enum={"Dosen Tetap", "Dosen Tidak Tetap"}, example="Dosen Tetap"),
     *             @OA\Property(property="bidang_keahlian", type="string", example="Kecerdasan Buatan")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Dosen ditambahkan"),
     *     @OA\Response(response=422, description="Validasi gagal")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_dosen' => 'required|string|max:255',
            'nidn' => 'required|string|max:50|unique:dosen,nidn',
            'email' => 'required|email|unique:dosen,email',
            'alamat' => 'required|string|max:255',
            'program_studi' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'status' => 'required|in:Dosen Tetap,Dosen Tidak Tetap',
            'bidang_keahlian' => 'required|string|max:255',
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

    /**
     * @OA\Put(
     *     security={{"bearerAuth":{}}},
     *     path="/api/dosen/{id}",
     *     tags={"Dosen"},
     *     summary="Update data dosen",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nama_dosen", type="string", example="Dr. Budi"),
     *             @OA\Property(property="nidn", type="string", example="9876543210"),
     *             @OA\Property(property="email", type="string", example="budi@example.com"),
     *             @OA\Property(property="alamat", type="string", example="Jl. Merdeka No. 123"),
     *             @OA\Property(property="program_studi", type="string", example="Teknik Elektro"),
     *             @OA\Property(property="tanggal_lahir", type="string", format="date", example="1985-03-15"),
     *             @OA\Property(property="jenis_kelamin", type="string", enum={"L", "P"}, example="L"),
     *             @OA\Property(property="status", type="string", enum={"Dosen Tetap", "Dosen Tidak Tetap"}, example="Dosen Tidak Tetap"),
     *             @OA\Property(property="bidang_keahlian", type="string", example="Jaringan Komputer")
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
            'nama_dosen' => 'sometimes|required|string|max:255',
            'nidn' => 'sometimes|required|string|max:50|unique:dosen,nidn,' . $id,
            'email' => 'sometimes|required|email|unique:dosen,email,' . $id,
            'alamat' => 'sometimes|required|string|max:255',
            'program_studi' => 'sometimes|required|string|max:255',
            'tanggal_lahir' => 'sometimes|required|date',
            'jenis_kelamin' => 'sometimes|required|in:L,P',
            'status' => 'sometimes|required|in:Dosen Tetap,Dosen Tidak Tetap',
            'bidang_keahlian' => 'sometimes|required|string|max:255',
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

    /**
     * @OA\Delete(
     *     security={{"bearerAuth":{}}},
     *     path="/api/dosen/{id}",
     *     tags={"Dosen"},
     *     summary="Hapus dosen",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Dosen dihapus"),
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
