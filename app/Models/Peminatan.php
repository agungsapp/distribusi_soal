<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Peminatan extends Model
{
    protected $fillable = ['prodi_id', 'nama'];

    public function prodi(): BelongsTo
    {
        return $this->belongsTo(Prodi::class, 'prodi_id');
    }


    public function mataKuliah(): HasMany
    {
        return $this->hasMany(MataKuliah::class, 'peminatan_id');
    }
}
