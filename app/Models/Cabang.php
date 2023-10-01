<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $fillable = [
        'kd_cabang',
        'nm_cabang',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'kd_departemen');
    }
}
