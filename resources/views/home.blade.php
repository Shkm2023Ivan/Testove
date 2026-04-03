<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Новая заявка</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --bg-main: #ffffff;
            --text-main: #111827;
            --text-muted: #6b7280;
            --border-color: #e5e7eb;
            --focus-color: #111827;
            --error-color: #dc2626;
            --success-color: #059669;
            --ease: cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: -apple-system, BlinkMacSystemFont, "Inter", sans-serif; -webkit-font-smoothing: antialiased; }

        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: var(--bg-main);
            color: var(--text-main);
        }

        .container {
            width: 100%;
            max-width: 420px;
            padding: 32px;
            opacity: 0;
            transform: translateY(10px);
            animation: fadeIn 0.5s var(--ease) forwards;
        }

        @keyframes fadeIn { to { opacity: 1; transform: translateY(0); } }

        h1 { font-size: 26px; font-weight: 800; margin-bottom: 8px; letter-spacing: -0.03em; }
        .subtitle { color: var(--text-muted); font-size: 14px; margin-bottom: 32px; line-height: 1.5; }

        .form-group { margin-bottom: 20px; position: relative; }
        label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; margin-left: 2px; }

        input, textarea {
            width: 100%;
            padding: 12px 14px;
            background-color: transparent;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
            color: var(--text-main);
            outline: none;
            transition: all 0.2s var(--ease);
        }

        input::placeholder, textarea::placeholder { color: #9ca3af; opacity: 0.8; }

        input:focus, textarea:focus {
            border-color: var(--focus-color);
            box-shadow: 0 0 0 3px rgba(17, 24, 39, 0.05);
        }

        textarea { height: 110px; resize: none; line-height: 1.5; }

        .error-text { color: var(--error-color); font-size: 11px; margin-top: 5px; min-height: 14px; }
        input.is-invalid, textarea.is-invalid { border-color: var(--error-color); }

        button {
            width: 100%;
            padding: 12px;
            background-color: var(--focus-color);
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s var(--ease);
        }

        button:hover { opacity: 0.9; transform: translateY(-1px); }
        button:disabled { background-color: #d1d5db; cursor: not-allowed; transform: none; }

        #response { margin-top: 24px; font-size: 13px; text-align: center; min-height: 20px; font-weight: 500; }
        .success-msg { color: var(--success-color); }
        .error-msg { color: var(--error-color); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Новая заявка</h1>
        <p class="subtitle">Опишите вашу проблему, и мы свяжемся с вами в ближайшее время.</p>

        <form id="ticketForm" action="{{ route('tickets.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Ваше имя</label>
                <input type="text" id="name" name="name" placeholder="Иван Иванов" required>
                <div class="error-text" id="error-name"></div>
            </div>

            <div class="form-group">
                <label for="email">Электронная почта</label>
                <input type="email" id="email" name="email" placeholder="example@mail.com" required>
                <div class="error-text" id="error-email"></div>
            </div>

            <div class="form-group">
                <label for="phone">Номер телефона</label>
                <input type="text" id="phone" name="phone" placeholder="+38 (0XX) XXX-XX-XX" required>
                <div class="error-text" id="error-phone"></div>
            </div>

            <div class="form-group">
                <label for="subject">Тема</label>
                <input type="text" id="subject" name="subject" placeholder="Коротко о проблеме" required>
                <div class="error-text" id="error-subject"></div>
            </div>

            <div class="form-group">
                <label for="message">Сообщение</label>
                <textarea id="message" name="message" placeholder="Опишите детали вашего вопроса..." required></textarea>
                <div class="error-text" id="error-message"></div>
            </div>

            <button type="submit" id="submitBtn">Отправить</button>
        </form>

        <div id="response"></div>
    </div>

    <script>
        $('#ticketForm').on('submit', function(e) {
            e.preventDefault();
            const btn = $('#submitBtn');
            const responseDiv = $('#response');
            
            $('.error-text').text('');
            $('input, textarea').removeClass('is-invalid');
            btn.text('Отправка...').prop('disabled', true);
            responseDiv.html('');

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    responseDiv.html('<span class="success-msg">' + response.message + '</span>');
                    $('#ticketForm')[0].reset();
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        const errors = xhr.responseJSON.errors;
                        Object.keys(errors).forEach(key => {
                            $(`#${key}`).addClass('is-invalid');
                            $(`#error-${key}`).text(errors[key][0]);
                        });
                    } else {
                        responseDiv.html('<span class="error-msg">Ошибка при отправке</span>');
                    }
                },
                complete: function() { btn.text('Отправить').prop('disabled', false); }
            });
        });
    </script>
</body>
</html>