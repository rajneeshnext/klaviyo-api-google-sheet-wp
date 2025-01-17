<?php
echo "<pre>";
// Your Klaviyo private API key
$apiKey = "xxxx";
$metricId = "xxxx";
$orderProductMetricID = "xxxx";
//flowtriggers($apiKey);exit();
metricAggregates($apiKey, $metricId);exit();
//getMetrics($apiKey);exit();
//getMetricFlows($apiKey, $metricId);exit();
function getMetricFlows($apiKey, $metricId){
// API endpoint
$url = "https://a.klaviyo.com/api/metrics/$metricId/flow-triggers/";

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Klaviyo-API-Key $apiKey",
    "accept: application/vnd.api+json",
    "revision: 2025-01-15"
]);

// Execute the request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // Decode and print the response
    $data = json_decode($response, true);
    print_r($data);
}

// Close cURL
curl_close($ch);
exit();
}
function getMetrics($apiKey){
// API endpoint
$url = "https://a.klaviyo.com/api/metrics";

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Klaviyo-API-Key $apiKey",
    "accept: application/vnd.api+json",
    "revision: 2025-01-15"
]);

// Execute the request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
} else {
    // Decode and print the response
    $data = json_decode($response, true);
    print_r($data);
}

// Close cURL
curl_close($ch);
exit();
}

// Your Klaviyo private API key
function metricAggregates($apiKey, $orderProductMetricID){
// API endpoint for metric aggregates
$url = "https://a.klaviyo.com/api/metric-aggregates";
$startDate = "2023-01-01"; // Replace with your desired start date
$endDate = date("Y-m-d"); // End date (default to today)
$now = new DateTime();
// Set the timezone to UTC
$now->setTimezone(new DateTimeZone('UTC'));
// Format the date in the desired format
$now->modify('last day of last month');
echo $currentDate = $now->format('Y-m-d\TH:i:s.v\Z');
echo "==";
$now = new DateTime();
//$now->modify('-30 day');
$now->modify('first day of last month');
echo $firstDate = $now->format('Y-m-d\T00:00:00.v\Z');
$attributed_flow = '$attributed_flow';
$attributed_message = '$attributed_message';
// Data to be sent in the POST request
$data = [
    "data" => [
        "type" => "metric-aggregate",
        "attributes" => [
            "interval" => "day",
            "page_size" => 500,
            "timezone" => "UTC",
            "measurements" => ["sum_value"],
            "filter" =>  [
                "greater-or-equal(datetime,$firstDate)",
                "less-than(datetime,$currentDate)",
                "not(equals($attributed_flow,\"\"))",
                //"not(equals($attributed_message,\"\"))"
                //"greater-or-equal(datetime,2025-01-01T00:01:00.710Z)",
                //"less-than(datetime,2025-01-15T12:30:00.710Z)"
            ],
            "by"=> [$attributed_flow],  
            "metric_id" => "$orderProductMetricID" // Replace with your actual metric ID
        ]
    ]
];
print_r($data);
// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Klaviyo-API-Key $apiKey",
    "accept: application/vnd.api+json",
    "content-type: application/vnd.api+json",
    "revision: 2025-01-15"
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Execute the request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Close cURL
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);

// Output the response
echo "<pre>";
print_r($data['data']['attributes']['data'][0]['measurements']['sum_value']);
echo "</pre>";

$totalRevenue = 0;
$sumValues = $data['data']['attributes']['data'][0]['measurements']['sum_value'];

foreach ($sumValues as $value) {
    $totalRevenue += $value;
}
echo "Total Revenue from $totalRevenue\n";
exit();
}    

function flowtriggers($apiKey){

// Specific metric ID
$metricId = "RTyLtv"; // Replace with your metric ID


// API endpoint to fetch metric data
$startDate = "2023-01-01"; // Replace with your desired start date
$endDate = date("Y-m-d"); // End date (default to today)
$url = "https://a.klaviyo.com/api/metrics/$metricId/flow-triggers";


//https://a.klaviyo.com/api/v1/metric/X8K8GZ/export?start_date=2021-11-01&end_date=2021-11-30&unit=week
//&measurement=value&where=%5B%5B%22%24attributed_message%22%2C%22%3D%22%2C%22Wf6rLC%22%5D%5D&api_key=MYAPIKEY

// Initialize cURL
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Klaviyo-API-Key $apiKey",
    "accept: application/vnd.api+json",
    "revision: 2025-01-15"
]);

// Execute the request
$response = curl_exec($ch);

// Check for cURL errors
if (curl_errno($ch)) {
    echo "cURL Error: " . curl_error($ch);
    curl_close($ch);
    exit;
}

// Close cURL
curl_close($ch);

// Decode the JSON response
$data = json_decode($response, true);
print_r($data);
// Calculate total revenue
$totalRevenue = 0;

if (isset($data['data'])) {
    foreach ($data['data'] as $event) {
        if (isset($event['properties']['value'])) {
            $totalRevenue += $event['properties']['value'];
        }
    }
    echo "Total Revenue from $startDate to $endDate: $" . number_format($totalRevenue, 2) . "\n";
} else {
    echo "Error fetching metric data: " . json_encode($data) . "\n";
}

// Close cURL
curl_close($ch);
}
?>
