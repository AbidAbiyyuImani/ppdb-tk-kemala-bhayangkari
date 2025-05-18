<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function showNotification($userSlug) {
        $notifikasi = auth()->user()->notifikasi()->whereNull('deleted_at')->latest()->paginate(10);
        $notifikasi->load('user');
        return view('auth.notification.index', compact('notifikasi'));
    }

    public function readNotification($userSlug, $notifSlug) {
        try {
            $notifikasi = auth()->user()->notifikasi()->whereNull('deleted_at')->where('slug', $notifSlug)->firstOrFail();
        } catch (\Exception $e) {
            return redirect()->route('show.notification', [$userSlug])->with('error', 'Notifikasi tidak ditemukan.');
        }
        
        $notifikasi->update(['status_baca' => 'Dibaca']);
        return view('auth.notification.detail', compact('notifikasi'));
        
        return redirect()->route('show.notification', [$userSlug])->with('error', 'Notifikasi tidak ditemukan.');
    }
}