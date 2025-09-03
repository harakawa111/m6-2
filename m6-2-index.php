<?php
require_once 'm6-2-common.php';

// --- 表示データ取得 ---
$posts = [];
// ログインしている場合のみ投稿データを取得
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->query("SELECT p.image_name, p.memo, p.created_at, u.name AS user_name FROM posts p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC");
    $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>Nailog - タイムライン</title>
    <style>
        body { font-family: sans-serif; }
        .container { max-width: 800px; margin: auto; padding: 20px; }
        .post { border: 1px solid #ddd; padding: 15px; margin-bottom: 20px; }
        .post img { max-width: 100%; height: auto; display: block;　margin: 0 auto;}
        .welcome-section { background: #f4f4f4; padding: 20px; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Nailog</h1>

        <?php if (isset($_SESSION['user_id'])): ?>
            <!-- ログイン中の表示 -->
            <div class="welcome-section">
                <p>ようこそ、<?php echo h($_SESSION['user_name']); ?>さん！</p>
                <a href="m6-2-post.php">新しいネイルを投稿する</a> | 
                <a href="m6-2-logout.php">ログアウト</a>
            </div>

            <h2>タイムライン</h2>
            <?php if (empty($posts)): ?>
                <p>まだ投稿はありません。</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <div class="post">
                        <p><strong><?php echo h($post['user_name']); ?></strong> <small><?php echo $post['created_at']; ?></small></p>
                        <img src="uploads/<?php echo h($post['image_name']); ?>" alt="ネイル画像">
                        <p><?php echo nl2br(h($post['memo'])); ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>

        <?php else: ?>
            <!-- ログアウト中の表示 -->
            <div class="welcome-section">
                <a href="m6-2-login.php">ログインまたは新規登録</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
