<?php
class AskWP_Settings {
    private $api_client;

    public function __construct($api_client) {
        $this->api_client = $api_client;
    }

    public function init() {
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_styles')); // Added this line
    }

    public function add_settings_page() {
        add_options_page(
            'AskWP Settings',
            'AskWP',
            'manage_options',
            'askwp-settings',
            array($this, 'render_settings_page')
        );
    }

    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1>AskWP Settings</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('askwp_settings_group');
                do_settings_sections('askwp-settings');
                submit_button();
                ?>
            </form>

            <!-- Added the ChatGPT interface below the settings form -->
            <h2>ChatGPT Interface</h2>
            <?php echo do_shortcode('[askwp]'); ?>
        </div>
        <?php
    }

    public function register_settings() {
        register_setting('askwp_settings_group', 'askwp_api_key');

        add_settings_section(
            'askwp_settings_section',
            'API Settings',
            null,
            'askwp-settings'
        );

        add_settings_field(
            'askwp_api_key',
            'API Key',
            array($this, 'api_key_field_callback'),
            'askwp-settings',
            'askwp_settings_section'
        );
    }

    public function api_key_field_callback() {
        $api_key = get_option('askwp_api_key');
        echo '<input type="text" name="askwp_api_key" value="' . esc_attr($api_key) . '" class="regular-text">';
    }

    // Added this function to enqueue the necessary scripts and styles
    public function enqueue_scripts_styles($hook) {
        if ($hook !== 'settings_page_askwp-settings') {
            return;
        }

        wp_enqueue_style('askwp-style', plugins_url('css/style.css', dirname(__FILE__)));
        wp_enqueue_script('askwp-script', plugins_url('js/script.js', dirname(__FILE__)), array(), false, true);

        wp_localize_script('askwp-script', 'askwp_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('askwp_submit_nonce')
        ));
    }
}

?>