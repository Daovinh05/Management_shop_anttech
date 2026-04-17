<?php
// Load biến môi trường từ .env
$envPath = __DIR__ . '/.env';

if (file_exists($envPath)) {
    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0 || strpos($line, '=') === false) continue;
        list($name, $value) = array_map('trim', explode('=', $line, 2));
        if (!getenv($name)) {
            putenv("$name=$value");
        }
    }
}

// Lấy API key
$api_key = getenv('GROQ_API_KEY');

if (!$api_key) {
    echo "❌ Chưa có GROQ_API_KEY trong file .env\n";
    exit;
}

// API URL
$groq_url = 'https://api.groq.com/openai/v1/chat/completions';

// Model
$model = 'llama-3.3-70b-versatile';

// Data gửi đi
$data = [
    'model' => $model,
    'messages' => [
        ['role' => 'system', 'content' => 'Bạn là AI test Groq.'],
        ['role' => 'user', 'content' => 'Xin chào, bạn có hoạt động không?']
    ],
    'max_tokens' => 50,
    'temperature' => 0.7
];

// Init curl
$ch = curl_init($groq_url);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);

curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . $api_key
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Gửi request
$result = curl_exec($ch);

// Check lỗi curl
if (curl_errno($ch)) {
    echo "❌ CURL Error: " . curl_error($ch) . "\n";
    curl_close($ch);
    exit;
}

// Lấy HTTP code
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// In kết quả
echo "HTTP code: $httpcode\n";

if ($httpcode !== 200) {
    echo "❌ API lỗi:\n";
    echo $result;
    exit;
}

// Decode JSON
$response = json_decode($result, true);

// In nội dung AI trả về
if (isset($response['choices'][0]['message']['content'])) {
    echo "🤖 AI trả lời:\n";
    echo $response['choices'][0]['message']['content'] . "\n";
} else {
    echo "❌ Không đọc được response:\n";
    print_r($response);
}