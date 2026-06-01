<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

$host = "gateway01.ap-southeast-1.prod.alicloud.tidbcloud.com";
$user = "4HiqgjKudvGuqfv.root";
$pass = "IUgsMwolBgS1hU2M";
$db   = "koperasikas_db";

$conn = new mysqli($host, $user, $pass, $db, 4000);

// Cek Koneksi
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["error" => "Gagal terhubung ke database: " . $conn->connect_error]);
    exit();
}

// Opsional: Set timezone ke Jakarta
$conn->query("SET time_zone = '+07:00'");
?>
