<?php
    require_once '../includes/config.php';
    require_once '../includes/functions.php';
    include '../includes/header.php';
?>

<div class="page-header">
    <div class="container">
        <h1>Контакты</h1>
        <p>Свяжитесь с нами удобным способом</p>
    </div>
</div>

<div class="container">
    <div class="contact-grid">
        <div class="info-card">
            <h3>Контактная информация</h3>
            <div class="contact-detail-item"><strong>Адрес:</strong><span>640000, г. Курган, Улица Ястржембского, 41а (территория АО «НПО Курганприбор»)</span></div>
            <div class="contact-detail-item"><strong>Телефон:</strong><span>+7 (3522) 45-67-89 доб. 1234</span></div>
            <div class="contact-detail-item"><strong>Email:</strong><span>edu@kurganpribor.ru</span></div>
            <div class="contact-detail-item"><strong>Часы:</strong><span>Пн–Пт 08:30 – 17:00</span></div>
            <div class="map-placeholder">
                <img src="<?= SITE_URL ?>/assets/images/map.png" alt="Схема проезда" style="max-width: 100%; border-radius: 12px;">
                <strong>Схема проезда:</strong> автобусы №74, 28, остановка «Заводская». Учебный корпус, вход со стороны ул. Ястржембского.
            </div>
        </div>
        <div class="form-card">
            <h3>Форма обратной связи</h3>
            <form id="feedbackForm">
                <div class="form-group">
                    <label>Имя *</label>
                    <input type="text" id="nameInput" placeholder="Иванов Иван" required>
                </div>
                <div class="form-group">
                    <label>Email *</label>
                    <input type="email" id="emailInput" placeholder="ivanov@example.com" required>
                </div>
                <div class="form-group">
                    <label>Сообщение *</label>
                    <textarea rows="4" id="msgInput" placeholder="Ваш вопрос или заявка..."></textarea>
                </div>
                <button type="submit" class="btn">Отправить сообщение</button>
                <div id="formMsgResult" style="margin-top: 1rem; font-size:0.9rem;"></div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const feedbackForm = document.getElementById('feedbackForm');
    
    if (feedbackForm) {
        feedbackForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const nameInput = document.getElementById('nameInput');
            const emailInput = document.getElementById('emailInput');
            const msgInput = document.getElementById('msgInput');
            const resultDiv = document.getElementById('formMsgResult');
            const submitBtn = this.querySelector('button[type="submit"]');
            
            // Проверяем, что все элементы существуют
            if (!nameInput || !emailInput || !msgInput || !resultDiv || !submitBtn) {
                console.error('Не найдены элементы формы');
                if (resultDiv) resultDiv.innerHTML = '<span style="color:#dc3545;">Техническая ошибка. Попробуйте позже.</span>';
                return;
            }
            
            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const message = msgInput.value.trim();
            
            // Валидация
            if (!name) {
                resultDiv.innerHTML = '<span style="color:#dc3545;">Пожалуйста, укажите ваше имя.</span>';
                return;
            }
            if (!email) {
                resultDiv.innerHTML = '<span style="color:#dc3545;">Пожалуйста, укажите email для обратной связи.</span>';
                return;
            }
            if (!email.match(/^[^\s@]+@([^\s@]+\.)+[^\s@]+$/)) {
                resultDiv.innerHTML = '<span style="color:#dc3545;">Пожалуйста, введите корректный email адрес.</span>';
                return;
            }
            if (!message) {
                resultDiv.innerHTML = '<span style="color:#dc3545;">Пожалуйста, введите текст сообщения.</span>';
                return;
            }
            
            const originalText = submitBtn.textContent;
            submitBtn.textContent = 'Отправка...';
            submitBtn.disabled = true;
            
            try {
                const response = await fetch('<?= SITE_URL ?>/admin/send_message.php', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ name, email, message })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    resultDiv.innerHTML = '<span style="color:#28a745;">✓ Сообщение успешно отправлено! Мы свяжемся с вами в ближайшее время.</span>';
                    nameInput.value = '';
                    emailInput.value = '';
                    msgInput.value = '';
                    // Очищаем сообщение через 5 секунд
                    setTimeout(() => {
                        if (resultDiv) resultDiv.innerHTML = '';
                    }, 5000);
                } else {
                    resultDiv.innerHTML = '<span style="color:#dc3545;">❌ Ошибка: ' + (result.error || 'Не удалось отправить сообщение') + '</span>';
                }
            } catch (error) {
                console.error('Ошибка:', error);
                resultDiv.innerHTML = '<span style="color:#dc3545;">❌ Ошибка сети. Проверьте подключение и попробуйте снова.</span>';
            } finally {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            }
        });
    }
});
</script>

<?php include '../includes/footer.php'; ?>