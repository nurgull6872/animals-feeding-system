<?php
$host = 'localhost';               // hosting IP'si filezillanın kendi içinden ulaşması gerektiğinden localhost
$db   = 'dbstorage22360859022';         // canlı veritabanı adım
$user = '';             // canlı veritabanı kullanıcı adım
$pass = '';                 // canlı veritabanı şifrem

try {
    $pdo = new PDO("mysql:host=$host;port=3306;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantı hatası: " . $e->getMessage());
}
?>
