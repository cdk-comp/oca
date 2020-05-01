<?php
/**
 * Plugin Name:       OCA
 * Description:       OCA plugin is the helper for One Click Accessibility plugin.
 * Plugin URI:        https://cdk.co.il
 * Author:            Dima Minka
 * Author             URI: https://dimaminka.com
 * Version:           1.0.0
 * License:           GPLv2 or later
 * Text Domain:       oca
 * Domain Path:       /lang
 *
 * @package OCA
 */

$admin_page_name = 'oca-main';

/**
 * Add menu page
 */
function add_oca_menu_page() {
	global $admin_page_name;

	add_submenu_page(
		'accessibility-settings',
		__( 'OCA', 'oca' ),
		__( 'OCA', 'oca' ),
		'manage_options',
		$admin_page_name,
		'access_fields_view'
	);
}

add_action( 'admin_menu', 'add_oca_menu_page', 99 );

/**
 * View fields
 */
function access_fields_view() {
	global $admin_page_name;
	?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>

		<?php
		if ( get_current_screen()->parent_base !== 'options-general' ) {
			settings_errors( 'src_1' );
		}
		?>

        <form action="options.php" method="POST">
			<?php
			settings_fields( "oca_group" );
			do_settings_sections( $admin_page_name );
			submit_button();
			?>
        </form>
    </div>
	<?php
}

/**
 * Setting Options
 */
function oca_option_settings() {
	global $admin_page_name;

	$options_name = 'oca_access_options_1';
	register_setting( 'oca_group', $options_name, 'true_validate_settings' );

	// Add section
	add_settings_section( 'oca_section_1', __( 'First Links', 'oca' ), '', $admin_page_name );

	// Add field 2
	$field_params = [
		'type'        => 'text',
		'id'          => 'text_1',
		'desc'        => __( 'Input Text Link', 'oca' ),
		'label_for'   => 'text_1',
		'option_name' => $options_name
	];
	add_settings_field( 'text_1_field', __( 'Input Text Link', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_1', $field_params );


	// Add field 1
	$field_params = [
		'type'        => 'url',
		'id'          => 'src_1',
		'desc'        => __( 'Input source', 'oca' ),
		'label_for'   => 'src_1',
		'option_name' => $options_name,
		'placeholder' => 'https://site.com'
	];
	add_settings_field( 'src_1_field', __( 'Input source', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_1', $field_params );

	$options_name = 'oca_options_2';
	//add validation
	register_setting( 'oca_group', $options_name, 'true_validate_settings' );

	// Add section
	add_settings_section( 'oca_section_2', __( 'Second Links', 'oca' ), '', $admin_page_name );

	// Add field 2
	$field_params = [
		'type'        => 'text',
		'id'          => 'text_2',
		'desc'        => __( 'Input Text Link', 'oca' ),
		'label_for'   => 'text_2',
		'option_name' => $options_name
	];
	add_settings_field( 'text_2_field', __( 'Input Text Link', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_2', $field_params );

	// Add field 1
	$field_params = [
		'type'        => 'url',
		'id'          => 'src_2',
		'desc'        => __( 'Input source', 'oca' ),
		'label_for'   => 'src_2',
		'option_name' => $options_name,
        'placeholder' => 'https://site.com'
	];
	add_settings_field( 'src_2_field', __( 'Input source', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_2', $field_params );

}

add_action( 'admin_init', 'oca_option_settings' );

/**
 * Template options
 *
 * @param $args
 */
function oca_option_display_settings( $args ) {
	$option_name = $args['option_name'];

	$id        = $args['id'];
	$type      = $args['type'];
	$desc      = $args['desc'];
	$label_for = $args['label_for'];
	$placeholder = isset($args['placeholder']) ? $args['placeholder'] : '';
	$o         = get_option( $option_name );

	switch ( $type ) {
		case 'text':
			$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
			echo "<input
			class='regular-text'
			type='text'
			id='$id'
			name='{$option_name}[$id]'
			placeholder='{$placeholder}'
			value='$o[$id]' />";
			echo ( $desc != '' ) ? "<br /><span class='description'>$desc</span>" : "";
			break;
		case 'url':
			$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
			echo "<input
			class='regular-text'
			type='url' id='$id'
			name='{$option_name}[$id]'
			placeholder='{$placeholder}'
			value='$o[$id]' />";
			echo ( $desc != '' ) ? "<br /><span class='description'>$desc</span>" : "";
			break;
	}
}

/**
 * Validate
 *
 * @param $input
 *
 * @return mixed
 */
function true_validate_settings( $input ) {
	$valid_input = [];

	foreach ( $input as $k => $v ) {
		$valid_input[ $k ] = trim( $v );
	}

	return $valid_input;
}

/**
 * one-click - pojo-accessibility Before button
 */
function pojo_toolbar_before_buttons_output() {
	$options = get_option( 'oca_access_options_1' );
	ob_start();
	?>
    <li class="pojo-a11y-toolbar-item">
        <a
                href="<?php echo $options['src_1']; ?>">
            <span class="pojo-a11y-toolbar-icon">
                <span class="dashicons dashicons-media-text"></span>
            </span>
            <span class="pojo-a11y-toolbar-text"><?php echo $options['text_1']; ?></span>
        </a>
    </li>
	<?php
	echo ob_get_clean();
}

add_action( 'pojo_a11y_toolbar_before_buttons', 'pojo_toolbar_before_buttons_output' );

/**
 * one-click - pojo-accessibility After button
 */
function pojo_toolbar_after_buttons_output() {
	$options = get_option( 'oca_options_2' );

	ob_start();
	?>
    <li class="pojo-a11y-toolbar-item">
        <a
                href="<?php echo $options['src_2']; ?>">
            <span class="pojo-a11y-toolbar-icon">
                <span class="dashicons dashicons-email-alt"></span>
            </span>
            <span class="pojo-a11y-toolbar-text"><?php echo $options['text_2']; ?></span>
        </a>
    </li>
	<?php
	echo ob_get_clean();
}

add_action( 'pojo_a11y_toolbar_after_buttons', 'pojo_toolbar_after_buttons_output' );
