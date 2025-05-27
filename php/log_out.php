<?php
session_start();
session_unset(); // hapus semua variabel sesi
session_destroy(); // hancurkan sesi

header("Location: ../index.php");
exit();
?>