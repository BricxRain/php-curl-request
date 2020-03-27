<?php

require 'controller/CurlController.php';

$data = [
    "data" => "data"
];


$result = CurlController::request('xxxxx', 'https://www.google.com', $data, 'POST');

echo json_encode($result);