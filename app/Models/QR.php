<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QR extends Model
{
    use HasFactory;

    protected $table = 'qr_master';
    protected $primaryKey = 'id';

    protected $fillable = [
        'qr_name',
        'qr_content',
        'qr_path',
        'created_by',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
