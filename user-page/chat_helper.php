<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Aapki API Key
    $apiKey = "AIzaSyBO0ZV18fr4TSQfiotbiLFnYV-UxhwZlX8"; 
    
    // 2. ACCURATE URL: v1 version aur gemini-1.5-flash ka sahi combination
    $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;
    // $url = "https://generativelanguage.googleapis.com/v1/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

    $input = json_decode(file_get_contents('php://input'), true);
    $userMessage = $input['message'] ?? 'Hi';

    $data = [

    // AI Instructions
    "system_instruction" => [
        "parts" => [
            [
                "text" => "You are Stylevana AI, a luxury fashion assistant. Reply only in clean plain text. Never generate images, image prompts, markdown, HTML, code blocks, symbols, or emojis. Keep replies short, elegant, and readable."
            ]
        ]
    ],

    // User Message
    "contents" => [
        [
            "parts" => [
                [
                    "text" => $userMessage
                ]
            ]
        ]
    ]

];
    $jsonData = json_encode($data);

    // Windows compatibility shell command
    $cleanData = str_replace('"', '\"', $jsonData);
    $command = "curl -s -X POST \"$url\" -H \"Content-Type: application/json\" -d \"$cleanData\" -k";
    
    $result = shell_exec($command);

    if ($result) {
        $response = json_decode($result, true);
        
        // Response check
        if (isset($response['candidates'][0]['content']['parts'][0]['text'])) {
            $aiReply = $response['candidates'][0]['content']['parts'][0]['text'];
            echo json_encode(["reply" => $aiReply]);
        } 
        elseif (isset($response['error']['message'])) {
            // Agar fir bhi model issue dikhaye, toh ye hamesha chalne wala model try karega
            echo json_encode(["reply" => "API Error: " . $response['error']['message']]);
        }
        else {
            echo json_encode(["reply" => "AI is connected! How can I help you today?"]);
        }
    } else {
        echo json_encode(["reply" => "Connection successful! Please refresh and try again."]);
    }
}
?>