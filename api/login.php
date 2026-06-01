<?php
// api/login.php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// 1. Koneksi ke Database
include 'koneksi.php';

// 2. Ambil Data dari Frontend (JSON)
$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

// Validasi input kosong
if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Username dan Password wajib diisi"]);
    exit();
}

// 3. Cek User di Database
// Kita pakai prepared statement biar aman dari SQL Injection
$stmt = $conn->prepare("SELECT id_admin, nama, username, password, role FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // 4. Verifikasi Password (Cocokkan hash bcrypt)
    if (password_verify($password, $user['password'])) {
        // Login Berhasil
        http_response_code(200);
        echo json_encode([
            "success" => true,
            "message" => "Login berhasil",
            "data" => [
                "id" => $user['id_admin'],
                "nama" => $user['nama'],
                "role" => $user['role']
            ]
        ]);
    } else {
        // Password Salah
        http_response_code(401);
        echo json_encode(["error" => "Password salah"]);
    }
} else {
    // Username Tidak Ditemukan
    http_response_code(404);
    echo json_encode(["error" => "Username tidak ditemukan"]);
}

$stmt->close();
$conn->close();
?>
