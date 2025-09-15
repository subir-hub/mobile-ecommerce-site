<?php
session_start();

$envFile = __DIR__ . '/../.env'; 

// Read .env file into array
$lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
$env = [];

foreach ($lines as $line) {
    if (strpos(trim($line), '#') === 0) continue; // Skip comments
    if (strpos($line, '=') !== false) {
        list($key, $value) = explode('=', $line, 2);
        $env[trim($key)] = trim($value, " \""); // Remove spaces and quotes
    }
}

// Use API key from .env
$apiKey = $env['API_KEY'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $userMsg = htmlspecialchars(trim($_POST['msg']));

    // Database connection
    require '../admin/config/db.php';

    // Fetch products
    $sql = "SELECT id, product_name, price FROM products";
    $result = $conn->query($sql);

    $productList = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productList[] = $row['product_name'] . " (₹" . number_format($row['price']) . ")";
        }
    }

    $productText = "Available products are: " . implode(", ", $productList) . ".";
    $productCount = count($productList);
    $bestProduct = "Xiaomi Redmi 14C (₹10,999)";
    $faq = "FAQ: 1. Shipping takes 3-5 days. 2. Return allowed within 7 days.";

    $url = "https://api.openai.com/v1/chat/completions";

    $data = [
        "model" => "gpt-4o-mini",
        "messages" => [
            [
                "role" => "system",
                "content" => "You are a helpful assistant for an e-commerce site. There are {$productCount} products. {$productText} The best product is {$bestProduct}. {$faq} Answer queries about how many products are available, which is best, and FAQs."
            ],
            [
                "role" => "user",
                "content" => $userMsg
            ]
        ],
        "max_tokens" => 150,
        "temperature" => 0.7
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    $reply = $result['choices'][0]['message']['content'] ?? "Sorry, I couldn’t understand that.";

    echo json_encode(["reply" => $reply]);
}
?>
