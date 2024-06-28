<?php
header('Content-Type: application/json');

// Path to the Python script
$pythonScriptPath = '/home/u155023598/domains/snow-elk-370295.hostingersite.com/public_html/apriori.py';

// Execute the Python script and capture both standard output and standard error
exec("python3 $pythonScriptPath 2>&1", $output, $returnVar);

// Check if the execution was successful
if ($returnVar !== 0) {
    echo json_encode(["status" => "error", "message" => "Failed to execute Python script", "output" => $output]);
    exit;
}

// Combine the output lines into a single string
$output = implode("\n", $output);

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
