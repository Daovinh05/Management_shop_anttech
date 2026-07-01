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

    .tz-msg--bot strong {
        color: #0b3d7a;
        font-weight: 700;
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

    .tz-chatbot__mic {
        border: 1px solid #bedcff;
        border-radius: 12px;
        min-width: 44px;
        padding: 0 10px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        color: #0e3c74;
        background: #eff6ff;
        transition: background 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
    }

    .tz-chatbot__mic:hover {
        background: #e2f0ff;
        border-color: #96c7ff;
        transform: translateY(-1px);
    }

    .tz-chatbot__mic.is-listening {
        color: #fff;
        border-color: #e14d4d;
        background: linear-gradient(145deg, #ff6b6b, #d73b3b);
        box-shadow: 0 8px 18px rgba(215, 59, 59, 0.24);
    }

    .tz-chatbot__mic:disabled {
        cursor: not-allowed;
        opacity: 0.55;
        transform: none;
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
                <button class="tz-chatbot__mic" id="tzChatMic" type="button" aria-label="Nhập bằng giọng nói" title="Nhập bằng giọng nói">Mic</button>
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
        var micBtn = document.getElementById('tzChatMic');
        var body = document.getElementById('tzChatBody');
        var typing = document.getElementById('tzTyping');
        var roleBadge = document.getElementById('tzRoleBadge');
        var role = wrapper.getAttribute('data-role') || 'customer';
        var historyLoaded = false;
        var isRequestPending = false;
        var askUrl = '<?php echo UrlHelper::url('Api/Techbot/ask'); ?>';
        var historyUrl = '<?php echo UrlHelper::url('Api/Techbot/history'); ?>';
        var SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
        var recognition = null;
        var isListening = false;

        roleBadge.textContent = role === 'admin' ? 'Quản trị viên' : 'Khách hàng';

        function escapeHtml(text) {
            return String(text)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;')
                .replace(/'/g, '&#39;');
        }

        function formatLineWithHighlight(rawLine) {
            var line = String(rawLine || '');
            var matchPipe = line.match(/^(\s*-\s*)([^|]+)(\|.*)$/);
            if (matchPipe) {
                return escapeHtml(matchPipe[1])
                    + '<strong>' + escapeHtml(matchPipe[2].trim()) + '</strong>'
                    + ' ' + escapeHtml(matchPipe[3].trimStart());
            }

            var matchLabel = line.match(/^(\s*-\s*)([^:]{2,60}:)\s*(.*)$/);
            if (matchLabel) {
                return escapeHtml(matchLabel[1])
                    + '<strong>' + escapeHtml(matchLabel[2].trim()) + '</strong>'
                    + (matchLabel[3] ? ' ' + escapeHtml(matchLabel[3]) : '');
            }

            return escapeHtml(line);
        }

        function formatBotMessageHtml(text) {
            var normalized = String(text || '').replace(/\r\n?/g, '\n');
            var lines = normalized.split('\n');
            var formattedLines = lines.map(function(line) {
                return formatLineWithHighlight(line);
            });

            var firstContentIndex = -1;
            for (var i = 0; i < lines.length; i++) {
                if (String(lines[i] || '').trim() !== '') {
                    firstContentIndex = i;
                    break;
                }
            }

            if (firstContentIndex >= 0) {
                formattedLines[firstContentIndex] = '<strong>' + formattedLines[firstContentIndex] + '</strong>';
            }

            return formattedLines.join('<br>');
        }

        function appendMessage(type, text) {
            var msg = document.createElement('div');
            msg.className = 'tz-msg ' + (type === 'user' ? 'tz-msg--user' : 'tz-msg--bot');
            if (type === 'bot') {
                msg.innerHTML = formatBotMessageHtml(text);
            } else {
                msg.textContent = text;
            }
            body.appendChild(msg);
            body.scrollTop = body.scrollHeight;
        }

        function clearMessages() {
            while (body.firstChild) {
                body.removeChild(body.firstChild);
            }
        }

        function setTypingStatus(message) {
            if (!isRequestPending) {
                typing.textContent = message || '';
            }
        }

        function setMicListeningState(listening) {
            isListening = !!listening;
            if (!micBtn) {
                return;
            }

            micBtn.classList.toggle('is-listening', isListening);
            micBtn.textContent = isListening ? 'Stop' : 'Mic';
            micBtn.setAttribute('aria-label', isListening ? 'Dừng ghi âm giọng nói' : 'Nhập bằng giọng nói');
            micBtn.setAttribute('title', isListening ? 'Dừng ghi âm giọng nói' : 'Nhập bằng giọng nói');
            setTypingStatus(isListening ? 'TechZone đang nghe giọng nói...' : '');
        }

        function setupSpeechRecognition() {
            if (!micBtn) {
                return;
            }

            if (!SpeechRecognition) {
                micBtn.disabled = true;
                micBtn.title = 'Trình duyệt chưa hỗ trợ nhập bằng giọng nói';
                micBtn.setAttribute('aria-label', 'Trình duyệt chưa hỗ trợ nhập bằng giọng nói');
                return;
            }

            recognition = new SpeechRecognition();
            recognition.lang = 'vi-VN';
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            recognition.onresult = function(event) {
                if (!event || !event.results || !event.results[0] || !event.results[0][0]) {
                    return;
                }
                var transcript = String(event.results[0][0].transcript || '').trim();
                if (transcript !== '') {
                    input.value = transcript;
                    input.focus();
                }
            };

            recognition.onerror = function(event) {
                var errorCode = event && event.error ? String(event.error) : 'unknown';
                if (errorCode === 'not-allowed' || errorCode === 'service-not-allowed') {
                    setTypingStatus('Trình duyệt chưa được cấp quyền dùng microphone.');
                } else if (errorCode === 'no-speech') {
                    setTypingStatus('Không nhận được giọng nói. Bạn thử nói lại giúp mình.');
                } else {
                    setTypingStatus('Không thể nhận diện giọng nói lúc này.');
                }
            };

            recognition.onend = function() {
                setMicListeningState(false);
            };

            micBtn.addEventListener('click', function() {
                if (!recognition) {
                    setTypingStatus('Trình duyệt chưa hỗ trợ nhập bằng giọng nói.');
                    return;
                }

                if (isListening) {
                    recognition.stop();
                    return;
                }

                setMicListeningState(true);
                try {
                    recognition.start();
                } catch (err) {
                    setMicListeningState(false);
                    setTypingStatus('Không thể bật microphone lúc này.');
                }
            });
        }

        function renderHistory(items) {
            clearMessages();

            if (!Array.isArray(items) || items.length === 0) {
                appendMessage('bot', 'Dạ, TechZone xin chào Quý khách. Em có thể hỗ trợ tư vấn sản phẩm, đơn hàng và khuyến mãi ạ.');
                return;
            }

            items.forEach(function(item) {
                var sender = (item && item.sender === 'user') ? 'user' : 'bot';
                var message = item && item.message ? String(item.message) : '';
                if (message.trim() !== '') {
                    appendMessage(sender, message);
                }
            });
        }

        function loadHistory() {
            return fetch(historyUrl, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            })
                .then(function(res) {
                    return res.json();
                })
                .then(function(json) {
                    var items = (json && Array.isArray(json.items)) ? json.items : [];
                    renderHistory(items);
                    historyLoaded = true;
                })
                .catch(function() {
                    if (body.children.length === 0) {
                        appendMessage('bot', 'Dạ, TechZone xin chào Quý khách. Em có thể hỗ trợ tư vấn sản phẩm, đơn hàng và khuyến mãi ạ.');
                    }
                });
        }

        function openChat() {
            wrapper.classList.add('is-open');
            loadHistory();
            window.setTimeout(function() {
                input.focus();
            }, 80);
        }

        function closeChat() {
            wrapper.classList.remove('is-open');
        }

        toggle.addEventListener('click', openChat);
        closeBtn.addEventListener('click', closeChat);
        setupSpeechRecognition();

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            var text = (input.value || '').trim();
            if (!text) {
                return;
            }

            if (recognition && isListening) {
                recognition.stop();
            }

            appendMessage('user', text);
            input.value = '';
            isRequestPending = true;
            typing.textContent = 'TechZone đang phản hồi...';

            fetch(askUrl, {
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
                    isRequestPending = false;
                    typing.textContent = '';
                    historyLoaded = true;
                });
        });
    })();
</script>
