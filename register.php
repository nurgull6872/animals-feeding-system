<?php
require 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    // girilen şifre doğru mu diye kontrol eder
    if ($password !== $password_confirm) {
        $message = "Şifreler uyuşmuyor.";
    } else {
        // kullanıcı adı veya e mail daha önce kullanıldı mı diye
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        if ($stmt->fetch()) {
            $message = "Bu kullanıcı adı veya e-posta zaten kullanılıyor.";
        } else {
            // şifreyi hashler böylece şifreyi gizlenmiş görürüm
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // yeni kullanıcıyı insert into komutu yardımıyla veri tabanıma kaydettim
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt->execute([$username, $email, $passwordHash])) {
                $message = "Kayıt başarılı! Giriş yapabilirsiniz.";
            } else {
                $message = "Bir hata oluştu. Tekrar deneyin.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kayıt Ol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Kayıt Ol</h2>

    <?php if ($message): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">E-posta</label>
            <input type="email" class="form-control" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
            <label for="password_confirm" class="form-label">Şifre (Tekrar)</label>
            <input type="password" class="form-control" name="password_confirm" required>
        </div>
        <button type="submit" class="btn btn-primary">Kayıt Ol</button>
        <a href="login.php" class="btn btn-link">Zaten hesabınız var mı? Giriş Yapın</a>
    </form>
</div>

</body>
</html>
