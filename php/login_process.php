<?php
session_start();
require 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

if (empty($username) || empty($password)) {
    header("Location: ../login.php?error=empty");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM users WHERE nama = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['nama'];
        $_SESSION['is_logged_in'] = true;
        header("Location: ../dashboard.php");
        exit;
    } else {
        header("Location: ../index.php?error=password");
        exit;
    }
} else {
    header("Location: ../index.php?error=username");
    exit;
}
$conn->close();

?>
