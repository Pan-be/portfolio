// AI assistant demo on the home page (ai-demo.php). The history lives only in this page;
// each request sends it whole to the endpoint, which keeps the last 10 messages.
(function () {
    const chat = document.querySelector('[data-chat]');
    const form = chat && chat.querySelector('[data-chat-form]');
    if (!form) {
        return;
    }

    const log = chat.querySelector('[data-chat-log]');
    const input = form.querySelector('input[name="message"]');
    const trap = form.querySelector('input[name="website"]');
    const send = form.querySelector('button[type="submit"]');
    const starters = chat.querySelector('[data-chat-starters]');
    const polish = chat.dataset.lang !== 'en';
    const history = [];
    let busy = false;

    function addMessage(role, text) {
        const message = document.createElement('p');
        message.className = 'chat__message chat__message--' + role;
        // The model is told to write plain text; strip Markdown bold in case it slips in.
        message.textContent = text.replace(/\*\*(.+?)\*\*/g, '$1');
        log.append(message);
        log.scrollTop = log.scrollHeight;
        return message;
    }

    function setBusy(state) {
        busy = state;
        input.disabled = state;
        send.disabled = state;
        chat.classList.toggle('chat--busy', state);
    }

    // GA4 event, only when the visitor accepted analytics (header.php loads GA then).
    function track(name, params) {
        if (window.panbeAnalyticsLoaded && window.gtag) {
            window.gtag('event', name, params);
        }
    }

    async function ask(text, source) {
        text = text.trim();
        if (busy || text === '') {
            return;
        }
        if (starters) {
            starters.hidden = true;
        }
        addMessage('user', text);
        history.push({ role: 'user', content: text });
        input.value = '';
        setBusy(true);
        const typing = addMessage('typing', polish ? 'Asystent pisze…' : 'The assistant is typing…');

        let reply = '';
        let failed = false;
        try {
            const response = await fetch(chat.dataset.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ messages: history, lang: chat.dataset.lang, website: trap.value }),
            });
            const data = await response.json();
            reply = data.reply || data.error || '';
            failed = !data.reply;
        } catch (e) {
            failed = true;
        }
        typing.remove();

        if (failed) {
            // Drop the unanswered question so the next request still alternates user/assistant.
            history.pop();
            addMessage('error', reply || (polish ? 'Nie udało się połączyć. Spróbuj za chwilę.' : 'Could not connect. Try again in a moment.'));
        } else {
            history.push({ role: 'assistant', content: reply });
            addMessage('assistant', reply);
            track('ai_demo_message', { message_number: history.length / 2, source: source });
        }
        setBusy(false);
        input.focus();
    }

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        ask(input.value, 'typed');
    });

    if (starters) {
        starters.addEventListener('click', function (e) {
            const starter = e.target.closest('button');
            if (starter) {
                ask(starter.textContent, 'starter');
            }
        });
    }

    const book = chat.querySelector('[data-chat-book]');
    const bookNote = chat.querySelector('[data-chat-book-note]');
    book.addEventListener('click', function () {
        bookNote.hidden = false;
        track('ai_demo_book_click', {});
    });
})();
