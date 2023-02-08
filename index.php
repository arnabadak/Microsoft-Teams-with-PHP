<?php

// Register your application and get the client ID and secret
// from the Azure portal https://portal.azure.com/

$clientId = "<client_id>";
$clientSecret = "<client_secret>";

// Request an access token

$url = "https://login.microsoftonline.com/common/oauth2/v2.0/token";
$data = array(
    "client_id" => $clientId,
    "client_secret" => $clientSecret,
    "grant_type" => "client_credentials",
    "scope" => "https://graph.microsoft.com/.default"
);

$options = array(
    "http" => array(
        "header" => "Content-type: application/x-www-form-urlencoded",
        "method" => "POST",
        "content" => http_build_query($data)
    )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$result = json_decode($response, true);
$accessToken = $result["access_token"];

// Make an API request to send a message to a Teams channel

$url = "https://graph.microsoft.com/v1.0/teams/<team_id>/channels/<channel_id>/messages";
$data = array(
    "body" => array(
        "content" => "Hello from PHP"
    )
);

$options = array(
    "http" => array(
        "header" => "Authorization: Bearer " . $accessToken . "\r\n" .
                    "Content-Type: application/json",
        "method" => "POST",
        "content" => json_encode($data)
    )
);

$context = stream_context_create($options);
$response = file_get_contents($url, false, $context);
$result = json_decode($response, true);

// Check the response for success

if ($result) {
    echo "Message sent successfully";
} else {
    echo "Message sending failed";
}

?>
