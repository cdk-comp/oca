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

function oca_load_plugin_textdomain() {
	load_plugin_textdomain(
	        'oca',
            false,
            dirname( plugin_basename( __FILE__ ) ) . '/lang/'
    );
}

add_action( 'plugins_loaded', 'oca_load_plugin_textdomain' );

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
                <?php echo icon1(); ?>
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
                <?php echo icon2(); ?>
            </span>
            <span class="pojo-a11y-toolbar-text"><?php echo $options['text_2']; ?></span>
        </a>
    </li>
	<?php
	echo ob_get_clean();
}

add_action( 'pojo_a11y_toolbar_after_buttons', 'pojo_toolbar_after_buttons_output' );

function icon1() {

	return '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 482.8 482.8" style="enable-background:new 0 0 482.8 482.8;" xml:space="preserve">
<g>
	<g>
		<path d="M431.3,114.5c-1.4-3.3-3.5-6.4-6.1-8.9l-101.9-98c-2.5-2.4-5.4-4.3-8.6-5.6l0,0c-3.2-1.3-6.6-2-10.1-2H109
			C76,0,49.3,26.7,49.3,59.7v363.4c0,33,26.7,59.7,59.7,59.7h264.8c33,0,59.7-26.7,59.7-59.7v-298
			C433.5,121.5,432.7,117.9,431.3,114.5z M405.3,423.2c0,17.4-14.2,31.5-31.5,31.5H109c-17.4,0-31.5-14.2-31.5-31.5V59.8
			c0-17.4,14.1-31.5,31.5-31.5h186.7V74c0,32.9,26.8,59.7,59.7,59.7h49.9V423.2z"/>
		<path d="M110,241.5L110,241.5c0,7.7,6.3,14.1,14.1,14.1h237.1c7.7,0,14.1-6.3,14.1-14.1l0,0c0-7.7-6.3-14.1-14.1-14.1H124.1
			C116.3,227.4,110,233.8,110,241.5z"/>
		<path d="M361.1,283.7h-237c-7.7,0-14.1,6.3-14.1,14.1l0,0c0,7.7,6.3,14.1,14.1,14.1h237.1c7.7,0,14.1-6.3,14.1-14.1l0,0
			C375.2,290,368.9,283.7,361.1,283.7z"/>
		<path d="M361.1,340h-237c-7.7,0-14.1,6.3-14.1,14.1l0,0c0,7.7,6.3,14.1,14.1,14.1h237.1c7.7,0,14.1-6.3,14.1-14.1l0,0
			C375.2,346.3,368.9,340,361.1,340z"/>
		<path d="M255.1,396.2H122.9c-7.7,0-14.1,6.3-14.1,14.1s6.3,14.1,14.1,14.1h132.3c7.7,0,14.1-6.3,14.1-14.1
			S262.9,396.2,255.1,396.2z"/>
		<path d="M172.2,201.3h61.9c-0.3-25.7,0.7-39.5-16-45.6c-15.2-5.8-24.2-11.7-24.2-11.7l-11.7,36.9l-1.6,5l-5.2-14.8
			c12-16.8-0.9-17.6-3.2-17.6l0,0l0,0l0,0l0,0l0,0l0,0c-2.2,0-15.2,0.8-3.2,17.6l-5.2,14.8l-1.6-5L150.4,144c0,0-9.1,5.9-24.2,11.7
			c-16.7,6.1-15.7,19.8-16,45.6H169L172.2,201.3L172.2,201.3z"/>
		<path d="M145.8,120.6c1.3,8.7,8,19.7,19,23.6c4.5,1.6,9.5,1.6,14,0c10.8-3.9,17.7-14.9,19.1-23.6c1.5-0.1,3.4-2.2,5.4-9.5
			c2.8-10-0.2-11.5-2.7-11.2c0.5-1.4,0.9-2.7,1.1-4.1c4.3-25.8-8.4-26.7-8.4-26.7s-2.1-4.1-7.7-7.1c-3.7-2.2-8.9-3.9-15.7-3.3
			c-2.2,0.1-4.3,0.5-6.3,1.2l0,0c-2.5,0.8-4.8,2.1-6.9,3.5c-2.6,1.6-5,3.6-7.1,5.9c-3.4,3.4-6.4,7.9-7.7,13.5
			c-1.1,4.2-0.8,8.5,0.1,13.1l0,0c0.2,1.4,0.6,2.7,1.1,4.1c-2.5-0.2-5.5,1.2-2.7,11.2C142.5,118.4,144.4,120.5,145.8,120.6z"/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>';
}

function icon2() {
    return '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 483.3 483.3" style="enable-background:new 0 0 483.3 483.3;" xml:space="preserve">
<g>
	<g>
		<path d="M424.3,57.75H59.1c-32.6,0-59.1,26.5-59.1,59.1v249.6c0,32.6,26.5,59.1,59.1,59.1h365.1c32.6,0,59.1-26.5,59.1-59.1
			v-249.5C483.4,84.35,456.9,57.75,424.3,57.75z M456.4,366.45c0,17.7-14.4,32.1-32.1,32.1H59.1c-17.7,0-32.1-14.4-32.1-32.1v-249.5
			c0-17.7,14.4-32.1,32.1-32.1h365.1c17.7,0,32.1,14.4,32.1,32.1v249.5H456.4z"/>
		<path d="M304.8,238.55l118.2-106c5.5-5,6-13.5,1-19.1c-5-5.5-13.5-6-19.1-1l-163,146.3l-31.8-28.4c-0.1-0.1-0.2-0.2-0.2-0.3
			c-0.7-0.7-1.4-1.3-2.2-1.9L78.3,112.35c-5.6-5-14.1-4.5-19.1,1.1c-5,5.6-4.5,14.1,1.1,19.1l119.6,106.9L60.8,350.95
			c-5.4,5.1-5.7,13.6-0.6,19.1c2.7,2.8,6.3,4.3,9.9,4.3c3.3,0,6.6-1.2,9.2-3.6l120.9-113.1l32.8,29.3c2.6,2.3,5.8,3.4,9,3.4
			c3.2,0,6.5-1.2,9-3.5l33.7-30.2l120.2,114.2c2.6,2.5,6,3.7,9.3,3.7c3.6,0,7.1-1.4,9.8-4.2c5.1-5.4,4.9-14-0.5-19.1L304.8,238.55z"
			/>
	</g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
<g>
</g>
</svg>
';
}

