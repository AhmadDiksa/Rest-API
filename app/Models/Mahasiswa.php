<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table = 'mahasiswa'; // Jika nama tabel Anda berbeda dari konvensi

    protected $fillable = [
        'nim',
        'nama',
        'jenis_kelamin',
        'alamat',
        'tanggal_lahir',
        'program_studi',
        'angkatan',
        'email',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',  // Pastikan tanggal_lahir di-cast sebagai date
    ];
}