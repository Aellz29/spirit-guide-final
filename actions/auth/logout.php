<?php
session_start();
session_unset();
session_destroy();

// PERBAIKAN: Redirect ke halaman Index (Home), bukan Login
header("Location: ../../index.php");
exit;
?>