<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use softDeletes;
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $fillable = [
        'slug',
        'nama',
        'email',
        'password',
        'role',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($user) {
            $slug = Str::slug($user->nama);;

            if (static::where('slug', $slug)->exists()) {
                throw new \Exception("Slug sudah ada, silakan gunakan nama lain.");
            }

            $user->slug = $slug;
        });
        
        static::updating(function ($user) {
            if ($user->isDirty('nama')) {
                $slug = Str::slug($user->nama);

                if (static::where('slug', $slug)->exists()) {
                    throw new \Exception("Slug sudah ada, silakan gunakan nama lain.");
                }

                $user->slug = $slug;
            }
        });
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    public function pendaftaran() {
        return $this->hasMany(pendaftaran::class, 'user_id', 'id_user')->withTrashed();
    }

    public function verifikasi() {
        return $this->hasMany(Verifikasi::class, 'admin_id', 'id_user')->withTrashed();
    }

    public function notifikasi() {
        return $this->hasMany(Notifikasi::class, 'user_id', 'id_user')->withTrashed();
    }
}
