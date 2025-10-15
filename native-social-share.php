<?php
/**
 * Plugin Name: Native Social Share
 * Description: A lightweight, performance-focused social sharing plugin using the native Web Share API with fallbacks.
 * Version: 1.0
 * Author: M Khoirul Huda
 * Author URI: https://mkhuda.com
 * Text Domain: native-social-share
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

register_activation_hook( __FILE__, function() {
    if ( get_option( 'nss_position' ) === false ) {
        update_option( 'nss_position', 'below' );
    }
    if ( get_option( 'nss_enabled_buttons' ) === false ) {
        update_option( 'nss_enabled_buttons', [
            'native' => true,
            'twitter' => true,
            'facebook' => true,
            'linkedin' => true,
        ]);
    }
});


// 1. Enqueue scripts and styles
function nss_enqueue_assets() {
    // Only load on single posts/pages
    if ( is_singular() ) {
        wp_enqueue_style(
            'nss-styles',
            plugin_dir_url( __FILE__ ) . 'assets/css/style.css',
            [],
            '1.0'
        );

        wp_enqueue_script(
            'nss-script',
            plugin_dir_url( __FILE__ ) . 'assets/js/share.js',
            [],
            '1.0',
            true // Load in the footer to be non-blocking
        );
    }
}
add_action( 'wp_enqueue_scripts', 'nss_enqueue_assets' );

// 2. Function to render the share buttons HTML
function nss_display_share_buttons() {
    $post_url   = get_permalink();
    $post_title = get_the_title();
    $buttons    = get_option( 'nss_enabled_buttons', [
        'native' => true,
        'twitter' => true,
        'facebook' => true,
        'linkedin' => true,
    ]);

    $html  = '<div class="nss-container" data-share-title="' . esc_attr( $post_title ) . '" data-share-url="' . esc_url( $post_url ) . '">';
    $html .= '<div class="nss-share-bar">';
    if ( ! empty( $buttons['native'] ) ) {
        $html .= '<button class="nss-share-button nss-native-share" aria-label="Share this post">
        <svg viewBox="0 0 24 24" height="18" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M9 12C9 13.3807 7.88071 14.5 6.5 14.5C5.11929 14.5 4 13.3807 4 12C4 10.6193 5.11929 9.5 6.5 9.5C7.88071 9.5 9 10.6193 9 12Z" stroke="#fff" stroke-width="1.5"></path>
            <path d="M14 6.5L9 10" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M14 17.5L9 14" stroke="#fff" stroke-width="1.5" stroke-linecap="round"></path>
            <path d="M19 18.5C19 19.8807 17.8807 21 16.5 21C15.1193 21 14 19.8807 14 18.5C14 17.1193 15.1193 16 16.5 16C17.8807 16 19 17.1193 19 18.5Z" stroke="#fff" stroke-width="1.5"></path>
            <path d="M19 5.5C19 6.88071 17.8807 8 16.5 8C15.1193 8 14 6.88071 14 5.5C14 4.11929 15.1193 3 16.5 3C17.8807 3 19 4.11929 19 5.5Z" stroke="#fff" stroke-width="1.5"></path>
        </svg>
    </button>'; 
    }
    $html .= '<div class="nss-fallback-links">';

    if ( ! empty( $buttons['twitter'] ) ) {
        $html .= '<a href="https://x.com/intent/tweet?url=' . urlencode( $post_url ) . '&text=' . urlencode( $post_title ) . '" target="_blank" rel="noopener noreferrer">Twitter (X)</a>';
    }
    if ( ! empty( $buttons['facebook'] ) ) {
        $html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . urlencode( $post_url ) . '" target="_blank" rel="noopener noreferrer">Facebook</a>';
    }
    if ( ! empty( $buttons['linkedin'] ) ) {
        $html .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . urlencode( $post_url ) . '&title=' . urlencode( $post_title ) . '" target="_blank" rel="noopener noreferrer">LinkedIn</a>';
    }
    $html .= '</div></div>'; // Close nss-share-bar
    $html .= '</div>'; // Close nss-container
    return $html;
}

// 3. Automatically add buttons to post content
function nss_add_buttons_to_content( $content ) {
    if ( is_singular() && in_the_loop() && is_main_query() ) {
        $position = get_option( 'nss_position', 'below' );
        $buttons  = nss_display_share_buttons();

        if ( $position === 'above' ) {
            return $buttons . $content;
        } else {
            return $content . $buttons;
        }
    }
    return $content;
}
add_filter( 'the_content', 'nss_add_buttons_to_content' );

// =============================
// 4. Admin Settings Page
// =============================

function nss_register_settings() {
    register_setting( 'nss_settings_group', 'nss_position', [
        'default' => 'below',
        'sanitize_callback' => 'sanitize_text_field'
    ] );

    register_setting( 'nss_settings_group', 'nss_enabled_buttons', [
        'default' => [
            'native'   => true,
            'twitter'  => true,
            'facebook' => true,
            'linkedin' => true,
        ],
        'sanitize_callback' => 'nss_sanitize_checkboxes'
    ] );
}

function nss_sanitize_checkboxes( $input ) {
    $defaults = ['native' => false, 'twitter' => false, 'facebook' => false, 'linkedin' => false];
    $output = [];
    foreach ( $defaults as $key => $val ) {
        $output[$key] = isset( $input[$key] ) ? (bool) $input[$key] : false;
    }
    return $output;
}

add_action( 'admin_init', 'nss_register_settings' );


function nss_add_settings_page() {
    add_options_page(
        'Native Social Share Settings',
        'Native Social Share',
        'manage_options',
        'native-social-share',
        'nss_render_settings_page'
    );
}
add_action( 'admin_menu', 'nss_add_settings_page' );


function nss_render_settings_page() {
    $current_position = get_option( 'nss_position', 'below' );
    $enabled_buttons  = get_option( 'nss_enabled_buttons', [
        'native' => true,
        'twitter' => true,
        'facebook' => true,
        'linkedin' => true,
    ]);
    ?>
    <div class="wrap">
        <h1>Native Social Share Settings</h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'nss_settings_group' ); ?>
            <?php do_settings_sections( 'nss_settings_group' ); ?>

            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Share Buttons Position</th>
                    <td>
                        <select name="nss_position">
                            <option value="above" <?php selected( $current_position, 'above' ); ?>>Above Content</option>
                            <option value="below" <?php selected( $current_position, 'below' ); ?>>Below Content</option>
                        </select>
                        <p class="description">Choose whether to display the share buttons above or below the post content.</p>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">Enabled Buttons</th>
                    <td>
                        <label><input type="checkbox" name="nss_enabled_buttons[native]" value="1" <?php checked( $enabled_buttons['native'], true ); ?>> Native Share</label><br>
                        <label><input type="checkbox" name="nss_enabled_buttons[twitter]" value="1" <?php checked( $enabled_buttons['twitter'], true ); ?>> Twitter (X)</label><br>
                        <label><input type="checkbox" name="nss_enabled_buttons[facebook]" value="1" <?php checked( $enabled_buttons['facebook'], true ); ?>> Facebook</label><br>
                        <label><input type="checkbox" name="nss_enabled_buttons[linkedin]" value="1" <?php checked( $enabled_buttons['linkedin'], true ); ?>> LinkedIn</label>
                        <p class="description">Select which share buttons you want to display.</p>
                    </td>
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

