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

    public function pengampu(): HasMany {
        return $this->hasMany(Pengampu::class, 'mata_kuliah_id');
    }
}
