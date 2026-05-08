<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'asunto' => 'nullable|string|max:255',
            'mensaje' => 'required|string',
        ]);

        $contact = Contact::create($validated);
        
        return response()->json([
            'success' => true,
            'message' => 'Mensaje enviado correctamente',
            'data' => $contact
        ], 201);
    }
}