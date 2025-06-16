<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class GeminiChatController extends Controller
{
    public function responder_gemini(Request $request)
    {
        $mensaje = $request->input('mensaje');
        $apiKey = 'AIzaSyBVAK0yk3DxgypyoRiRzGzYPo6SZkW7JAw'; 

       $response = Http::post("https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=$apiKey", [
    'contents' => [
        [
            'role' => 'user',
            'parts' => [
                ['text' => "Solo responde preguntas sobre tecnología, productos informáticos o soporte técnico. Si no es del tema, di: 'Lo siento, solo puedo ayudarte con tecnología.'\n\nUsuario: $mensaje"]
            ]
        ]
    ]
]);



        if ($response->successful()) {
        $respuestaTexto = $response['candidates'][0]['content']['parts'][0]['text'] ?? 'Sin respuesta';
        $respuestaFinal = $respuestaTexto . "\n\n🛍️ Para más información visita nuestra tienda: https://innovanegocios.com";
        return response()->json(['respuesta' => $respuestaFinal]);

    } else {
        $error = $response->json()['error']['message'] ?? 'Error desconocido';
        return response()->json(['respuesta' => '⚠️ Error Gemini: ' . $error], 500);
    }



    }
}
