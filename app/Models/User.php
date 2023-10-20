<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Define the table associated with this model (if not using the default "users" table).
    protected $table = 'users';

    // Define the primary key field name (if not using the default "id").
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'nm_user',
        'dob_user',
        'mobile_user',
        'kd_departemen',
        'hak_akses',
        'kd_cabang',
        'profile_img',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'kd_cabang');
    }

    public function departemen()
    {
        return $this->belongsTo(Departemen::class, 'kd_departemen');
    }

    public function hakAkses()
    {
        return $this->belongsTo(HakAkses::class, 'hak_akses');
    }

    public function createdQR()
    {
        return $this->hasMany(QR::class, 'created_by');
    }

    public function createdTasks()
    {
        return $this->hasMany(Task::class, 'user_id');
    }

    public function liveChats()
    {
        return $this->hasMany(LiveChat::class, 'user_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }
    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }
    public function threads()
    {
        return $this->hasMany(Thread::class, 'author_id');
    }
    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }
}
