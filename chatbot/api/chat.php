<?php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit(json_encode(['error' => 'Invalid request']));
}

$input = json_decode(file_get_contents('php://input'), true);
$message = $input['message'] ?? '';

if (empty($message)) {
    exit(json_encode(['error' => 'No message provided']));
}

// Simple responses (you can make this smarter later)
$responses = [
    'hello' => 'Hello! Welcome to EDOC Medical Center. How can I help you?',
    'appointment' => 'To book an appointment, please login and go to the appointments section.',
    'doctor' => 'We have qualified doctors available. Please check our doctor list.',
    'help' => 'I can help you with appointments, doctor information, and general inquiries.',
    'default' => 'Thank you for your message. For specific medical advice, please consult with our doctors.'
];

// Simple keyword matching
$response = $responses['default'];
$message_lower = strtolower($message);

foreach ($responses as $keyword => $reply) {
    if (strpos($message_lower, $keyword) !== false) {
        $response = $reply;
        break;
    }
}

echo json_encode([
    'success' => true,
    'response' => $response
]);
?>
