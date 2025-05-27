<?php
session_start();
require 'Koneksi.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Validasi input
if (empty($username) || empty($password)) {
    header("Location: ../login.php?error=empty");
    exit;
}

// Cek data di database
$stmt = $conn->prepare("SELECT * FROM users WHERE nama = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nama'];
        $_SESSION['is_logged_in'] = true;

        // Redirect ke dashboard
        header("Location: ../dashboard.php");
        exit;
    } else {
        // Password salah
        header("Location: ../login.php?error=password");
        exit;
    }
} else {
    // Username tidak ditemukan
    header("Location: ../login.php?error=username");
    exit;
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>