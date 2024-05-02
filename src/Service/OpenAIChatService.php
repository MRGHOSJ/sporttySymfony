<?php

namespace App\Service;

use GuzzleHttp\Client;

class OpenAIChatService
{
    public function sendMessage($message)
    {
        $client = new Client();

        $response = $client->request('POST', 'https://chatgpt-api8.p.rapidapi.com/', [
            'json' => [
                [
                    'content' => "Hello! I'm an AI assistant bot based on ChatGPT 3. How may I help you?",
                    'role' => 'system'
                ],
                [
                    'content' => $message,
                    'role' => 'user'
                ]
            ],
            'headers' => [
                'X-RapidAPI-Host' => 'chatgpt-api8.p.rapidapi.com',
                'X-RapidAPI-Key' => 'fe4429ae05mshe9b08fc10a6eaefp1e30c9jsn26fb0b6e7731',
                'Content-Type' => 'application/json',
            ],
        ]);

        return json_decode($response->getBody()->getContents(), true)['text'] ?? null;
    }
}
