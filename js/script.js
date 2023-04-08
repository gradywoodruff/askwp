document.addEventListener('DOMContentLoaded', function () {
    var submitButton = document.querySelector('.askwp-submit');
    var input = document.querySelector('.askwp-input');
    var prompt = input.value;
    

    

    input.addEventListener('keydown', function (event) {
        var input = document.querySelector('.askwp-input');
        var prompt = input.value;
        if (event.key === 'Enter') {

            if (prompt.trim() !== '') {
                addMessageToChat('user', prompt);
                input.value = '';

                var xhr = new XMLHttpRequest();
                xhr.open('POST', askwp_ajax.ajax_url, true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var response = JSON.parse(xhr.responseText);

                        if (response.success) {
                            addMessageToChat('gpt', response.message);
                        } else {
                            addMessageToChat('gpt', 'Error: ' + response.message);
                        }
                    }
                };

                var data = 'action=askwp_submit&security=' + encodeURIComponent(askwp_ajax.nonce) + '&prompt=' + encodeURIComponent(prompt);
                xhr.send(data);
            }
        }
    });

    

    function addMessageToChat(role, content) {
        var messagesContainer = document.querySelector('.askwp-messages');
        var messageElement = document.createElement('div');
        messageElement.className = 'askwp-message';

        var roleElement = document.createElement('span');
        roleElement.className = 'role';
        roleElement.textContent = role === 'user' ? 'You: ' : 'ChatGPT: ';
        messageElement.appendChild(roleElement);

        var contentElement = document.createElement('span');
        contentElement.className = 'content';
        contentElement.textContent = content;
        messageElement.appendChild(contentElement);

        messagesContainer.appendChild(messageElement);

        // Scroll to the bottom of the messages container
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});