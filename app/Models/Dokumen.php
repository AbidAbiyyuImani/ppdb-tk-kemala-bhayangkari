<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use softDeletes;
    protected $table = 'dokumen';
    protected $primaryKey = 'id_dokumen';
    protected $fillable = [
        'pendaftaran_id',
        'slug',
        'nama_dokumen',
        'path_dokumen',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($dokumen) {
            $baseSlug = Str::slug($dokumen->nama_dokumen);
            $slug = "{$baseSlug}-" . uniqid();

            while (Dokumen::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-" . uniqid();
            }

            $dokumen->slug = $slug;
        });
        
        static::updating(function ($dokumen) {
            if ($dokumen->isDirty('nama_dokumen')) {
                $baseSlug = Str::slug($dokumen->nama_dokumen);
                $slug = "{$baseSlug}-" . uniqid();

                while (Dokumen::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-" . uniqid();
                }

                $dokumen->slug = $slug;
            }
        });
    }

    public function getRouteKeyName() {
        return 'slug';
    }
    
    public function pendaftaran() {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id', 'id_pendaftaran')->withTrashed();
    }
}
