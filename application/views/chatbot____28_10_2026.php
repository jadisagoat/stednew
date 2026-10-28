<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userInput = htmlspecialchars($_POST['user_input']);

    $url = 'AI_SERVICE_ENDPOINT'; // Replace with the actual endpoint of the AI service
    // Key lives in application/config/secrets.php (not committed)
    $secrets = is_file(APPPATH . 'config/secrets.php') ? include APPPATH . 'config/secrets.php' : array();
    $apiKey = isset($secrets['chatbot_api_key']) ? $secrets['chatbot_api_key'] : '';

    // Setup the HTTP request headers and payload
    $headers = [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ];

    $postData = json_encode(['prompt' => $userInput, 'other_parameters' => 'values']); // Customize this payload as per the AI service's API documentation

    // Initialize cURL session
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

    // Execute the cURL session and close it
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode the JSON response from the AI service
    $decodedResponse = json_decode($response, true);

    // Handle the response (this will vary based on the AI service and your application's logic)
    // For example, if this is a chatbot, you might display the AI's response back to the user
    echo $decodedResponse['ai_response_key']; // Replace 'ai_response_key' with the actual key that contains the response in the AI service's response JSON
}
?>