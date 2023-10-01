<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Forwarding extends Model
{
    use HasFactory;
    protected $table = 'forwardings';
    protected $primaryKey = 'id';

    protected $fillable = [
        'form_id',
        'fw_name',
        'fw_link',
    ];
}
