<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $fillable = [
        'slug',
        'nama_kelas',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($kelas) {
            $baseSlug = Str::slug($kelas->nama_kelas);
            $slug = "{$baseSlug}-" . uniqid();

            while (Kelas::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-" . uniqid();
            }

            $kelas->slug = $slug;
        });
        
        static::updating(function ($kelas) {
            if ($kelas->isDirty('nama_kelas')) {
                $baseSlug = Str::slug($kelas->nama_kelas);
                $slug = "{$baseSlug}-" . uniqid();

                while (Kelas::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-" . uniqid();
                }

                $kelas->slug = $slug;
            }
        });
    }

    public function getRouteKeyName() {
        return 'slug';
    }

    public function pendaftaran() {
        return $this->hasMany(Pendaftaran::class);
    }
    
}
