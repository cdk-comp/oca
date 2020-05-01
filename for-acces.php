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

	// Add field 1
	$field_params = [
		'type'        => 'text',
		'id'          => 'src_1',
		'desc'        => __( 'Input source', 'oca' ),
		'label_for'   => 'src_1',
		'option_name' => $options_name
	];
	add_settings_field( 'src_1_field', __( 'Input source', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_1', $field_params );

	// Add field 2
	$field_params = [
		'type'        => 'text',
		'id'          => 'text_1',
		'desc'        => __( 'Input Text Link', 'oca' ),
		'label_for'   => 'text_1',
		'option_name' => $options_name
	];
	add_settings_field( 'text_1_field', __( 'Input Text Link', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_1', $field_params );

	$options_name = 'oca_options_2';
	//add validation
	register_setting( 'oca_group', $options_name, 'true_validate_settings' );

	// Add section
	add_settings_section( 'oca_section_2', __( 'Second Links', 'oca' ), '', $admin_page_name );

	// Add field 1
	$field_params = [
		'type'        => 'text',
		'id'          => 'src_2',
		'desc'        => __( 'Input source', 'oca' ),
		'label_for'   => 'src_2',
		'option_name' => $options_name
	];
	add_settings_field( 'src_2_field', __( 'Input source', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_2', $field_params );

	// Add field 2
	$field_params = [
		'type'        => 'text',
		'id'          => 'text_2',
		'desc'        => __( 'Input Text Link', 'oca' ),
		'label_for'   => 'text_2',
		'option_name' => $options_name
	];
	add_settings_field( 'text_2_field', __( 'Input Text Link', 'oca' ), 'oca_option_display_settings', $admin_page_name, 'oca_section_2', $field_params );

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
	$o         = get_option( $option_name );

	switch ( $type ) {
		case 'text':
			$o[ $id ] = esc_attr( stripslashes( $o[ $id ] ) );
			echo "<input class='regular-text' type='text' id='$id' name='{$option_name}[$id]' value='$o[$id]' />";
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
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" viewBox="0 0 448 448">
                    <path fill="currentColor"
                          d="M256 200v16c0 4.25-3.75 8-8 8h-56v56c0 4.25-3.75 8-8 8h-16c-4.25 0-8-3.75-8-8v-56h-56c-4.25 0-8-3.75-8-8v-16c0-4.25 3.75-8 8-8h56v-56c0-4.25 3.75-8 8-8h16c4.25 0 8 3.75 8 8v56h56c4.25 0 8 3.75 8 8zM288 208c0-61.75-50.25-112-112-112s-112 50.25-112 112 50.25 112 112 112 112-50.25 112-112zM416 416c0 17.75-14.25 32-32 32-8.5 0-16.75-3.5-22.5-9.5l-85.75-85.5c-29.25 20.25-64.25 31-99.75 31-97.25 0-176-78.75-176-176s78.75-176 176-176 176 78.75 176 176c0 35.5-10.75 70.5-31 99.75l85.75 85.75c5.75 5.75 9.25 14 9.25 22.5z" "="">
                    </path>
                </svg>
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
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="1em" viewBox="0 0 448 448">
                    <path fill="currentColor"
                          d="M256 200v16c0 4.25-3.75 8-8 8h-56v56c0 4.25-3.75 8-8 8h-16c-4.25 0-8-3.75-8-8v-56h-56c-4.25 0-8-3.75-8-8v-16c0-4.25 3.75-8 8-8h56v-56c0-4.25 3.75-8 8-8h16c4.25 0 8 3.75 8 8v56h56c4.25 0 8 3.75 8 8zM288 208c0-61.75-50.25-112-112-112s-112 50.25-112 112 50.25 112 112 112 112-50.25 112-112zM416 416c0 17.75-14.25 32-32 32-8.5 0-16.75-3.5-22.5-9.5l-85.75-85.5c-29.25 20.25-64.25 31-99.75 31-97.25 0-176-78.75-176-176s78.75-176 176-176 176 78.75 176 176c0 35.5-10.75 70.5-31 99.75l85.75 85.75c5.75 5.75 9.25 14 9.25 22.5z" "="">
                    </path>
                </svg>
            </span>
            <span class="pojo-a11y-toolbar-text"><?php echo $options['text_2']; ?></span>
        </a>
    </li>
	<?php
	echo ob_get_clean();
}

add_action( 'pojo_a11y_toolbar_after_buttons', 'pojo_toolbar_after_buttons_output' );
