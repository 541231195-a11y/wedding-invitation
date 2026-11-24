<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invitation;

class InvitationController extends Controller
{
    // Handler jika diakses dari root (127.0.0.1:8000/)
    public function showDefault(Request $request)
    {
        return $this->show($request, 'dwi-baidowi');
    }

    // Handler jika diakses dengan slug (127.0.0.1:8000/dwi-baidowi)
    public function show(Request $request, $slug)
    {
        $invitation = Invitation::firstOrCreate(
            ['slug' => $slug],
            ['title' => 'The Wedding of Dwi & Baidowi']
        );
        
        $guestName = $request->query('to', 'Tamu Undangan');
        $invitationCode = $request->query('code', '');

        return view('invitation', compact('invitation', 'guestName', 'invitationCode'));
    }
}
