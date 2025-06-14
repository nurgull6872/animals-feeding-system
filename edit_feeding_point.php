<?php
session_start();
require 'config.php';

// giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// düzenlenecek yemleme noktasının id bilgisi URL'de yoksa işlemi iptal edip anasayfaya yönlendirmek için koşul bloğu
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id']; // kullanıcı idyi kontrol edemesin direkt diye ekstra güvenlik için 

// düzenlenecek mevcut veriyi veritabanından çekiyoruz sadece o kullanıcıya ait olanı
$stmt = $pdo->prepare("SELECT * FROM feeding_points WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$point = $stmt->fetch();


// eğer böyle bir veri yoksa anasayfaya yönlendirdim
if (!$point) {
    header("Location: index.php");
    exit;
}

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $location = trim($_POST['location']);
    $feeding_date = $_POST['feeding_date'];
    $amount = trim($_POST['amount']);
    $description = trim($_POST['description']);
    // veriyi güncellemeyi yine sadece o kullanıcıya ait veriyi düzenlesin diye ayarladım
    $stmt = $pdo->prepare("UPDATE feeding_points SET location = ?, feeding_date = ?, amount = ?, description = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$location, $feeding_date, $amount, $description, $id, $_SESSION['user_id']]);

    $message = "Yemleme noktası güncellendi.";
    header("Location: index.php"); // güncelleme tamamlandıktan sonra anasayfaya yönlendirdim
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Yemleme Noktası Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Yemleme Noktası Düzenle</h2>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label class="form-label">Konum</label>
            <input type="text" name="location" class="form-control" required value="<?php echo htmlspecialchars($point['location']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Tarih</label>
            <input type="date" name="feeding_date" class="form-control" required value="<?php echo htmlspecialchars($point['feeding_date']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Miktar</label>
            <input type="text" name="amount" class="form-control" value="<?php echo htmlspecialchars($point['amount']); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Açıklama</label>
            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($point['description']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Güncelle</button>
        <a href="index.php" class="btn btn-secondary">İptal</a>
    </form>
</div>

</body>
</html>
