<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;
    protected $table = 'dosen'; // Menyesuaikan nama tabel
    protected $fillable = ['nama_dosen', 'nidn', 'email', 'alamat', 'program_studi', 'tanggal_lahir', 'jenis_kelamin', 'status', 'bidang_keahlian']; // Mass assignment
}

