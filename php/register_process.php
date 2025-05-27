<?php
session_start();
require_once 'koneksi.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

function redirectWithError($errors, $data) {
    $_SESSION['register_error'] = $errors;
    $_SESSION['data_form'] = $data;
    header('Location: ../register.php');
    exit();
}

$errors = [];
$data = [
    'nama' => $_POST['nama'] ?? '',
    'password' => $_POST['password'] ?? '',
    'konfirmasi_password' => $_POST['konfirmasi_password'] ?? ''
];

// Validasi
if (empty($data['nama'])) $errors['nama'] = 'Nama wajib diisi';
if (empty($data['password'])) $errors['password'] = 'Password wajib diisi';
if ($data['password'] !== $data['konfirmasi_password']) $errors['konfirmasi_password'] = 'Konfirmasi password tidak cocok';

if (!empty($errors)) {
    redirectWithError($errors, $data);
}

try {
    $conn->begin_transaction();

    // Cek apakah nama sudah digunakan
    $check = $conn->prepare("SELECT id FROM users WHERE nama = ?");
    $check->bind_param("s", $data['nama']);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        throw new Exception('Nama sudah terdaftar');
    }

    // Hash password
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);

    // Insert user baru
    $stmt = $conn->prepare("INSERT INTO users (nama, password) VALUES (?, ?)");
    if (!$stmt) {
        throw new Exception('Prepare failed: ' . $conn->error);
    }

    $stmt->bind_param("ss", $data['nama'], $hashedPassword);
    if (!$stmt->execute()) {
        throw new Exception('Execute failed: ' . $stmt->error);
    }

    $conn->commit();

    $_SESSION['success_message'] = 'Registrasi berhasil! Silakan login.';
    header('Location: ../index.php');
    exit();

} catch (Exception $e) {
    $conn->rollback();
    error_log("Register Error: " . $e->getMessage());

    $errors['database'] = 'Terjadi kesalahan: ' . $e->getMessage();
    redirectWithError($errors, $data);
}
?>