<?php
session_start();
require 'config.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
     // formdan gelen kullanıcı adı ve şifre verilerini aldım
    $username = trim($_POST['username']);
    $password = $_POST['password'];
     // veritabanında kullanıcı adıyla eşleşen kullanıcıyı aradım 
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
     // kullanıcı bulunduysa ve şifre doğruysa giriş işlemini başlattım girişi sağladım
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header("Location: index.php"); 
        exit;
    } else {
        // kullanıcı bulunamadıysa veya şifre yanlışsa hata mesajı gösterilir
        $message = "Kullanıcı adı veya şifre hatalı.";
    }
}
?>
<!-- css kütüphanesi ile belirli bir şekilde sayfalrım aynı stilde olması için yine aynı link ile görünümünü butonları vs ayarladım-->
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Giriş Yap</title>
    <!-- bootstrap css kütüphanesi kullandım  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Giriş Yap</h2>
    <!-- hata mesajı varsa gösterdim -->
    <?php if ($message): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm">
        <div class="mb-3">
            <label for="username" class="form-label">Kullanıcı Adı</label>
            <input type="text" class="form-control" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Şifre</label>
            <input type="password" class="form-control" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Giriş Yap</button>
        <a href="register.php" class="btn btn-link">Hesabınız yok mu? Kayıt Olun</a>
    </form>
</div>

</body>
</html>
