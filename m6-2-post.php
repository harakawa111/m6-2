<?php
require_once 'm6-2-common.php';

// ログインしていなければ、ログインページにリダイレクト
if (!isset($_SESSION['user_id'])) {
    header("Location: m6-2-login.php");
    exit;
}

$error_message = "";

// 新規投稿処理
if (isset($_POST['new_post'])) {
    $memo = $_POST['memo'];
    // 画像アップロード処理
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploaded_file = $_FILES['image'];
        $file_name = uniqid() . "_" . $uploaded_file['name'];
        $upload_path = 'uploads/' . $file_name;

        if (move_uploaded_file($uploaded_file['tmp_name'], $upload_path)) {
            $stmt = $pdo->prepare("INSERT INTO posts (user_id, image_name, memo) VALUES (?, ?, ?)");
            $stmt->execute([$_SESSION['user_id'], $file_name, $memo]);
            header("Location: m6-2-index.php"); // 投稿後、タイムラインへ
            exit;
        } else {
            $error_message = "ファイルのアップロードに失敗しました。";
        }
    } else {
        $error_message = "画像を選択してください。";
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Nailog - 新規投稿</title>
     <style>
        body { font-family: sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .form-section { background: #f4f4f4; padding: 20px; margin-bottom: 20px; }
        textarea { width: 100%; padding: 8px; margin-bottom: 10px; height: 100px; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>新しいネイルを投稿</h1>
        <p><a href="m6-2-index.php">タイムラインに戻る</a></p>
        <p class="error"><?php echo h($error_message); ?></p>
        
        <div class="form-section">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="file" name="image" accept="image/*" required><br><br>
                <textarea name="memo" placeholder="使用アイテムやメモ..."></textarea><br>
                <input type="submit" name="new_post" value="投稿">
            </form>
        </div>
    </div>
</body>
</html>