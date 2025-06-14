<?php
session_start(); // oturumu başlattım ve devamında o sayfanın özelliklerini sağladım
require 'config.php';

// kullanıcı giriş yapmamışsa login sayfasına yönlendir
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = ''; // kullanıcıya gösterilecek mesaj için değişken

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = trim($_POST['location']);
    $feeding_date = $_POST['feeding_date'];
    $amount = trim($_POST['amount']);
    $description = trim($_POST['description']);
    // verileri veritabanına ekledim
    $stmt = $pdo->prepare("INSERT INTO feeding_points (user_id, location, feeding_date, amount, description) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $location, $feeding_date, $amount, $description]);

    $message = "Yemleme noktası başarıyla eklendi.";
    header("Location: index.php"); // eklendikten sonra listeye yönlendiriyoruz
    exit;
}
?>
<!-- html ile görünüşü hazır css kütüphanesini kullanrak sağladım-->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yemleme Noktası Ekle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Yeni Yemleme Noktası Ekle</h2>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Konum</label>
            <input type="text" name="location" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tarih</label>
            <input type="date" name="feeding_date" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Miktar</label>
            <input type="text" name="amount" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="description" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Kaydet</button>
        <a href="index.php" class="btn btn-secondary">Geri Dön</a>
    </form>
</div>

</body>
</html>
