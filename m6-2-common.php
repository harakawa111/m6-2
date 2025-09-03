<?php
// --- 共通処理 ---
session_start();

// DB接続設定
$dsn = 'mysql:dbname=データベース名';
$user_db = 'ユーザ名';
$password_db = 'パスワード';

try {
    $pdo = new PDO($dsn, $user_db, $password_db);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB接続エラー: " . $e->getMessage());
}

// XSS対策のヘルパー関数
function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
