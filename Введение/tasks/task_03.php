<?php
/**
 * ЗАДАНИЕ 3: Форма обратной связи с валидацией
 *
 * Ваша задача: обработать форму и проверить введённые данные
 */

$ошибки = [];
$успех = false;
$сохранённые = ['name' => '', 'email' => '', 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $сохранённые['name'] = trim($_POST['name'] ?? '');
    $сохранённые['email'] = trim($_POST['email'] ?? '');
    $сохранённые['message'] = trim($_POST['message'] ?? '');

    
    if (empty($сохранённые['name'])) {
        $ошибки[] = 'Имя не может быть пустым';
    } elseif (strlen($сохранённые['name']) < 2) {
        $ошибки[] = 'Имя должно содержать не менее 2 символов';
    }

    
    if (empty($сохранённые['email'])) {
        $ошибки[] = 'Email не может быть пустым';
    } elseif (!filter_var($сохранённые['email'], FILTER_VALIDATE_EMAIL)) {
        $ошибки[] = 'Введите корректный email адрес';
    }

    
    if (empty($сохранённые['message'])) {
        $ошибки[] = 'Сообщение не может быть пустым';
    } elseif (strlen($сохранённые['message']) < 10) {
        $ошибки[] = 'Сообщение должно содержать не менее 10 символов';
    }

    if (empty($ошибки)) {
        $успех = true;
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Задание 3: Форма обратной связи</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f0f2f5;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 30px;
        }

        h1 {
            font-size: 24px;
            font-weight: 500;
            color: #1a1a1a;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid #eee;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #333;
            margin-bottom: 6px;
        }

        input, textarea {
            width: 100%;
            padding: 10px 12px;
            font-size: 14px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border-color 0.2s;
            font-family: inherit;
        }

        input:focus, textarea:focus {
            outline: none;
            border-color: #0066cc;
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        button {
            background: #0066cc;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 500;
            border-radius: 6px;
            cursor: pointer;
            width: 100%;
            transition: background 0.2s;
        }

        button:hover {
            background: #0052a3;
        }

        .error-list {
            background: #fee;
            border: 1px solid #fcc;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
        }

        .error-list ul {
            margin: 0 0 0 20px;
            color: #c00;
            font-size: 14px;
        }

        .error-list li {
            margin: 4px 0;
        }

        .success-message {
            background: #e8f5e9;
            border: 1px solid #c8e6c9;
            border-radius: 6px;
            padding: 12px 16px;
            margin-bottom: 20px;
            color: #2e7d32;
            font-size: 14px;
        }

        .nav-links {
            margin-top: 24px;
            padding-top: 16px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 14px;
        }

        .nav-links a {
            color: #0066cc;
            text-decoration: none;
            margin: 0 10px;
        }

        .nav-links a:hover {
            text-decoration: underline;
        }

        .hint {
            font-size: 12px;
            color: #999;
            margin-top: 4px;
        }

        .required::after {
            content: "*";
            color: #c00;
            margin-left: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1> Форма обратной связи</h1>
        <div class="subtitle">Задание 3: проверка введённых данных</div>

        <?php if (!empty($ошибки)): ?>
            <div class="error-list">
                <strong>Исправьте ошибки:</strong>
                <ul>
                    <?php foreach ($ошибки as $err): ?>
                        <li><?= htmlspecialchars($err) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($успех): ?>
            <div class="success-message">
                ✓ Данные приняты. Спасибо за обращение, <?= htmlspecialchars($сохранённые['name']) ?>!
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label class="required">Имя</label>
                <input type="text" 
                       name="name" 
                       value="<?= htmlspecialchars($сохранённые['name']) ?>"
                       placeholder="Введите ваше имя">
                <div class="hint">минимум 2 символа</div>
            </div>

            <div class="form-group">
                <label class="required">Email</label>
                <input type="email" 
                       name="email" 
                       value="<?= htmlspecialchars($сохранённые['email']) ?>"
                       placeholder="name@example.com">
                <div class="hint">например: ivan@mail.ru</div>
            </div>

            <div class="form-group">
                <label class="required">Сообщение</label>
                <textarea name="message" 
                          placeholder="Введите ваше сообщение..."><?= htmlspecialchars($сохранённые['message']) ?></textarea>
                <div class="hint">минимум 10 символов</div>
            </div>

            <button type="submit">Отправить</button>
        </form>

        <div class="nav-links">
            <a href="task_02.php">← Назад</a>
            <a href="index.php">К списку заданий</a>
            <a href="task_04.php">Далее →</a>
        </div>
    </div>
</body>
</html>
