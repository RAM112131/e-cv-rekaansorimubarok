<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ContactController extends Controller
{
    /**
     * Handle contact form submission from public page.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email', 'max:150'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:2000'],
        ], [
            'name.required'    => 'Nama wajib diisi.',
            'email.required'   => 'Email wajib diisi.',
            'email.email'      => 'Format email tidak valid.',
            'message.required' => 'Pesan wajib diisi.',
            'message.min'      => 'Pesan minimal 10 karakter.',
        ]);

        try {
            Message::create([
                'name'    => $validated['name'],
                'email'   => $validated['email'],
                'subject' => $validated['subject'] ?? null,
                'message' => $validated['message'],
                'is_read' => false,
            ]);

            // Clear navigation badge cache so admin sees new message count
            Cache::forget('nav.badge.unread_messages');

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi saya.',
                ]);
            }

            return redirect()->to(url()->previous() . '#kontak')->with('contact_success', 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi saya.');
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengirim pesan. Silakan coba beberapa saat lagi.',
                ], 500);
            }

            return redirect()->to(url()->previous() . '#kontak')->with('contact_error', 'Gagal mengirim pesan. Silakan coba beberapa saat lagi.');
        }
    }
}
