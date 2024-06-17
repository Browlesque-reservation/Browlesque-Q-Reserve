<?php
header('Content-Type: application/json');

// Execute the Python script and capture both standard output and standard error
$output = shell_exec('python apriori.py 2>&1');

// Check if the output is not empty
if ($output === null) {
    echo json_encode(["status" => "error", "message" => "Failed to execute Python script"]);
    exit;
}

// Trim the output to remove any leading or trailing whitespace
$output = trim($output);

// Separate the lines of output
$outputLines = explode("\n", $output);

// Process each line separately
$response = null;
foreach ($outputLines as $line) {
    // Attempt to decode each line as JSON
    $line = trim($line);
    if (strpos($line, '{') === 0 && strpos($line, '}') !== false) {
        // If the line starts with '{' and ends with '}', it is likely a JSON object
        $decoded = json_decode($line, true);
        if ($decoded !== null && json_last_error() === JSON_ERROR_NONE) {
            // If decoding is successful, set the response and break the loop
            $response = $decoded;
            break;
        }
    }
}

// Check if a valid JSON response was found
if ($response !== null) {
    // Output the JSON response
    echo json_encode($response);
} else {
    // If no valid JSON response was found, return an error
    echo json_encode(["status" => "error", "message" => "Invalid JSON response from Python script"]);
}
?>
