<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Pendaftaran extends Model
{
    use softDeletes;
    protected $table = 'pendaftaran';
    protected $primaryKey = 'id_pendaftaran';
    protected $fillable = [
        'user_id',
        'kelas_id',
        'slug',
        'nama_anak',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'anak_ke',
        'status_anak',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'pekerjaan_ayah',
        'pekerjaan_ibu',
        'pekerjaan_wali',
        'alamat',
        'kelurahan',
        'no_telp',
        'email',
        'no_wa',
        'imunisasi_vaksin_yang_pernah_diterima',
        'penyakit_berat_yang_diderita',
        'jarak_dari_rumah',
        'golongan_darah',
        'status_pendaftaran',
    ];
    public const DOKUMEN_LIST = [
        'akta-kelahiran',
        'kartu-keluarga',
        'ktp-orang-tua',
        'pas-foto-peserta-didik',
    ];

    protected static function boot() {
        parent::boot();

        static::creating(function ($pendaftaran) {
            $baseSlug = Str::slug($pendaftaran->nama_anak);
            $slug = "{$baseSlug}-" . uniqid();

            while (Pendaftaran::where('slug', $slug)->exists()) {
                $slug = "{$baseSlug}-" . uniqid();
            }

            $pendaftaran->slug = $slug;
        });
        
        static::updating(function ($pendaftaran) {
            if ($pendaftaran->isDirty('nama_anak')) {
                $baseSlug = Str::slug($pendaftaran->nama_anak);
                $slug = "{$baseSlug}-" . uniqid();

                while (Pendaftaran::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-" . uniqid();
                }

                $pendaftaran->slug = $slug;
            }
        });
    }
    
    public function getRouteKeyName() {
        return 'slug';
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id_user')->withTrashed();
    }

    public function dokumen() {
        return $this->hasMany(Dokumen::class, 'pendaftaran_id', 'id_pendaftaran')->withTrashed();
    }

    public function getAktaKelahiranAttribute() {
        return $this->dokumen()->where('nama_dokumen', 'Akta Kelahiran')->whereNull('deleted_at')->first();
    }

    public function getKartuKeluargaAttribute() {
        return $this->dokumen()->where('nama_dokumen', 'Kartu Keluarga')->whereNull('deleted_at')->first();
    }

    public function getKtpOrangTuaAttribute() {
        return $this->dokumen()->where('nama_dokumen', 'KTP Orang Tua')->whereNull('deleted_at')->first();
    }

    public function getPasFotoPesertaDidikAttribute() {
        return $this->dokumen()->where('nama_dokumen', 'Pas Foto Peserta Didik')->whereNull('deleted_at')->first();
    }

    public function kelas() {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas');
    }

    public function verifikasi() {
        return $this->hasOne(Verifikasi::class, 'pendaftaran_id', 'id_pendaftaran')->withTrashed();
    }
}