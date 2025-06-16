<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function responder(Request $request)
    {
        $mensaje = $request->input('mensaje');

        $respuestaIA = Http::withHeaders([
            'Authorization' => 'Bearer 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',
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => "Eres un asistente de inteligencia artificial especializado en responder únicamente preguntas relacionadas con tecnología, informática y productos como laptops, impresoras, accesorios, etc. Si te preguntan otra cosa, responde: 'Lo siento, solo puedo responder preguntas sobre tecnología e informática.' Si la respuesta es válida, al final agrega: 'Para más información puedes visitar nuestra tienda: https://innovanegocios.com'."
                ],
                [
                    'role' => 'user',
                    'content' => $mensaje
                ]
            ],
            'temperature' => 0.7,
        ]);

        if ($respuestaIA->failed()) {
    return response()->json([
        'respuesta' => 'Error OpenAI: ' . $respuestaIA->body() // Muestra el error textual de la API
    ], 500);
}

    }
}
