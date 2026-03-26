<?php
    require_once '../includes/config.php';

    header('Content-Type: application/json');

    $input = json_decode(file_get_contents('php://input'), true);

    $name = trim($input['name'] ?? '');
    $email = trim($input['email'] ?? '');
    $message = trim($input['message'] ?? '');

    $errors = [];

    if (empty($name)) {
        $errors[] = 'Имя обязательно';
    }
    if (empty($email)) {
        $errors[] = 'Email обязателен';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email адрес';
    }
    if (empty($message)) {
        $errors[] = 'Сообщение обязательно';
    }

    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'error' => implode(', ', $errors)
        ]);
        exit;
    }

    $to = ADMIN_EMAIL;
    $subject = "=?UTF-8?B?" . base64_encode("Сообщение с сайта Учебного центра") . "?=";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/plain; charset=utf-8\r\n";
    $headers .= "From: =?UTF-8?B?" . base64_encode($name) . "?= <no-reply@" . $_SERVER['HTTP_HOST'] . ">\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    $body = "----------------------------------------\n";
    $body .= "Новое сообщение с сайта Учебного центра\n";
    $body .= "----------------------------------------\n\n";
    $body .= "Имя отправителя: " . $name . "\n";
    $body .= "Email для ответа: " . $email . "\n";
    $body .= "IP адрес: " . ($_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? 'неизвестен') . "\n";
    $body .= "Время отправки: " . date('d.m.Y H:i:s') . "\n\n";
    $body .= "Текст сообщения:\n";
    $body .= "----------------------------------------\n";
    $body .= $message . "\n";
    $body .= "----------------------------------------\n\n";
    $body .= "---\n";
    $body .= "Это письмо отправлено автоматически. Пожалуйста, не отвечайте на него.\n";
    $body .= "Для ответа используйте email отправителя: " . $email;

    $mailSent = mail($to, $subject, $body, $headers);

    if ($mailSent) {
        echo json_encode([
            'success' => true,
            'message' => 'Сообщение успешно отправлено'
        ]);
    } else {
        error_log("Ошибка отправки письма на $to от $email");
        
        echo json_encode([
            'success' => false,
            'error' => 'Не удалось отправить сообщение. Попробуйте позже.'
        ]);
    }
?>