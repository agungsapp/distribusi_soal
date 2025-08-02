<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Soal extends Model
{
    protected $fillable = ['pengampu_id', 'dosen_id', 'path', 'status'];

    public function pengampu(): BelongsTo
    {
        return $this->belongsTo(Pengampu::class, 'pengampu_id');
    }

    public function dosen(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dosen_id');
    }
}
