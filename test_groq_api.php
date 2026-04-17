<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

function json_response(int $status, array $payload): void {
    http_response_code($status);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    exit;
}

function load_env_from_root(string $rootPath): void {
    static $loaded = false;
    if ($loaded) {
        return;
    }
    $loaded = true;

    $envPath = rtrim($rootPath, '/\\') . DIRECTORY_SEPARATOR . '.env';
    if (!is_file($envPath) || !is_readable($envPath)) {
        return;
    }

    $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (!is_array($lines)) {
        return;
    }

    foreach ($lines as $line) {
        $line = trim((string)$line);
        if ($line === '' || strpos($line, '#') === 0 || strpos($line, '=') === false) {
            continue;
        }

        $parts = explode('=', $line, 2);
        $name = trim((string)($parts[0] ?? ''));
        $value = trim((string)($parts[1] ?? ''));

        if ($name === '') {
            continue;
        }

        $len = strlen($value);
        if ($len >= 2) {
            $first = $value[0];
            $last = $value[$len - 1];
            if (($first === '"' && $last === '"') || ($first === "'" && $last === "'")) {
                $value = substr($value, 1, -1);
            }
        }

        $current = getenv($name);
        if ($current === false || trim((string)$current) === '') {
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

if (!function_exists('curl_init')) {
    json_response(500, [
        'success' => false,
        'message' => 'PHP chưa bật cURL extension. Vui lòng bật php_curl trong php.ini.'
    ]);
}

load_env_from_root(__DIR__);

$keyFromEnv = getenv('GROQ_API_KEY');
$keyFromRequest = isset($_GET['key']) ? trim((string)$_GET['key']) : '';
if ($keyFromRequest === '' && isset($_POST['key'])) {
    $keyFromRequest = trim((string)$_POST['key']);
}

$keySource = 'none';
$rootEnvPath = __DIR__ . DIRECTORY_SEPARATOR . '.env';
if ($keyFromEnv !== false && trim((string)$keyFromEnv) !== '') {
    $keySource = is_file($rootEnvPath) ? 'env_file' : 'system_env';
}
if ($keySource === 'none' && $keyFromRequest !== '') {
    $keySource = 'request';
}

$apiKey = $keyFromEnv !== false && trim($keyFromEnv) !== ''
    ? trim((string)$keyFromEnv)
    : $keyFromRequest;

if ($apiKey === '') {
    json_response(400, [
        'success' => false,
        'message' => 'Thiếu GROQ_API_KEY.',
        'how_to_test' => [
            'Ưu tiên lưu key trong file .env ở thư mục gốc dự án.',
            'Hoặc test nhanh: /test_groq_api.php?key=GROQ_API_KEY_CUA_BAN'
        ]
    ]);
}

$model = isset($_GET['model']) && trim((string)$_GET['model']) !== ''
    ? trim((string)$_GET['model'])
    : 'llama-3.1-8b-instant';

$question = isset($_GET['q']) && trim((string)$_GET['q']) !== ''
    ? trim((string)$_GET['q'])
    : 'Trả lời đúng 1 từ: OK';

$payload = [
    'model' => $model,
    'temperature' => 0,
    'messages' => [
        ['role' => 'system', 'content' => 'Bạn là bot test kết nối API.'],
        ['role' => 'user', 'content' => $question]
    ]
];

$ch = curl_init('https://api.groq.com/openai/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload, JSON_UNESCAPED_UNICODE));
curl_setopt($ch, CURLOPT_TIMEOUT, 20);

$start = microtime(true);
$raw = curl_exec($ch);
$latencyMs = (int)round((microtime(true) - $start) * 1000);

if ($raw === false) {
    $curlError = curl_error($ch);
    curl_close($ch);

    json_response(502, [
        'success' => false,
        'message' => 'Không gọi được Groq API.',
        'error' => $curlError,
        'latency_ms' => $latencyMs
    ]);
}

$httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$decoded = json_decode($raw, true);
$assistant = is_array($decoded)
    ? (string)($decoded['choices'][0]['message']['content'] ?? '')
    : '';

$ok = $httpCode >= 200 && $httpCode < 300 && $assistant !== '';

json_response($ok ? 200 : 502, [
    'success' => $ok,
    'message' => $ok ? 'Groq API hoạt động bình thường.' : 'Groq API chưa hoạt động đúng.',
    'key_source' => $keySource,
    'http_code' => $httpCode,
    'latency_ms' => $latencyMs,
    'model' => $model,
    'question' => $question,
    'assistant_reply' => $assistant,
    'raw_response' => $decoded
]);
