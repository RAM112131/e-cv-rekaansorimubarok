<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Services\ContactMessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function __construct(private ContactMessageService $contactMessageService) {}

    public function store(StoreContactMessageRequest $request): JsonResponse|RedirectResponse
    {
        $this->contactMessageService->store($request->validated());

        $message = 'Pesan Anda berhasil dikirim! Terima kasih telah menghubungi saya.';

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        }

        return redirect()->to(url()->previous().'#kontak')->with('contact_success', $message);
    }
}
