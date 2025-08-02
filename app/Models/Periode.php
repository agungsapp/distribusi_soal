<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periode extends Model
{
    //
    protected $fillable = ['tahun_ajaran', 'semester'];

    protected $casts = [
        'semester' => 'string',
    ];

    public function pengampu(): HasMany
    {
        return $this->hasMany(Pengampu::class, 'periode_id');
    }
}
