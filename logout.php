<?php
session_start();
session_destroy(); // tüm oturum verilerini temizleyerek kullanıcıyı çıkış yaptırdım
header("Location: login.php"); // çıkış yaptırıp giriş ekranına yolladım
exit;
?>
