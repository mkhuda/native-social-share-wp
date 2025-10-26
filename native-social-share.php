<?php

/**
 * Plugin Name: Native Social Share
 * Description: A lightweight, performance-focused social sharing plugin using the native Web Share API with fallbacks.
 * Version: 1.1.0
 * Author: M Khoirul Huda
 * Author URI: https://mkhuda.com
 * Text Domain: native-social-share
 * Requires at least: 6.0
 * Requires PHP: 7.4
 * License: GPL-3.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

declare(strict_types=1);

namespace Mkhuda\NativeSocialShare;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Constants (unique + prefixed)
 */
define( 'NATSSH_VERSION', '1.1.0' );
define( 'NATSSH_PLUGIN_FILE', __FILE__ );
define( 'NATSSH_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NATSSH_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

/**
 * Option keys (unique + prefixed)
 */
const NATSSH_OPTION_POSITION = 'natssh_position';
const NATSSH_OPTION_BUTTONS  = 'natssh_enabled_buttons';
const NATSSH_SETTINGS_GROUP  = 'natssh_settings_group';

/**
 * Activation: set defaults + migrate old option names
 */
\register_activation_hook( __FILE__, __NAMESPACE__ . '\\natssh_on_activate' );
function natssh_on_activate(): void {
	// Migrate old keys if exist
	$old_pos = \get_option( 'nss_position', null );
	$old_btn = \get_option( 'nss_enabled_buttons', null );

	if ( null !== $old_pos ) {
		\update_option( NATSSH_OPTION_POSITION, $old_pos );
		\delete_option( 'nss_position' );
	}
	if ( null !== $old_btn ) {
		\update_option( NATSSH_OPTION_BUTTONS, $old_btn );
		\delete_option( 'nss_enabled_buttons' );
	}

	if ( \get_option( NATSSH_OPTION_POSITION ) === false ) {
		\update_option( NATSSH_OPTION_POSITION, 'below' );
	}
	if ( \get_option( NATSSH_OPTION_BUTTONS ) === false ) {
		\update_option(
			NATSSH_OPTION_BUTTONS,
			array(
				'native'   => true,
				'twitter'  => true,
				'facebook' => true,
				'linkedin' => true,
			)
		);
	}
}

/**
 * Enqueue assets only on singular
 */
\add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\natssh_enqueue_assets' );
function natssh_enqueue_assets(): void {
	if ( \is_singular() ) {
		\wp_enqueue_style(
			'natssh-styles',
			NATSSH_PLUGIN_URL . 'assets/css/style.css',
			array(),
			NATSSH_VERSION
		);

		\wp_enqueue_script(
			'natssh-script',
			NATSSH_PLUGIN_URL . 'assets/js/share.js',
			array(),
			NATSSH_VERSION,
			true
		);
	}
}

/**
 * Render share buttons HTML
 */
function natssh_render_share_buttons(): string {
	$post_url   = \get_permalink();
	$post_title = \get_the_title();

	$buttons = \get_option(
		NATSSH_OPTION_BUTTONS,
		array(
			'native'   => true,
			'twitter'  => true,
			'facebook' => true,
			'linkedin' => true,
		)
	);

	$html  = '<div class="nss-container" data-share-title="' . \esc_attr( $post_title ) . '" data-share-url="' . \esc_url( $post_url ) . '">';
	$html .= '<div class="nss-share-bar">';

	if ( ! empty( $buttons['native'] ) ) {
		$html .= '<button class="nss-share-button nss-native-share" aria-label="' . \esc_attr__( 'Share this post', 'native-social-share' ) . '">
        <svg viewBox="0 0 24 24" height="18" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
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
		$html .= '<a href="https://x.com/intent/tweet?url=' . \urlencode( (string) $post_url ) . '&text=' . \urlencode( (string) $post_title ) . '" target="_blank" rel="noopener noreferrer">Twitter (X)</a>';
	}
	if ( ! empty( $buttons['facebook'] ) ) {
		$html .= '<a href="https://www.facebook.com/sharer/sharer.php?u=' . \urlencode( (string) $post_url ) . '" target="_blank" rel="noopener noreferrer">Facebook</a>';
	}
	if ( ! empty( $buttons['linkedin'] ) ) {
		$html .= '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . \urlencode( (string) $post_url ) . '&title=' . \urlencode( (string) $post_title ) . '" target="_blank" rel="noopener noreferrer">LinkedIn</a>';
	}
	$html .= '</div>'; // .nss-fallback-links

	$html .= '</div>'; // .nss-share-bar
	$html .= '</div>'; // .nss-container

	return $html;
}

/**
 * Inject buttons to content
 */
\add_filter( 'the_content', __NAMESPACE__ . '\\natssh_add_buttons_to_content' );
function natssh_add_buttons_to_content( string $content ): string {
	if ( \is_singular() && \in_the_loop() && \is_main_query() ) {
		$position = \get_option( NATSSH_OPTION_POSITION, 'below' );
		$buttons  = natssh_render_share_buttons();

		if ( $position === 'above' ) {
			return $buttons . $content;
		}
		if ( $position === 'both' ) {
			return $buttons . $content . $buttons;
		}
		return $content . $buttons;
	}
	return $content;
}

/**
 * Settings
 */
\add_action( 'admin_init', __NAMESPACE__ . '\\natssh_register_settings' );
function natssh_register_settings(): void {
	\register_setting(
		NATSSH_SETTINGS_GROUP,
		NATSSH_OPTION_POSITION,
		array(
			'default'           => 'below',
			'sanitize_callback' => 'sanitize_text_field',
		)
	);

	\register_setting(
		NATSSH_SETTINGS_GROUP,
		NATSSH_OPTION_BUTTONS,
		array(
			'default'           => array(
				'native'   => true,
				'twitter'  => true,
				'facebook' => true,
				'linkedin' => true,
			),
			'sanitize_callback' => __NAMESPACE__ . '\\natssh_sanitize_checkboxes',
		)
	);
}

function natssh_sanitize_checkboxes( $input ): array {
	$defaults = array(
		'native'   => false,
		'twitter'  => false,
		'facebook' => false,
		'linkedin' => false,
	);
	$output   = array();
	foreach ( $defaults as $key => $val ) {
		$output[ $key ] = isset( $input[ $key ] ) ? (bool) $input[ $key ] : false;
	}
	return $output;
}

\add_action( 'admin_menu', __NAMESPACE__ . '\\natssh_add_settings_page' );
function natssh_add_settings_page(): void {
	\add_options_page(
		__( 'Native Social Share Settings', 'native-social-share' ),
		__( 'Native Social Share', 'native-social-share' ),
		'manage_options',
		'native-social-share',
		__NAMESPACE__ . '\\natssh_render_settings_page'
	);
}

function natssh_render_settings_page(): void {
	$current_position = \get_option( NATSSH_OPTION_POSITION, 'below' );
	$enabled_buttons  = \get_option(
		NATSSH_OPTION_BUTTONS,
		array(
			'native'   => true,
			'twitter'  => true,
			'facebook' => true,
			'linkedin' => true,
		)
	);
	?>
	<div class="wrap">
		<h1><?php echo \esc_html__( 'Native Social Share Settings', 'native-social-share' ); ?></h1>
		<form method="post" action="options.php">
			<?php \settings_fields( NATSSH_SETTINGS_GROUP ); ?>
			<?php \do_settings_sections( NATSSH_SETTINGS_GROUP ); ?>

			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><?php echo \esc_html__( 'Share Buttons Position', 'native-social-share' ); ?></th>
					<td>
						<select name="<?php echo \esc_attr( NATSSH_OPTION_POSITION ); ?>">
							<option value="above" <?php \selected( $current_position, 'above' ); ?>><?php echo \esc_html__( 'Above Content', 'native-social-share' ); ?></option>
							<option value="below" <?php \selected( $current_position, 'below' ); ?>><?php echo \esc_html__( 'Below Content', 'native-social-share' ); ?></option>
							<option value="both" <?php \selected( $current_position, 'both' ); ?>><?php echo \esc_html__( 'Above and Below Content', 'native-social-share' ); ?></option>
						</select>
						<p class="description"><?php echo \esc_html__( 'Choose whether to display the share buttons above or below the post content.', 'native-social-share' ); ?></p>
					</td>
				</tr>

				<tr>
					<th scope="row"><?php echo \esc_html__( 'Enabled Buttons', 'native-social-share' ); ?></th>
					<td>
						<label><input type="checkbox" name="<?php echo \esc_attr( NATSSH_OPTION_BUTTONS ); ?>[native]" value="1" <?php \checked( ! empty( $enabled_buttons['native'] ), true ); ?>> <?php echo \esc_html__( 'Native Share', 'native-social-share' ); ?></label><br>
						<label><input type="checkbox" name="<?php echo \esc_attr( NATSSH_OPTION_BUTTONS ); ?>[twitter]" value="1" <?php \checked( ! empty( $enabled_buttons['twitter'] ), true ); ?>> Twitter (X)</label><br>
						<label><input type="checkbox" name="<?php echo \esc_attr( NATSSH_OPTION_BUTTONS ); ?>[facebook]" value="1" <?php \checked( ! empty( $enabled_buttons['facebook'] ), true ); ?>> Facebook</label><br>
						<label><input type="checkbox" name="<?php echo \esc_attr( NATSSH_OPTION_BUTTONS ); ?>[linkedin]" value="1" <?php \checked( ! empty( $enabled_buttons['linkedin'] ), true ); ?>> LinkedIn</label>
						<p class="description"><?php echo \esc_html__( 'Select which share buttons you want to display.', 'native-social-share' ); ?></p>
					</td>
				</tr>
			</table>

			<?php \submit_button(); ?>
		</form>
	</div>
	<?php
}
