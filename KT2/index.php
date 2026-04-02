<?php
session_start();

$result = '';
$messageClass = '';

$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

function generateCaptchaCode($chars) {
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

if (isset($_GET['ajax_new_captcha'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    exit;
}

if (isset($_GET['new_captcha'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode($chars);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_input'])) {
    $userInput = trim($_POST['captcha_input']);
    $expectedCode = $_SESSION['captcha_code'] ?? '';

    if ($userInput !== '' && $expectedCode !== '' && $userInput === $expectedCode) {
        $result = 'Правильно';
        $messageClass = 'success';
        $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    } else {
        $result = 'Неправильно';
        $messageClass = 'error';
        $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #d4d4d4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .captcha-box {
            background: #f0f0f0;
            border: 1px solid #aaa;
            width: 380px;
            padding: 20px;
            box-shadow: 3px 3px 8px rgba(0,0,0,0.2);
        }
        .captcha-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #bbb;
        }
        .captcha-image {
            text-align: center;
            margin: 15px 0;
            background: #fff;
            padding: 10px;
            border: 1px solid #ccc;
        }
        .captcha-image img {
            max-width: 100%;
            height: auto;
            vertical-align: middle;
        }
        .input-row {
            margin: 15px 0;
        }
        .input-row label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }
        .input-row input {
            width: 100%;
            padding: 8px;
            border: 1px solid #aaa;
            font-size: 14px;
            font-family: monospace;
            text-transform: uppercase;
        }
        .buttons {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }
        button {
            padding: 8px 20px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            background: #e0e0e0;
            border: 1px solid #888;
        }
        button:hover {
            background: #ccc;
        }
        .message-area {
            margin-top: 15px;
            padding: 8px;
            text-align: center;
            border: 1px solid #bbb;
            background: #fff;
        }
        .success {
            color: green;
            font-weight: bold;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="captcha-box">
    <div class="captcha-title">Регистрация</div>

    <div class="captcha-image">
        <img src="captcha.php?<?= time() ?>" alt="CAPTCHA" id="captchaImg">
    </div>

    <form method="POST">
        <div class="input-row">
            <label>Введите строку</label>
            <input type="text" name="captcha_input" id="captchaInput" maxlength="5" autocomplete="off" required>
        </div>

        <div class="buttons">
            <button type="button" id="refreshBtn">Обновить</button>
            <button type="submit">OK</button>
        </div>
    </form>

    <?php if ($result): ?>
        <div class="message-area">
            <span class="<?= $messageClass ?>"><?= htmlspecialchars($result) ?></span>
        </div>
    <?php else: ?>
        <div class="message-area">
            &nbsp;
        </div>
    <?php endif; ?>
</div>

<script>
    const captchaImg = document.getElementById('captchaImg');
    const refreshBtn = document.getElementById('refreshBtn');
    const captchaInput = document.getElementById('captchaInput');

    function refreshCaptcha() {
        fetch('index.php?ajax_new_captcha=1')
            .then(() => {
                captchaImg.src = 'captcha.php?' + Date.now();
                captchaInput.value = '';
                captchaInput.focus();
            })
            .catch(() => {
                captchaImg.src = 'captcha.php?' + Date.now();
                captchaInput.value = '';
                captchaInput.focus();
            });
    }

    refreshBtn.addEventListener('click', refreshCaptcha);
    captchaImg.addEventListener('click', refreshCaptcha);
    captchaInput.focus();

    captchaInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
</body>
</html>
