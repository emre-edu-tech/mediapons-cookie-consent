<?php
/*
    Plugin Name: Media Pons Cookie Consent
    Description: Plugin that makes possible to put a Cookie Consent Notice on website
    Version: 1.0
    Author: Media Pons
    Author URI: https://mediapons.de
*/

if(!defined('ABSPATH')) exit;

class MediaPonsCookieConsent {
    function __construct()
    {
        add_action('admin_menu', [$this, 'admin_page']);
        // For the WORDPRESS GENERATED FORMS use the admin_init action
        add_action('admin_init', [$this, 'cookie_consent_settings']);
    }

    function cookie_consent_settings() {
        // add a section to hold the settings for the Cookie Consent Options / Settings
        add_settings_section('mp_cookie_consent_section', null, null, 'mediapons-cookie-consent-options');
        
        // Settings and fields for the Cookie Consent Section
        // Cookie Consent Notice Toggle. If checked Cookie Consent active, if unchecked cookie consent is not active
        add_settings_field('mp_cookie_consent_check', 'Add Cookie Consent to Website', [$this, 'general_checkbox_html'], 'mediapons-cookie-consent-options', 'mp_cookie_consent_section', ['custom_option_name' => 'mp_cookie_consent_check']);
        register_setting('mediapons-cookie-consent', 'mp_cookie_consent_check', [
            'sanitize_callback' => [$this, 'sanitize_cookie_consent'],
            'default' => ''
        ]);

        // Cookie Consent Button Text
        add_settings_field('mp_cookie_consent_btn_text', 'Cookie Consent Accept Button Text', [$this, 'cookie_consent_accept_btn_html'], 'mediapons-cookie-consent-options', 'mp_cookie_consent_section');
        register_setting('mediapons-cookie-consent', 'mp_cookie_consent_btn_text', [
            'sanitize_callback' => 'sanitize_text_field',
            'default' => 'Akzeptieren'
        ]);
    }

    function cookie_consent_accept_btn_html() { ?>
        <input type="text" name="mp_cookie_consent_btn_text" value="<?php echo esc_attr(get_option('mp_cookie_consent_btn_text', 'Akzeptieren')) ?>" placeholder="Enter Cookie Consent Button Text">
        <p class="description">Write a proper text for the accept button in Cookie Consent.</p>
    <?php }
    
    function sanitize_cookie_consent($input_val) {
        if($input_val != '1' && $input_val != '') {
            add_settings_error('mp_cookie_consent_check', 'mp_cookie_consent_check_error', 'Cookie Consent checbox checked value is wrong. Try again!');
            return get_option('mp_cookie_consent_check');
        }
        
        return $input_val;
    }

    // Reusable function to output input checkbox to the page
    function general_checkbox_html($args) { ?>
        <input type="checkbox" name="<?php echo $args['custom_option_name'] ?>" value="1" <?php checked(get_option($args['custom_option_name']), '1') ?>>
    <?php }

    function admin_page() {
        // adds a top level admin menu page
        // use svg file directly - suitable if you want your custom colored svg icon
        // add_menu_page('Media Pons Cookie Consent', 'MP Cookie Consent', 'manage_options', 'mediapons-cookie-consent', [$this, 'cookie_consent_page_content'], plugin_dir_url(__FILE__) . '/img/cookie.svg', 100);
        // use base64 encoded svg for menu icon - preferred way
        $main_menu_page_hook = add_menu_page('Media Pons Cookie Consent', 'MP Cookie Consent', 'manage_options', 'mediapons-cookie-consent', [$this, 'cookie_consent_page_content'], 'data:image/svg+xml;base64,PHN2ZyBmaWxsPSIjMDAwMDAwIiBoZWlnaHQ9IjgwMHB4IiB3aWR0aD0iODAwcHgiIHZlcnNpb249IjEuMSIgaWQ9IkNhcGFfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgCgkgdmlld0JveD0iMCAwIDQxNi45OTEgNDE2Ljk5MSIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+CjxnPgoJPHBhdGggZD0iTTE1OS43MDYsOTkuMTExYy0yMy4xNTksMC00MiwxOC44NDEtNDIsNDJzMTguODQxLDQyLDQyLDQyYzIzLjE1OSwwLDQyLTE4Ljg0MSw0Mi00MlMxODIuODY2LDk5LjExMSwxNTkuNzA2LDk5LjExMXoKCQkgTTE1OS43MDYsMTYzLjExMWMtMTIuMTMxLDAtMjItOS44NjktMjItMjJjMC0xMi4xMzEsOS44NjktMjIsMjItMjJjMTIuMTMxLDAsMjIsOS44NjksMjIsMjIKCQlDMTgxLjcwNiwxNTMuMjQyLDE3MS44MzcsMTYzLjExMSwxNTkuNzA2LDE2My4xMTF6Ii8+Cgk8cGF0aCBkPSJNMTMxLjk0OCwyNTAuMjk1Yy0yMy4xNTksMC00MiwxOC44NDEtNDIsNDJjMCwyMy4xNTksMTguODQxLDQyLDQyLDQyYzIzLjE1OSwwLDQyLTE4Ljg0MSw0Mi00MgoJCUMxNzMuOTQ4LDI2OS4xMzYsMTU1LjEwNywyNTAuMjk1LDEzMS45NDgsMjUwLjI5NXogTTEzMS45NDgsMzE0LjI5NWMtMTIuMTMxLDAtMjItOS44NjktMjItMjJjMC0xMi4xMzEsOS44NjktMjIsMjItMjIKCQljMTIuMTMxLDAsMjIsOS44NjksMjIsMjJDMTUzLjk0OCwzMDQuNDI2LDE0NC4wNzksMzE0LjI5NSwxMzEuOTQ4LDMxNC4yOTV6Ii8+Cgk8cGF0aCBkPSJNNDE2Ljk3LDIwNi41OTZsLTAuMDEzLTAuODMxYy0wLjA2NC01LjI3OS00LjIyMi05LjU5OC05LjQ5NC05Ljg2NGMtMTQuODc1LTAuNzUxLTI4LjAwNy05LjYzOS0zNC4yNy0yMy4xOTMKCQljLTEuMjQ1LTIuNjk0LTMuNjIzLTQuNjk2LTYuNDg5LTUuNDY1Yy0yLjg2Ny0wLjc2OS01LjkyNy0wLjIyNC04LjM1MywxLjQ4N2MtNi43MDYsNC43My0xNC45MjcsNy4zMzUtMjMuMTQ2LDcuMzM2CgkJYy02Ljk2NCwwLTEzLjg1Ny0xLjg1NC0xOS45MzUtNS4zNjNjLTEzLjQ1OC03Ljc3LTIxLjI0Mi0yMi44MDMtMTkuODMtMzguMjk5YzAuMjY5LTIuOTU2LTAuNzg5LTUuODc5LTIuODg4LTcuOTc3CgkJYy0yLjEtMi4xLTUuMDMzLTMuMTU0LTcuOTc3LTIuODg5Yy0xLjE5NSwwLjEwOS0yLjQxMSwwLjE2NC0zLjYxNCwwLjE2NGMtMTQuMjcyLDAtMjcuNTYyLTcuNjYyLTM0LjY4My0xOS45OTYKCQljLTcuNzctMTMuNDU4LTYuOTk0LTMwLjM2OSwxLjk3Ni00My4wODRjMS43MTEtMi40MjUsMi4yNTctNS40ODUsMS40ODgtOC4zNTJjLTAuNzY4LTIuODY3LTIuNzctNS4yNDUtNS40NjQtNi40OQoJCWMtMTMuNTQ4LTYuMjYyLTIyLjQzNC0xOS4zODctMjMuMTg5LTM0LjI1NGMtMC4yNjgtNS4yNjktNC41ODMtOS40MjQtOS44NTgtOS40OTJsLTAuODE2LTAuMDEzQzIwOS43NzcsMC4wMSwyMDkuMTM3LDAsMjA4LjQ5NiwwCgkJQzkzLjUzMSwwLDAuMDAxLDkzLjUzMSwwLjAwMSwyMDguNDk2czkzLjUzLDIwOC40OTYsMjA4LjQ5NSwyMDguNDk2czIwOC40OTUtOTMuNTMxLDIwOC40OTUtMjA4LjQ5NgoJCUM0MTYuOTkxLDIwNy44NjEsNDE2Ljk4MSwyMDcuMjI5LDQxNi45NywyMDYuNTk2eiBNNjkuOTc3LDEwNi4xMTFjMCwxMi4xMzEtOS44NjksMjItMjIsMjJjLTMuMTQ1LDAtNi4yMDItMC42ODktOS4wMTEtMS45NTQKCQljNi40MDctMTMuMTM4LDE0LjI5NC0yNS40MjUsMjMuNDQ4LTM2LjY0M0M2Ny4xMzksOTMuNjE3LDY5Ljk3Nyw5OS42MDgsNjkuOTc3LDEwNi4xMTF6IE0yMDguNDk2LDM5Ni45OTEKCQlDMTA0LjU1OSwzOTYuOTkxLDIwLDMxMi40MzMsMjAsMjA4LjQ5NmMwLTIyLjQyLDMuOTM4LTQzLjkzNywxMS4xNTMtNjMuOWM1LjI1NCwyLjI5OSwxMC45NjYsMy41MTYsMTYuODIzLDMuNTE2CgkJYzIzLjE1OSwwLDQyLTE4Ljg0MSw0Mi00MmMwLTEyLjI3MS01LjI3Ni0yMy42MDMtMTQuMTA4LTMxLjQyNGMzMi43MzItMzIuNDQ2LDc3LjI2LTUzLjAwOSwxMjYuNTAyLTU0LjU4OQoJCWMzLjE1NywxNC43NjMsMTEuNzY0LDI3Ljc0NiwyNC4xMDcsMzYuNDE4Yy04LjA2NCwxNy40OTUtNy4zNDEsMzguMTc5LDIuNDgsNTUuMTljOS43NzEsMTYuOTI1LDI3LjI3OCwyNy45ODUsNDYuNTY3LDI5Ljc0OAoJCWMxLjc2MSwxOS4xODgsMTIuNzI5LDM2Ljc0NywyOS43NDQsNDYuNTdjOS4xMTQsNS4yNjIsMTkuNDY2LDguMDQzLDI5LjkzNiw4LjA0MmM4LjgyLTAuMDAxLDE3LjM5Mi0xLjg5NywyNS4yNTgtNS41NDQKCQljOC42NzYsMTIuMzQzLDIxLjY2MSwyMC45NDcsMzYuNDI3LDI0LjEwMmMtMC40NTYsMTQuMjE3LTIuNDk0LDI4LjA0Mi01Ljk0NCw0MS4zMDNjLTQuNDQ1LTEuNTYxLTkuMTUxLTIuMzgtMTMuOTA1LTIuMzgKCQljLTIzLjE1OSwwLTQyLDE4Ljg0MS00Miw0MmMwLDEzLjc5MSw2Ljg0OCwyNi40NTQsMTcuNjYsMzQuMTkzQzMxOC4wOTksMzcwLjgzMiwyNjYuMjk4LDM5Ni45OTEsMjA4LjQ5NiwzOTYuOTkxegoJCSBNMzY0Ljc2OCwzMTMuNzgxYy01LjkzNS00LjAxNi05LjcyNC0xMC44MTEtOS43MjQtMTguMjM0YzAtMTIuMTMxLDkuODY5LTIyLDIyLTIyYzIuNzI1LDAsNS4zNTYsMC41MDEsNy44MjUsMS40NDQKCQlDMzc5LjY2MiwyODguNzU3LDM3Mi44OTIsMzAxLjc2MSwzNjQuNzY4LDMxMy43ODF6Ii8+Cgk8cGF0aCBkPSJNMjQ2LjQ3NSwyMDYuMjU5Yy0yOS43NzUsMC01NCwyNC4yMjQtNTQsNTRzMjQuMjI1LDU0LDU0LDU0czU0LTI0LjIyNCw1NC01NFMyNzYuMjUsMjA2LjI1OSwyNDYuNDc1LDIwNi4yNTl6CgkJIE0yNDYuNDc1LDI5NC4yNTljLTE4Ljc0OCwwLTM0LTE1LjI1My0zNC0zNGMwLTE4Ljc0OCwxNS4yNTItMzQsMzQtMzRjMTguNzQ4LDAsMzQsMTUuMjUyLDM0LDM0CgkJQzI4MC40NzUsMjc5LjAwNiwyNjUuMjIzLDI5NC4yNTksMjQ2LjQ3NSwyOTQuMjU5eiIvPgo8L2c+Cjwvc3ZnPg==', 100);
        // In order to change the label of the exact same sub page as the main menu page, this function below is mandatory -- this function below just makes possible to use Custom Text for the main sub page
        add_submenu_page('mediapons-cookie-consent', 'Cookie Consent', 'Cookie Consent', 'manage_options', 'mediapons-cookie-consent', [$this, 'cookie_consent_page_content']);
        // adds a submenu page that belongs to a menu page
        add_submenu_page('mediapons-cookie-consent', 'Cookie Consent Options', 'Options', 'manage_options', 'mediapons-cookie-consent-options', [$this, 'cookie_consent_options_page_content']);
        // load custom css just for this admin page
        add_action("load-{$main_menu_page_hook}", [$this, 'load_main_page_assets']);
    }

    function load_main_page_assets() {
        wp_enqueue_style('mp-cookie-consent-css', plugin_dir_url(__FILE__) . '/css/styles.css');
    }

    function cookie_consent_options_page_content() { ?>
        <div class="wrap">
            <h1>Cookie Consent Options</h1>
            <form action="options.php" method="post">
                <?php
                    // settings_errors() function is called by the Wordpress if it is an Options/Settings page
                    // if it is a Generic Admin Menu or Admin Submenu page, then settings_errors() function should be 
                    // called explicitly
                    settings_errors();
                    settings_fields('mediapons-cookie-consent');
                    do_settings_sections('mediapons-cookie-consent-options');
                    submit_button();
                ?>
            </form>
        </div>
    <?php }

    function handle_form_submit() {
        // Double check here. Both wordpress nonce value and current user capability
        if (isset($_POST['cookie_consent_nonce']) && wp_verify_nonce($_POST['cookie_consent_nonce'], 'save_cookie_consent_text') && current_user_can('manage_options')) {
            // save the incoming value to the options table by using update_option()
            update_option('mp_cookie_consent_text', wp_kses_post($_POST['mp_cookie_consent_text'])); ?>
            <div class="updated">
                <p>Your cookie consent text were saved successfully.</p>
            </div>
        <?php } else { ?>
            <div class="error">
                <p>Sorry, you do not have permission to perform this action.</p>
            </div>
        <?php }
    }

    function cookie_consent_page_content() { ?>
        <div class="wrap">
            <h1>Cookie Consent</h1>
            <form action="#" method="POST">
                <?php if(isset($_POST['form_submitted']) && $_POST['form_submitted'] == 'true') {
                    $this->handle_form_submit();  
                } ?>
                <input type="hidden" name="form_submitted" value="true">
                <?php wp_nonce_field('save_cookie_consent_text', 'cookie_consent_nonce') ?>
                <label for="mp_cookie_consent_text">Enter Cookie Consent Text</label>
                <div class="cookie-consent__flex-container">
                    <?php
                        wp_editor(wp_kses_post(get_option('mp_cookie_consent_text', 'Cookie Consent text to warn website users')), 'mp_cookie_consent_text', [
                            'media_buttons' => false,
                            'wpautop' => false,
                            'teeny' => true    // remove most of the unnecessary buttons from wp editor
                        ])
                    ?>
                </div>
                <input type="submit" value="Save Changes" name="submit" id="submit" class="button button-primary">
            </form>
        </div>
    <?php }
}

$mediaPonsCookieConsent = new MediaPonsCookieConsent();