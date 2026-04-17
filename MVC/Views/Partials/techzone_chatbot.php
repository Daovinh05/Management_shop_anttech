<?php
include_once __DIR__ . '/../../../Public/Classes/UrlHelper.php';
$techzoneChatRole = (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') ? 'admin' : 'customer';
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap');

    :root {
        --tz-chat-primary: #0c4da2;
        --tz-chat-secondary: #1f9fff;
        --tz-chat-accent: #ff8a00;
        --tz-chat-bg: #f3f8ff;
        --tz-chat-surface: #ffffff;
        --tz-chat-user: #e8f2ff;
        --tz-chat-border: #d3e6ff;
        --tz-chat-text: #11345f;
        --tz-chat-muted: #5a6d87;
        --tz-chat-shadow: 0 24px 46px rgba(12, 77, 162, 0.22);
    }

    .tz-chatbot {
        position: fixed;
        right: 20px;
        bottom: 20px;
        z-index: 2500;
        font-family: 'Be Vietnam Pro', 'Segoe UI', sans-serif;
    }

    .tz-chatbot__toggle {
        width: 62px;
        height: 62px;
        border-radius: 50%;
        border: none;
        cursor: pointer;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 26px;
        background: linear-gradient(145deg, var(--tz-chat-secondary), var(--tz-chat-primary));
        box-shadow: 0 14px 34px rgba(10, 83, 185, 0.35);
        position: relative;
        transition: transform 0.22s ease, box-shadow 0.22s ease;
    }

    .tz-chatbot__toggle:hover {
        transform: translateY(-2px) scale(1.04);
        box-shadow: 0 18px 36px rgba(10, 83, 185, 0.42);
    }

    .tz-chatbot__pulse {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: rgba(31, 159, 255, 0.45);
        animation: tz-chat-pulse 2s infinite;
        z-index: -1;
    }

    @keyframes tz-chat-pulse {
        0% {
            transform: scale(1);
            opacity: 0.65;
        }
        100% {
            transform: scale(1.75);
            opacity: 0;
        }
    }

    .tz-chatbot__panel {
        width: min(350px, calc(100vw - 24px));
        height: min(500px, calc(100vh - 90px));
        background: var(--tz-chat-surface);
        border-radius: 18px;
        overflow: hidden;
        box-shadow: var(--tz-chat-shadow);
        border: 1px solid var(--tz-chat-border);
        display: none;
        transform-origin: bottom right;
    }

    .tz-chatbot.is-open .tz-chatbot__panel {
        display: flex;
        flex-direction: column;
        animation: tz-chat-slide-up 0.24s ease;
    }

    .tz-chatbot.is-open .tz-chatbot__toggle {
        display: none;
    }

    @keyframes tz-chat-slide-up {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .tz-chatbot__header {
        color: #fff;
        padding: 14px 16px;
        background:
            radial-gradient(circle at 95% 8%, rgba(255, 255, 255, 0.28) 0 18px, transparent 19px),
            linear-gradient(145deg, var(--tz-chat-primary), var(--tz-chat-secondary));
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .tz-chatbot__title {
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.1px;
    }

    .tz-chatbot__badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 8px;
        border-radius: 30px;
        background: rgba(255, 255, 255, 0.2);
    }

    .tz-chatbot__close {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.15);
        color: #fff;
        font-size: 15px;
        cursor: pointer;
    }

    .tz-chatbot__body {
        flex: 1;
        overflow-y: auto;
        padding: 14px;
        background:
            linear-gradient(180deg, #f8fbff, #f1f8ff),
            repeating-linear-gradient(45deg, rgba(15, 109, 220, 0.03) 0, rgba(15, 109, 220, 0.03) 10px, transparent 10px, transparent 20px);
    }

    .tz-msg {
        max-width: 90%;
        border-radius: 14px;
        margin-bottom: 10px;
        line-height: 1.42;
        font-size: 13px;
        white-space: pre-wrap;
        word-break: break-word;
        animation: tz-msg-in 0.2s ease;
    }

    @keyframes tz-msg-in {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .tz-msg--bot {
        background: #fff;
        border: 1px solid #dcecff;
        color: var(--tz-chat-text);
        padding: 10px 12px;
        box-shadow: 0 6px 16px rgba(13, 94, 197, 0.08);
    }

    .tz-msg--user {
        margin-left: auto;
        background: var(--tz-chat-user);
        border: 1px solid #b9dbff;
        color: #0e3362;
        padding: 9px 11px;
    }

    .tz-chatbot__composer {
        border-top: 1px solid var(--tz-chat-border);
        background: var(--tz-chat-surface);
        padding: 10px;
    }

    .tz-chatbot__form {
        display: flex;
        gap: 8px;
    }

    .tz-chatbot__input {
        flex: 1;
        border: 1px solid #bedcff;
        border-radius: 12px;
        padding: 9px 10px;
        font-size: 13px;
        outline: none;
        color: #143963;
    }

    .tz-chatbot__input:focus {
        border-color: var(--tz-chat-secondary);
        box-shadow: 0 0 0 3px rgba(31, 159, 255, 0.16);
    }

    .tz-chatbot__send {
        border: none;
        border-radius: 12px;
        min-width: 44px;
        padding: 0 12px;
        font-size: 17px;
        cursor: pointer;
        color: #fff;
        background: linear-gradient(145deg, var(--tz-chat-accent), #ff6a00);
        box-shadow: 0 8px 20px rgba(255, 138, 0, 0.28);
    }

    .tz-chatbot__typing {
        color: var(--tz-chat-muted);
        font-size: 12px;
        margin: 2px 2px 0;
        min-height: 16px;
    }

    @media (max-width: 768px) {
        .tz-chatbot {
            right: 12px;
            bottom: 12px;
        }

        .tz-chatbot__panel {
            width: calc(100vw - 24px);
            height: min(72vh, 560px);
        }
    }
</style>

<div class="tz-chatbot" id="tzChatbot" data-role="<?php echo htmlspecialchars($techzoneChatRole); ?>">
    <button class="tz-chatbot__toggle" type="button" aria-label="Mở chat TechZone" id="tzChatToggle">
        <span class="tz-chatbot__pulse"></span>
        💬
    </button>

    <section class="tz-chatbot__panel" aria-label="Hỗ trợ trực tuyến TechZone">
        <header class="tz-chatbot__header">
            <div>
                <div class="tz-chatbot__title">Hỗ trợ trực tuyến TechZone</div>
                <div class="tz-chatbot__badge" id="tzRoleBadge">Khách hàng</div>
            </div>
            <button class="tz-chatbot__close" type="button" aria-label="Đóng chat" id="tzChatClose">✕</button>
        </header>

        <div class="tz-chatbot__body" id="tzChatBody"></div>

        <div class="tz-chatbot__composer">
            <form class="tz-chatbot__form" id="tzChatForm">
                <input
                    class="tz-chatbot__input"
                    id="tzChatInput"
                    type="text"
                    placeholder="Nhập câu hỏi... Ví dụ: iPhone 15 còn hàng không?"
                    autocomplete="off"
                >
                <button class="tz-chatbot__send" type="submit" aria-label="Gửi tin nhắn">➤</button>
            </form>
            <div class="tz-chatbot__typing" id="tzTyping"></div>
        </div>
    </section>
</div>

<script>
    (function() {
        var wrapper = document.getElementById('tzChatbot');
        if (!wrapper) {
            return;
        }

        var toggle = document.getElementById('tzChatToggle');
        var closeBtn = document.getElementById('tzChatClose');
        var form = document.getElementById('tzChatForm');
        var input = document.getElementById('tzChatInput');
        var body = document.getElementById('tzChatBody');
        var typing = document.getElementById('tzTyping');
        var roleBadge = document.getElementById('tzRoleBadge');
        var role = wrapper.getAttribute('data-role') || 'customer';

        roleBadge.textContent = role === 'admin' ? 'Quản trị viên' : 'Khách hàng';

        function appendMessage(type, text) {
            var msg = document.createElement('div');
            msg.className = 'tz-msg ' + (type === 'user' ? 'tz-msg--user' : 'tz-msg--bot');
            msg.textContent = text;
            body.appendChild(msg);
            body.scrollTop = body.scrollHeight;
        }

        function openChat() {
            wrapper.classList.add('is-open');
            if (body.children.length === 0) {
                appendMessage('bot', 'Dạ, TechZone xin chào Quý khách. Em có thể hỗ trợ tư vấn sản phẩm, đơn hàng và khuyến mãi ạ.');
            }
            window.setTimeout(function() {
                input.focus();
            }, 80);
        }

        function closeChat() {
            wrapper.classList.remove('is-open');
        }

        toggle.addEventListener('click', openChat);
        closeBtn.addEventListener('click', closeChat);

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var text = (input.value || '').trim();
            if (!text) {
                return;
            }

            appendMessage('user', text);
            input.value = '';
            typing.textContent = 'TechZone đang phản hồi...';

            fetch('<?php echo UrlHelper::url('Api/Techbot/ask'); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    message: text
                })
            })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    var reply = (json && json.reply) ? String(json.reply) : 'Dạ, TechZone tạm thời chưa thể phản hồi. Quý khách vui lòng thử lại sau giúp em ạ.';
                    appendMessage('bot', reply);
                })
                .catch(function() {
                    appendMessage('bot', 'Dạ, hiện tại hệ thống đang bận. Quý khách vui lòng thử lại sau ít phút giúp em ạ.');
                })
                .finally(function() {
                    typing.textContent = '';
                });
        });
    })();
</script>
