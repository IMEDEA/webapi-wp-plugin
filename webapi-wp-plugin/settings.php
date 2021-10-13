<?php
/** 
 * Settings page 
 */
 
add_action( 'admin_menu', 'iapi_add_settings_page' );
add_action( 'admin_init', 'iapi_register_settings' );


function iapi_add_settings_page() {
  add_options_page(
    'IMEDEA API Plugin Settings', //page title
    'IMEDEA API',                 //menu title
    'manage_options',             //capabilities for this menu
    'iapi_plugin_menu',  //name to refer this menu (unique)
    'iapi_settings_form' //function to display the content
  );
}

function iapi_settings_form() {
?>
  <h2>IMEDEA API Plugin Settings</h2>
  <form action="options.php" method="post">
    <?php 
    settings_fields( 'iapi_plugin_options' );
    do_settings_sections( 'iapi_plugin' );
    ?>
    <input
      type="submit"
      name="submit"
      class="button button-primary"
      value="<?php esc_attr_e( 'Save' ); ?>"
    />
  </form>
<?php
}

function iapi_register_settings() {
	register_setting( 'iapi_plugin_options', 'iapi_plugin_options', 'iapi_plugin_options_validate');
	add_settings_section( 'api_settings', 'API Settings', 'iapi_plugin_section_text', 'iapi_plugin' );
	add_settings_field( 'imedea_webapi_base_url', 'IMEDEA WEBAPI base url', 'imedea_webapi_base_url', 'iapi_plugin', 'api_settings' );
	add_settings_field( 'pub_detail_page_id', 'Publication details page ID', 'pub_detail_page_id', 'iapi_plugin', 'api_settings' );
	add_settings_field( 'staff_detail_page_id', 'Staff details page ID', 'staff_detail_page_id', 'iapi_plugin', 'api_settings' );
	add_settings_field( 'webapi_pagination_length', 'Pagination length', 'webapi_pagination_length', 'iapi_plugin', 'api_settings' );
}

function iapi_plugin_options_validate( $input ) {
	$newinput['webapi_url'] = trim( $input['webapi_url'] );
	$newinput['pub_detail_page_id'] = trim( $input['pub_detail_page_id'] );
	$newinput['staff_detail_page_id'] = trim( $input['staff_detail_page_id'] );
	$newinput['webapi_pagination_length'] = trim( $input['webapi_pagination_length'] );
	return $newinput;
}

function imedea_webapi_base_url() {
    $options = get_option( 'iapi_plugin_options' );
    echo "<input id='imedea_webapi_base_url' name='iapi_plugin_options[webapi_url]' type='text' value='" . esc_attr( $options['webapi_url'] ) . "' />";
}

function pub_detail_page_id() {
    $options = get_option( 'iapi_plugin_options' );
    echo "<input id='pub_detail_page_id' name='iapi_plugin_options[pub_detail_page_id]' type='text' value='" . esc_attr( $options['pub_detail_page_id'] ) . "' />";
}

function staff_detail_page_id() {
    $options = get_option( 'iapi_plugin_options' );
    echo "<input id='staffdetail_page_id' name='iapi_plugin_options[staff_detail_page_id]' type='text' value='" . esc_attr( $options['staff_detail_page_id'] ) . "' />";
}

function webapi_pagination_length() {
    $options = get_option( 'iapi_plugin_options' );
    echo "<input id='webapi_pagination_length' name='iapi_plugin_options[webapi_pagination_length]' type='text' value='" . esc_attr( $options['webapi_pagination_length'] ) . "' />";
}

?>