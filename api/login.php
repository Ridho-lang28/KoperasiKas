<?php
// api/login.php
include 'koneksi.php';

$input = json_decode(file_get_contents('php://input'), true);
$username = $input['username'] ?? '';
$password = $input['password'] ?? '';

if (empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(["error" => "Isi username & password"]);
    exit();
}

$stmt = $conn->prepare("SELECT id_admin, nama, username, password, role FROM admin WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    
    // Cek password (gunakan password_verify jika di DB sudah di-hash bcrypt)
    // Jika password di DB masih plain text, ganti jadi: if ($password === $user['password'])
    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "success" => true,
            "data" => ["id" => $user['id_admin'], "nama" => $user['nama'], "role" => $user['role']]
        ]);
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Password salah"]);
    }
} else {
    http_response_code(404);
    echo json_encode(["error" => "User tidak ditemukan"]);
}
?>
