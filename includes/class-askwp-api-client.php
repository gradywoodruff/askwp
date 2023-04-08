<?php
class AskWP_API_Client {
    private $api_key;
    private $api_url = 'https://api.openai.com/v1/chat/completions';

    public function __construct() {
        $this->api_key = get_option('askwp_api_key');
    }

    public function send_request($prompt) {
        $headers = array(
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->api_key
        );

        $body = array(
            'model' => 'gpt-3.5-turbo',
            'messages' => array(
                array(
                    'role' => 'user',
                    'content' => $prompt
                )
            )
        );

        $response = wp_remote_post($this->api_url, array(
            'headers' => $headers,
            'body' => json_encode($body),
            'timeout' => 60,
            'sslverify' => true
        ));

        if (is_wp_error($response)) {
            return $response;
        }

        $response_body = json_decode(wp_remote_retrieve_body($response), true);

        if (isset($response_body['choices'][0]['message']['content'])) {
            return $response_body['choices'][0]['message']['content'];
        }

        return new WP_Error('api_error', 'An error occurred while processing the request.');
    }
}
