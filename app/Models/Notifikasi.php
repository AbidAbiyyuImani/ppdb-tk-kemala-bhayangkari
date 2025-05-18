<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    use softDeletes;
    protected $table = 'notifikasi';
    protected $primaryKey = 'id_notifikasi';
    protected $fillable = [
        'user_id',
        'slug',
        'judul',
        'isi_pesan',
        'status_baca',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($notifikasi) {
            $baseSlug = Str::slug($notifikasi->judul);
            $slug = "{$baseSlug}-" . uniqid();

            while (Notifikasi::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-" . uniqid();
            }

            $notifikasi->slug = $slug;
        });

        static::updating(function ($notifikasi) {
            if ($notifikasi->isDirty('judul')) {
                $baseSlug = Str::slug($notifikasi->judul);
                $slug = "{$baseSlug}-" . uniqid();

                while (Notifikasi::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-" . uniqid();
                }

                $notifikasi->slug = $slug;
            }
        });
        
    }
    
    public function getRouteKeyName() {
        return 'slug';
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id_user')->withTrashed();
    }
}
