<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Verifikasi extends Model
{
    use softDeletes;
    protected $table = 'verifikasi';
    protected $primaryKey = 'id_verifikasi';
    protected $fillable = [
        'pendaftaran_id',
        'admin_id',
        'slug',
        'catatan',
        'hasil_verifikasi',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($verifikasi) {
            $slug = "verifikasi-" . uniqid();

            while (Verifikasi::where('slug', $slug)->exists()) {
                $slug = "verifikasi-" . uniqid();
            }

            $verifikasi->slug = $slug;
        });

        static::updating(function ($verifikasi) {
            if ($verifikasi->isDirty('catatan')) {
                $slug = "verifikasi-" . uniqid();

                while (Verifikasi::where('slug', $slug)->exists()) {
                    $slug = "verifikasi-" . uniqid();
                }

                $verifikasi->slug = $slug;
            }
        });
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    public function admin() {
        return $this->belongsTo(User::class, 'admin_id', 'id_user')->withTrashed();
    }

    public function pendaftaran() {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'id_pendaftaran')->withTrashed();
    }
}