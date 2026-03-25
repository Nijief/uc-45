<?php
    require_once '../includes/config.php';

    header('Content-Type: application/json');

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        
        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $message = trim($data['message'] ?? '');
        
        $errors = [];
        
        if (empty($name)) $errors[] = 'Имя обязательно';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Некорректный email';
        if (empty($message)) $errors[] = 'Сообщение обязательно';
        
        if (empty($errors)) {
            $to = ADMIN_EMAIL;
            $subject = "Сообщение с сайта Учебного центра от $name";
            $body = "Имя: $name\nEmail: $email\n\nСообщение:\n$message";
            $headers = "From: no-reply@uc-45.ru\r\n";
            $headers .= "Reply-To: $email\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            if (mail($to, $subject, $body, $headers)) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Ошибка отправки письма']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => implode(', ', $errors)]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Неверный метод запроса']);
    }
?>