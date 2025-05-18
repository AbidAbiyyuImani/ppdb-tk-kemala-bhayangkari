<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\DataAdminController;
use App\Http\Controllers\DataDokumenController;
use App\Http\Controllers\PendaftaranController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\VerificationController;
use App\Http\Controllers\DataNotifikasiController;
use App\Http\Controllers\DataVerifikasiController;
use App\Http\Controllers\DataPendaftaranController;

Route::view('/', 'dashboard')->name('home');
Route::view('/tentang', 'about')->name('show.about');

Route::controller(AuthController::class)->group(function () {
    Route::get('/daftar', 'showRegister')->name('show.register');
    Route::post('/daftar', 'register')->name('register');
    Route::get('/masuk', 'showLogin')->name('show.login');
    Route::post('/masuk', 'login')->name('login');
});

Route::middleware('auth')->group(function () {
    Route::post('/keluar', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('checkUserSlug')->group(function () {
        Route::controller(NotificationController::class)->group(function () {
            Route::get('/notifikasi/{user:slug}', 'showNotification')->name('show.notification');
            Route::get('/notifikasi/{user:slug}/{slug}', 'readNotification')->name('read.notification');
        });
    });

    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profil', 'showProfile')->name('show.profile');
        Route::patch('/profil', 'updateProfile')->name('update.profile');
    });

    Route::middleware('role:Orang Tua')->group(function () {
        Route::controller(PendaftaranController::class)->group(function () {
            Route::view('/pendaftaran', 'auth.user.pendaftaran')->name('show.pendaftaran');
            Route::post('/pendaftaran/tambah', 'storePendaftaran')->name('store.pendaftaran');
            Route::get('/pendaftaran/list', 'showListPendaftaran')->name('show.list-pendaftaran');
            Route::get('/pendaftaran/{user}/{pendaftaran}', 'showDetailPendaftaran')->name('show.detail-pendaftaran');
            Route::patch('/pendaftaran/{user}/{pendaftaran}/ubah', 'updateDetailPendaftaran')->name('update.detail-pendaftaran');
        });
    });

    Route::middleware('role:Admin')->group(function () {
        Route::controller(VerificationController::class)->group(function () {
            Route::get('/verifikasi', 'showVerification')->name('show.verification');
            Route::get('/verifikasi/{pendaftaran}', 'showDetailVerification')->name('show.detail.verification');
            Route::post('/verifikasi/{pendaftaran}', 'storeVerification')->name('store.verification');
        });
        
        Route::prefix('master-data')->group(function () {
            Route::view('/', 'auth.admin.master-data.index')->name('show.master-data');

            Route::controller(DataAdminController::class)->group(function () {
                Route::get('/admin', 'showDataAdmin')->name('show.data-admin');
                Route::view('/admin/tambah', 'auth.admin.master-data.admin.create')->name('show.create.data-admin');
                Route::post('/admin/tambah', 'storeDataAdmin')->name('store.data-admin');
                Route::get('/admin/{admin:slug}/ubah', 'showUpdateDataAdmin')->name('show.update.data-admin');
                Route::patch('/admin/{admin:slug}/ubah', 'updateDataAdmin')->name('update.data-admin');
                Route::patch('/admin/{admin:slug}/pulihkan', 'restoreDataAdmin')->name('restore.data-admin');
                Route::delete('/admin/{admin:slug}/hapus', 'destroyDataAdmin')->name('destroy.data-admin');
            });

            Route::controller(DataUserController::class)->group(function () {
                Route::get('/user', 'showDataUser')->name('show.data-user');
                Route::view('/user/tambah', 'auth.admin.master-data.user.create')->name('show.create.data-user');
                Route::post('/user/tambah', 'storeDataUser')->name('store.data-user');
                Route::get('/user/{user:slug}/ubah', 'showUpdateDataUser')->name('show.update.data-user');
                Route::patch('/user/{user:slug}/ubah', 'updateDataUser')->name('update.data-user');
                Route::patch('/user/{user:slug}/pulihkan', 'restoreDataUser')->name('restore.data-user');
                Route::delete('/user/{user:slug}/hapus', 'destroyDataUser')->name('destroy.data-user');
            });

            Route::controller(DataPendaftaranController::class)->group(function () {
                Route::get('/pendaftaran', 'showDataPendaftaran')->name('show.data-registration');
                Route::view('/pendaftaran/tambah', 'auth.admin.master-data.pendaftaran.create')->name('show.create.data-registration');
                Route::post('/pendaftaran/tambah', 'storeDataPendaftaran')->name('store.data-registration');
                Route::get('/pendaftaran/{user}/{pendaftaran}/ubah', 'showUpdateDataPendaftaran')->name('show.update.data-registration');
                Route::patch('/pendaftaran/{user}/{pendaftaran}/ubah', 'updateDataPendaftaran')->name('update.data-registration');
                Route::patch('/pendaftaran/{user}/{pendaftaran}/pulihkan', 'restoreDataPendaftaran')->name('restore.data-registration');
                Route::delete('/pendaftaran/{user}/{pendaftaran}/hapus', 'destroyDataPendaftaran')->name('destroy.data-registration');
            });

            Route::controller(DataDokumenController::class)->group(function () {
                Route::get('/dokumen', 'showDataDokumen')->name('show.data-document');
                Route::get('/dokumen/tambah', 'showCreateDataDokumen')->name('show.create.data-document');
                Route::post('/dokumen/tambah', 'storeDataDokumen')->name('store.data-document');
                Route::get('/dokumen/{pendaftaran}/{dokumen}/ubah', 'showUpdateDataDokumen')->name('show.update.data-document');
                Route::patch('/dokumen/{pendaftaran}/{dokumen}/ubah', 'updateDataDokumen')->name('update.data-document');
                Route::patch('/dokumen/{pendaftaran}/{dokumen}/pulihkan', 'restoreDataDokumen')->name('restore.data-document');
                Route::delete('/dokumen/{pendaftaran}/{dokumen}/hapus', 'destroyDataDokumen')->name('destroy.data-document');
            });

            Route::controller(DataVerifikasiController::class)->group(function () {
                Route::get('/verifikasi', 'showDataVerifikasi')->name('show.data-verification');
                Route::get('/verifikasi/tambah', 'showCreateDataVerifikasi')->name('show.create.data-verification');
                Route::post('/verifikasi/tambah', 'storeDataVerifikasi')->name('store.data-verification');
                Route::get('/verifikasi/{pendaftaran}/{verifikasi}/ubah', 'showUpdateDataVerifikasi')->name('show.update.data-verification');
                Route::patch('/verifikasi/{pendaftaran}/{verifikasi}/ubah', 'updateDataVerifikasi')->name('update.data-verification');
                Route::patch('/verifikasi/{pendaftaran}/{verifikasi}/pulihkan', 'restoreDataVerifikasi')->name('restore.data-verification');
                Route::delete('/verifikasi/{pendaftaran}/{verifikasi}/hapus', 'destroyDataVerifikasi')->name('destroy.data-verification');
            });

            Route::controller(DataNotifikasiController::class)->group(function () {
                Route::get('/notifikasi', 'showDataNotifikasi')->name('show.data-notification');
                Route::get('/notifikasi/tambah', 'showCreateDataNotifikasi')->name('show.create.data-notification');
                Route::post('/notifikasi/tambah', 'storeDataNotifikasi')->name('store.data-notification');
                Route::get('/notifikasi/{user}/{notifikasi}/ubah', 'showUpdateDataNotifikasi')->name('show.update.data-notification');
                Route::patch('/notifikasi/{user}/{notifikasi}/ubah', 'updateDataNotifikasi')->name('update.data-notification');
                Route::patch('/notifikasi/{user}/{notifikasi}/pulihkan', 'restoreDataNotifikasi')->name('restore.data-notification');
                Route::delete('/notifikasi/{user}/{notifikasi}/hapus', 'destroyDataNotifikasi')->name('destroy.data-notification');
            });
        });
    });
});