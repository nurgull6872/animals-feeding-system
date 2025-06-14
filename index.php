<?php
session_start();
require 'config.php';

// giriş kontrolü
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// kullanıcının yemleme noktalarını oluşturduğum ve içine veri yazdığım tablodan çektim
$stmt = $pdo->prepare("SELECT * FROM feeding_points WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$feedingPoints = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- hazır css kütüphanesi ile html ile görünüşü ayarladım-->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kontrol Paneli</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Hoş geldin, <?php echo htmlspecialchars($_SESSION['username']); ?> 👋</h2>

    <a href="add_feeding_point.php" class="btn btn-success mb-3">Yeni Yemleme Noktası Ekle</a>
    <a href="logout.php" class="btn btn-secondary mb-3 float-end">Çıkış Yap</a>

    <h4>Yemleme Noktaları</h4>
    <?php if (count($feedingPoints) > 0): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Konum</th>
                    <th>Tarih</th>
                    <th>Miktar</th>
                    <th>Açıklama</th>
                    <th>İşlemler</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($feedingPoints as $point): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($point['location']); ?></td>
                        <td><?php echo htmlspecialchars($point['feeding_date']); ?></td>
                        <td><?php echo htmlspecialchars($point['amount']); ?></td>
                        <td><?php echo htmlspecialchars($point['description']); ?></td>
                        <td>
                            <a href="edit_feeding_point.php?id=<?php echo $point['id']; ?>" class="btn btn-sm btn-primary">Düzenle</a>
                            <a href="delete_feeding_point.php?id=<?php echo $point['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Silmek istediğinize emin misiniz?');">Sil</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">Henüz bir yemleme noktası eklemediniz.</div>
    <?php endif; ?>
</div>

</body>
</html>
