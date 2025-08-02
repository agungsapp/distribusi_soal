<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataKuliah extends Model
{
    protected $fillable = ['peminatan_id', 'nama', 'kode'];

    public function peminatan(): BelongsTo
    {
        return $this->belongsTo(Peminatan::class, 'peminatan_id');
    }

    public function pengampu(): HasMany
    {
        return $this->hasMany(Pengampu::class, 'mata_kuliah_id');
    }

    public static function generateKode($peminatan_id, $mata_kuliah_id)
    {
        $peminatan = Peminatan::findOrFail($peminatan_id);
        $prodi = $peminatan->prodi;
        if (!$prodi) {
            throw new \Exception('Prodi tidak ditemukan untuk peminatan ini.');
        }

        // Ambil singkatan dari nama prodi (contoh: "Teknik Informatika" -> "TI")
        $words = explode(' ', trim($prodi->nama));
        $singkatan = '';
        foreach ($words as $word) {
            $singkatan .= strtoupper(substr($word, 0, 1));
        }
        if (empty($singkatan)) {
            $singkatan = 'XX'; // Fallback jika nama prodi kosong
        }

        // Format kode: [Singkatan]-[ID dengan 3 digit]
        return sprintf('%s-%03d', $singkatan, $mata_kuliah_id);
    }
}
