<?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';

    if (isAdmin()) {
        header('Location: index.php');
        exit;
    }

    $error = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header('Location: index.php');
            exit;
        } else {
            $error = 'Неверный логин или пароль';
        }
    }
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход в админ-панель</title>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>
    <div class="container" style="min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        <div class="admin-form" style="max-width: 400px; width: 100%;">
            <h2 style="text-align: center;">Вход в админ-панель</h2>
            <?php if ($error): ?>
                <p style="color: red; text-align: center;"><?= sanitize($error) ?></p>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Логин</label>
                    <input type="text" name="username" id="username" required>
                </div>
                <div class="form-group">
                    <label for="password">Пароль</label>
                    <input type="password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn" style="width: 100%;">Войти</button>
            </form>
            <p style="text-align: center; margin-top: 1rem;">
                <a href="<?= SITE_URL ?>/index.php">← Вернуться на сайт</a>
            </p>
        </div>
    </div>
</body>
</html>