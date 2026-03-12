<?php
/**
 * ЗАДАНИЕ 5: Форма авторизации с капчей - УПРОЩЕННАЯ ВЕРСИЯ
 */

session_start();

$ошибка = '';
$показать_форму = true;


if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: task_05.php');
    exit;
}


if (!empty($_SESSION['user'])) {
    $показать_форму = false;
}


if (!isset($_SESSION['captcha_code']) || isset($_GET['new_captcha'])) {
    $_SESSION['captcha_code'] = rand(10000, 99999); 
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && $показать_форму) {
    $логин = $_POST['login'] ?? '';
    $пароль = $_POST['password'] ?? '';
    $ввод_капчи = $_POST['captcha_input'] ?? '';

    
    if ($ввод_капчи == $_SESSION['captcha_code']) {
        
        unset($_SESSION['captcha_code']);

        
        if ($логин == 'admin' && $пароль == '12345') {
            $_SESSION['user'] = $логин;

            
            $_SESSION['captcha_code'] = rand(10000, 99999);

            header('Location: task_05.php');
            exit;
        } else {
            $ошибка = 'Неверный логин или пароль';
            
            $_SESSION['captcha_code'] = rand(10000, 99999);
        }
    } else {
        $ошибка = 'Неверный код с картинки';
        
        $_SESSION['captcha_code'] = rand(10000, 99999);
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 5: Авторизация с капчей</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 500px; margin: 50px auto; padding: 20px; background: #f5f5f5; }
        h1 { color: #333; }
        .error { color: #c00; margin: 10px 0; padding: 10px; background: #fee; border-radius: 4px; }
        .form-block { background: #fff; padding: 20px; border-radius: 8px; margin: 15px 0; }
        label { display: block; margin-top: 12px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; margin-top: 4px; border: 1px solid #ddd; border-radius: 4px; }
        .captcha-code { font-size: 28px; font-weight: bold; letter-spacing: 5px; padding: 10px; background: #eee; margin: 10px 0; text-align: center; border-radius: 4px; }
        button { margin-top: 15px; padding: 10px 20px; background: #4CAF50; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        .btn-logout { background: #c00; color: #fff; padding: 8px 16px; text-decoration: none; border-radius: 4px; display: inline-block; margin-top: 10px; }
        .success { background: #d4edda; color: #155724; padding: 20px; border-radius: 4px; text-align: center; }
    </style>
</head>
<body>
    <h1>Задание 5: Авторизация с капчей</h1>

    <?php if ($показать_форму): ?>
        <p>Войдите: логин <strong>admin</strong>, пароль <strong>12345</strong></p>

        <?php if ($ошибка): ?>
            <p class="error">✕ <?= htmlspecialchars($ошибка) ?></p>
        <?php endif; ?>

        <div class="form-block">
            <p>Код капчи: <span class="captcha-code"><?= $_SESSION['captcha_code'] ?></span></p>
            <p><a href="task_05.php?new_captcha=1"> Обновить капчу</a></p>

            <form method="POST">
                <label>Логин:</label>
                <input type="text" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" required>

                <label>Пароль:</label>
                <input type="password" name="password" required>

                <label>Код капчи:</label>
                <input type="text" name="captcha_input" maxlength="5" required>

                <button type="submit">Войти</button>
            </form>
        </div>
    <?php else: ?>
        <div class="success">
            <h2>✅ Личный кабинет</h2>
            <p>Вы вошли как <strong><?= htmlspecialchars($_SESSION['user']) ?></strong></p>
            <a href="task_05.php?logout=1" class="btn-logout">Выйти</a>
        </div>
    <?php endif; ?>

    <p style="margin-top: 20px;">
        <a href="task_04.php">← Предыдущее задание</a> | 
        <a href="task_06.php">Следующее задание →</a>
    </p>
</body>
</html>
