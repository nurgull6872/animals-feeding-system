<?php
session_start();
require 'config.php';

// giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    // silme işlemi sadece ilgili kullanıcıya ait veriler için geçerli olmasını ayarladım
    $stmt = $pdo->prepare("DELETE FROM feeding_points WHERE id = ? AND user_id = ?");
    $stmt->execute([$id, $_SESSION['user_id']]);
}

// işlem tamamlandıktan sonra anasayfaya yönlendirdim
header("Location: index.php");
exit;
?>
