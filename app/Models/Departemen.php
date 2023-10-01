<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    use HasFactory;

    protected $fillable = [
        'kd_departemen',
        'nm_departemen',
        'modul',
        'controller',
        'kd_cabang',
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'kd_departemen');
    }

    public function thread()
    {
        return $this->hasMany(Thread::class);
    }

    public function menuItem()
    {
        return $this->hasMany(MenuItem::class, 'modul_departemen')->on('sqlite');
    }
}
