<?php
require_once 'm6-2-common.php';

// 既にログインしている場合は、タイムラインページにリダイレクト
if (isset($_SESSION['user_id'])) {
    header("Location: m6-2-index.php");
    exit;
}

$error_message = "";

// 新規登録処理
if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    if (!empty($name) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        try {
            $stmt = $pdo->prepare("INSERT INTO users (name, password) VALUES (?, ?)");
            $stmt->execute([$name, $hashed_password]);
            $_SESSION['user_id'] = $pdo->lastInsertId();
            $_SESSION['user_name'] = $name;
            header("Location: m6-2-index.php"); // 登録後、タイムラインへ
            exit;
        } catch (PDOException $e) {
            $error_message = "ユーザー名が既に使用されています。";
        }
    } else {
        $error_message = "ユーザー名とパスワードを入力してください。";
    }
}

// ログイン処理
if (isset($_POST['login'])) {
    $name = $_POST['name'];
    $password = $_POST['password'];
    if (!empty($name) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = ?");
        $stmt->execute([$name]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            header("Location: m6-2-index.php"); // ログイン後、タイムラインへ
            exit;
        } else {
            $error_message = "ユーザー名またはパスワードが違います。";
        }
    } else {
        $error_message = "ユーザー名とパスワードを入力してください。";
    }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Nailog - ログイン</title>
    <style> /* 簡単なスタイル */
        body { font-family: sans-serif; }
        .container { max-width: 500px; margin: auto; padding: 20px; }
        .form-section { background: #f4f4f4; padding: 20px; margin-bottom: 20px; }
        input[type="text"], input[type="password"] { width: 90%; padding: 8px; margin-bottom: 10px; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nailogへようこそ！</h1>
        <p class="error"><?php echo h($error_message); ?></p>

        <div class="form-section">
            <h3>ログイン</h3>
            <form action="" method="post">
                <input type="text" name="name" placeholder="ユーザー名">
                <input type="password" name="password" placeholder="パスワード">
                <input type="submit" name="login" value="ログイン">
            </form>
            <h3>新規登録</h3>
            <form action="" method="post">
                <input type="text" name="name" placeholder="ユーザー名">
                <input type="password" name="password" placeholder="パスワード">
                <input type="submit" name="register" value="登録">
            </form>
        </div>
    </div>
</body>
</html>