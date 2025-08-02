<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    //
    protected $fillable = ['nama'];


    public function peminatan(): HasMany
    {
        return $this->hasMany(Peminatan::class, 'prodi_id');
    }
}
