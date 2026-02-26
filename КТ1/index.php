<?php
// Весь PHP-код должен быть в самом начале, до любого HTML
session_start();

// Инициализация
if (!isset($_SESSION['goodbye_count'])) {
    $_SESSION['goodbye_count'] = 0;
}

if (!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = [['type' => 'bot', 'text' => 'ЧЕГО СКАЗАТЬ-ТО ХОТЕЛ, МИЛОК?!']];
}

function getRandomYear() {
    return rand(1930, 1950);
}

// Обработка сообщения
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);

    if (!empty($userMessage)) {
        $_SESSION['messages'][] = ['type' => 'user', 'text' => $userMessage];

        if (strtoupper($userMessage) === 'ПОКА!') {
            $_SESSION['goodbye_count']++;

            if ($_SESSION['goodbye_count'] >= 3) {
                $_SESSION['messages'][] = ['type' => 'bot', 'text' => 'ДО СВИДАНИЯ, МИЛЫЙ!'];
            } else {
                $year = getRandomYear();
                $_SESSION['messages'][] = ['type' => 'bot', 'text' => "НЕТ, НИ РАЗУ С {$year} ГОДА!"];
            }
        } 
        elseif (substr($userMessage, -1) === '!') {
            $_SESSION['goodbye_count'] = 0;
            $year = getRandomYear();
            $_SESSION['messages'][] = ['type' => 'bot', 'text' => "НЕТ, НИ РАЗУ С {$year} ГОДА!"];
        } 
        else {
            $_SESSION['goodbye_count'] = 0;
            $_SESSION['messages'][] = ['type' => 'bot', 'text' => 'АСЬ?! ГОВОРИ ГРОМЧЕ, ВНУЧЕК!'];
        }

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Проверяем, закончен ли диалог
$lastMessage = end($_SESSION['messages']);
$dialogEnded = ($lastMessage && $lastMessage['type'] === 'bot' && $lastMessage['text'] === 'ДО СВИДАНИЯ, МИЛЫЙ!');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Глухая бабушка</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #e0e0e0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: white;
            border: 1px solid #999;
            border-radius: 5px;
            padding: 15px;
        }

        h3 {
            margin: 0 0 15px 0;
            color: #333;
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }

        .chat {
            height: 350px;
            overflow-y: auto;
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 15px;
            background-color: #f9f9f9;
        }

        .message {
            margin-bottom: 10px;
            padding: 8px 12px;
            border-radius: 3px;
            max-width: 80%;
            word-wrap: break-word;
        }

        .bot-message {
            background-color: #e5e5e5;
            border: 1px solid #ccc;
            margin-right: auto;
        }

        .user-message {
            background-color: #007bff;
            color: white;
            border: 1px solid #0056b3;
            margin-left: auto;
        }

        .input-group {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        input[type="text"] {
            flex: 1;
            padding: 8px;
            border: 1px solid #999;
            border-radius: 3px;
            font-size: 14px;
        }

        button {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: 1px solid #0056b3;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
        }

        button:hover {
            background-color: #0056b3;
        }

        button:disabled {
            background-color: #999;
            border-color: #666;
            cursor: not-allowed;
        }

        .info {
            font-size: 13px;
            color: #666;
            text-align: center;
            padding: 5px;
            border-top: 1px solid #ccc;
        }

        .counter {
            font-size: 13px;
            color: #333;
            text-align: center;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h3>Глухая бабушка</h3>

        <div class="chat" id="chat">
            <?php
            // Вывод сообщений
            foreach ($_SESSION['messages'] as $msg) {
                $class = ($msg['type'] === 'bot') ? 'bot-message' : 'user-message';
                echo "<div class='message {$class}'>" . htmlspecialchars($msg['text']) . "</div>";
            }
            ?>
        </div>

        <form method="POST" id="messageForm">
            <div class="input-group">
                <input type="text" name="message" id="messageInput" 
                       placeholder="Введите сообщение..." 
                       autocomplete="off" 
                       <?php echo $dialogEnded ? 'disabled' : ''; ?>
                       required>
                <button type="submit" <?php echo $dialogEnded ? 'disabled' : ''; ?>>Отправить</button>
            </div>
        </form>

        <div class="counter">
            Сказано "ПОКА!": <?php echo $_SESSION['goodbye_count']; ?>/3
        </div>
        <div class="info">
            Чтобы крикнуть - ставьте ! в конце<br>
            Чтобы уйти - скажите ПОКА! 3 раза
        </div>
    </div>

    <script>
        // Скролл вниз
        document.getElementById('chat').scrollTop = document.getElementById('chat').scrollHeight;

        // Очистка поля
        document.getElementById('messageForm').onsubmit = function() {
            setTimeout(() => {
                document.getElementById('messageInput').value = '';
            }, 10);
        };
    </script>
</body>
</html>
