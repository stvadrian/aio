<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    use HasFactory;

    protected $table = 'hak_akses';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kd_hak_akses',
        'nm_hak_akses',
    ];

    public function user()
    {
        return $this->hasMany(User::class);
    }
}
