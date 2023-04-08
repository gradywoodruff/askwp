<?php
class AskWP_Shortcode {
    private $api_client;

    public function __construct($api_client) {
        $this->api_client = $api_client;
    }

    public function init() {
        add_shortcode('askwp', array($this, 'render_shortcode'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts_styles'));
        add_action('wp_ajax_askwp_submit', array($this, 'handle_ajax_request'));
        add_action('wp_ajax_nopriv_askwp_submit', array($this, 'handle_ajax_request'));
    }

    public function render_shortcode($atts) {
        $output = '<div class="askwp-wrapper">';
        $output .= '<div class="askwp-messages"></div>';
        $output .= '<input type="text" class="askwp-input" placeholder="Type your question here...">';
        $output .= '</div>';
    
        return $output;
    }
    

    public function enqueue_scripts_styles() {
        wp_enqueue_style('askwp-style', plugins_url('css/style.css', dirname(__FILE__)));
        wp_enqueue_script('askwp-script', plugins_url('js/script.js', dirname(__FILE__)), array('jquery'), false, true);

        wp_localize_script('askwp-script', 'askwp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('askwp_submit_nonce')
        ));
    }

    public function handle_ajax_request() {
        check_ajax_referer('askwp_submit_nonce', 'security');

        $response = array('success' => false, 'message' => 'Invalid request.');

        if (isset($_POST['prompt']) && !empty($_POST['prompt'])) {
            $prompt = sanitize_text_field($_POST['prompt']);
            $api_response = $this->api_client->send_request($prompt);

            if (!is_wp_error($api_response)) {
                $response['success'] = true;
                $response['message'] = $api_response;
            } else {
                $response['message'] = $api_response->get_error_message();
            }
        }

        echo json_encode($response);
        wp_die();
    }
}
?>