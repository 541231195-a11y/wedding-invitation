<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuestBook;
use Illuminate\Support\Str;

class GuestBookController extends Controller
{
    public function countries()
    {
        return response()->json([
            'data' => [
                ['name' => 'Indonesia', 'iso2' => 'ID', 'dialCode' => 62],
                ['name' => 'Malaysia', 'iso2' => 'MY', 'dialCode' => 60],
            ]
        ]);
    }

    public function options($invitation_id)
    {
        return response()->json([
            'data' => [
                'rsvp_settings' => [
                    'is_private' => false,
                    'show_comments' => true,
                    'inputs' => [
                        ['name' => 'name', 'label' => 'Nama Lengkap', 'is_published' => true, 'required' => true],
                        ['name' => 'phone', 'label' => 'No. WhatsApp', 'is_published' => true, 'required' => true],
                        ['name' => 'attendance', 'label' => 'Konfirmasi Kehadiran', 'is_published' => true, 'required' => true, 'value' => 'Hadir, Tidak Hadir', 'showguest' => 'Hadir'],
                        ['name' => 'guest', 'label' => 'Jumlah Tamu', 'is_published' => true, 'required' => true, 'value' => '3'],
                        ['name' => 'comment', 'label' => 'Ucapan & Doa', 'is_published' => true, 'required' => true],
                    ]
                ],
                'custom_inputs' => []
            ]
        ]);
    }

    public function index($invitation_id, Request $request)
    {
        $guestBooks = GuestBook::where('invitation_id', $invitation_id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json(['data' => $guestBooks]);
    }

    public function store(Request $request, $invitation_id)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'attendance' => 'required|string',
        ]);

        $guest = GuestBook::create([
            'invitation_id' => $invitation_id,
            'code' => strtoupper(Str::random(6)),
            'name' => $request->name,
            'phone' => $request->phone,
            'country_code' => $request->country_code ?? 62,
            'attendance' => $request->attendance,
            'guest' => $request->guest ?? 1,
            'comment' => $request->comment,
        ]);

        return response()->json([
            'status' => 200,
            'status_code' => 'CONFIRMED',
            'message' => 'RSVP berhasil disimpan!',
            'data' => $guest
        ]);
    }
}
