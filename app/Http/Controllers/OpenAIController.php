<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\JsonResponse;

class OpenAIController extends Controller
{
    public function index(): JsonResponse 
    {
        $search = "write description of home that is located in bahawalpur near kfc and having 4 bed room. the description should be of 100 words length only";

        $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPEN_API_KEY'),
            ])
            ->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        "role" => 'user',
                        'content' => $search
                    ]
                ]
            ]);

        if ($response->successful()) {
            return response()->json([
                'success' => true,
                'data' => $response->json()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => $response->body()
            ], $response->status());
        }
    }
}
