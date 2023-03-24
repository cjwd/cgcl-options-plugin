<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://chinarajames.com
 * @since      1.0.0
 *
 * @package    Cgcl_Settings
 * @subpackage Cgcl_Settings/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cgcl_Settings
 * @subpackage Cgcl_Settings/admin
 * @author     Chinara James <cjwd@chinarajames.com>
 */
class Cgcl_Settings_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cgcl_Settings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cgcl_Settings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cgcl-settings-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cgcl_Settings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cgcl_Settings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cgcl-settings-admin.js', array( 'jquery' ), $this->version, false );


	}


	/**
	 * Settings Form
	 */
	function cgcl_options_page(  ) { 

		if(!current_user_can('manage_options')) {
			return;
		}

		// add error/update messages

		// check if the user have submitted the settings
		// WordPress will add the "settings-updated" $_GET parameter to the url
		if ( isset( $_GET['settings-updated'] ) ) {
			// add settings saved message with the class of "updated"
			add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'cgcl' ), 'updated' );
		}

		// show error/update messages
		settings_errors( 'wporg_messages' );

			?>
			<form action='options.php' method='post'>

				<h2>CGCL Settings</h2>

				<?php
				settings_fields( 'cgclOptions' );
				do_settings_sections( 'cgclOptions' );
				submit_button();
				?>

			</form>
			<?php

	}

	/**
	 * Add a custom page to the Wordpress menu
	 */
	function add_admin_menu(  ) { 

		add_menu_page( 
			'CGCL Options', /* Page Title */
			'CGCL Options', /* Menu Title */
			'manage_options', /* Role Capability */
			'cgcl-options', /* Menu Slug */
			array($this,'cgcl_options_page'), /* Callback Function */
			'dashicon-admin-settings', /* Icon */
			'75 '/* Position */
		);

	}

	/**
	 * Settings section description
	 */
	function cgcl_settings_section_callback(  ) { 
		// echo __( 'Select the pages to password protect', 'cgcl' );
	}

	/**
	 * Register settings sections and fields
	 */
	function settings_init(  ) { 

		// register_setting('cgclOptions', 'cgcl_hsse_page' );
		// register_setting('cgclOptions', 'cgcl_myaccount_page');
		register_setting('cgclOptions', 'cg_hsse_protected_pages');

		add_settings_section(
			'cgcl_cgclOptions_section', 
			__( 'HSSE Orientation Settings', 'cgcl' ), 
			array($this, 'cgcl_settings_section_callback'), 
			'cgclOptions'
		);

		add_settings_field(
			'cg_hsse_restrict_pages', // Field ID
			__('Select pages to protect:', 'cgcl'), // Field title
			array($this,'hsse_restrict_pages_callback'), // Callback function to render the field
			'cgclOptions', // Page slug
			'cgcl_cgclOptions_section' // Section ID
		);

		// HSSE Orientation Page
		// add_settings_field( 
		// 	'cgcl_select_field_pages', 
		// 	__( 'HSSE Orientation Page', 'cgcl' ), 
		// 	array($this,'cgcl_select_hsse_page_render'),
		// 	'cgclOptions', 
		// 	'cgcl_cgclOptions_section' 
		// );

		// HSSE My Account Page
		// add_settings_field( 
		// 	'cgcl_select_myaccount_page', 
		// 	__( 'HSSE My Account Page', 'cgcl' ), 
		// 	array($this,'cgcl_select_myaccount_render'),
		// 	'cgclOptions', 
		// 	'cgcl_cgclOptions_section' 
		// );

		


	}


	// Callback function to render the field
	function hsse_restrict_pages_callback() {
		$pages = get_pages();
		$protected_pages = get_option('cg_hsse_protected_pages', array());
		$page_ids = explode(',', $protected_pages);

		// var_dump($protected_pages);
		// var_dump($page_ids);
		// die;

		foreach ($pages as $page) {
			echo '<label>';
			echo '<input type="checkbox" name="hsse_protected_pages[]" value="' . $page->ID . '" ' . checked(in_array($page->ID, $page_ids), true, false) . '>';
			echo ' ' . $page->post_title;
			echo '</label><br>';
		}
	}

	/**
	 * Orientation page select field
	 */
	function cgcl_select_hsse_page_render(  ) { 
		$pages = get_pages();
		$option = get_option( 'cgcl_hsse_page' );
		?>
		<select name='cgcl_hsse_page[cgcl_select_field_pages]'>
			<?php foreach($pages as $page) : ?>
				<option value="<?= $page->ID; ?>" <?php selected( $option['cgcl_select_field_pages'], $page->ID ); ?>><?= $page->post_title; ?></option>
			<?php endforeach; ?>
		</select>

	<?php
	}

	/**
	 * My Account page select field
	 */
	function cgcl_select_myaccount_render(  ) { 
		$pages = get_pages();
		$option = get_option('cgcl_myaccount_page');
		?>
		<select name='cgcl_myaccount_page[cgcl_select_myaccount_page]'>
			<?php foreach($pages as $page) : ?>
				<option value="<?= $page->ID; ?>" <?php selected( $option['cgcl_select_myaccount_page'], $page->ID ); ?>><?= $page->post_title; ?></option>
			<?php endforeach; ?>
		</select>

	<?php
	}

	/**
	 * Display custom user fields on user profle page
	 */
	function display_custom_user_fields($user) {
		$company = get_user_meta($user->ID, 'hsse_user_company', true);
		$phone = get_user_meta($user->ID, 'hsse_user_phone', true);
		$id_num = get_user_meta($user->ID, 'hsse_user_id', true);
		$id_type = get_user_meta($user->ID, 'hsse_user_id_type', true);
		$id_image = get_user_meta($user->ID, 'hsse_user_id_image', true);
		$plea_id = get_user_meta($user->ID, 'hsse_user_plea_id', true);
		$plea_image = get_user_meta($user->ID, 'hsse_user_plea_image', true);
		$dtest_date = get_user_meta($user->ID, 'hsse_user_drugtest_date', true);
		$dtest_image = get_user_meta($user->ID, 'hsse_user_drugtest_image', true);
		$coc_id = get_user_meta($user->ID, 'hsse_user_coc_id', true);
		$coc_image = get_user_meta($user->ID, 'hsse_user_coc_image', true);
		?>
			<h3>HSSE Orientation User</h3>
			<table class="form-table" role="presentation">
				<tr>
					<th><label for="">Company Name</label></th>
					<td>
						<input type="text" name="hsse_user_company" id="hsse_user_company" value="<?= esc_attr($company);?>" class="regular-text">
					</td>
				</tr>
				<!-- Phone Number -->
				<tr>
					<th><label for="hsse_user_phone">Phone</label></th>
					<td>
						<input type="text" name="hsse_user_phone" id="hsse_user_phone" value="<?= esc_attr($phone);?>" class="regular-text">
					</td>
				</tr>
				<!-- ID Type -->
				<tr>
					<th><label for="">Identification Type</label></th>
					<td>
						<fieldset>
							<legend class="screen-reader-text"><span>Select your preferred ID</span></legend>
							<input type="radio" name="hsse_user_id_type" id="hsse_user_id_ni" value="National ID" <?php checked($id_type,'National ID'); ?>>
							<label for="hsse_user_id_ni">National ID</label>
							<input type="radio" name="hsse_user_id_type" id="hsse_user_id_dp" value="Driver's Permit" <?php checked($id_type,'Driver\'s Permit'); ?>>
							<label for="hsse_user_id_dp">Driver's Permit</label>
							<input type="radio" name="hsse_user_id_type" id="hsse_user_id_pp" value="Passport" <?php checked($id_type,'Passport'); ?>>
							<label for="hsse_user_id_pp">Passport</label>
						</fieldset>
					</td>
				</tr>
				<!-- ID Number -->
				<tr>
					<th><label for="">Identification Number</label></th>
					<td>
						<input type="text" name="hsse_user_id" id="hsse_user_id" value="<?= esc_attr($id_num);?>" class="regular-text">
					</td>
				</tr>
				<!-- ID Image -->
				<tr>
					<th><label for="">Identification Image</label></th>
					<td>
						<img src="<?= $id_image; ?>" width="150" height="150" alt="">
					</td>
					<!-- <td>
						<input type="file" name="hsse_user_id_image" id="hsse_user_id_image">
					</td> -->
				</tr>
				<!-- PLEA ID -->
				<tr>
					<th><label for="">PLEA ID Number</label></th>
					<td>
						<input type="text" name="hsse_user_plea_id" id="hsse_user_plea_id" value="<?= esc_attr($plea_id);?>" class="regular-text">
					</td>
				</tr>
				<!-- PLEA Image -->
				<tr>
					<th><label for="">PlEA Card</label></th>
					<td>
						<img src="<?= $plea_image; ?>" width="150" height="150" alt="">
					</td>
					<!-- <td>
						<input type="file" name="hsse_user_plea_image" id="hsse_user_plea_image">
					</td> -->
				</tr>
				<!-- Drug Test Date -->
				<tr>
					<th><label for="">Drug Test Date</label></th>
					<td>
						<?= $dtest_date; ?>
						<input type="date" name="hsse_user_drugtest_date" id="hsse_user_drugtest_date" value="<?= $dtest_date; ?>">
					</td>
				</tr>
				<!-- Drug Test Image -->
				<tr>
					<th><label for="">Drug Test Image</label></th>
					<td>
						<img src="<?= $dtest_image; ?>" width="150" height="150" alt="">
					</td>
					<!-- <td>
						<input type="file" name="hsse_user_drugtest_image" id="hsse_user_drugtest_image">
					</td> -->
				</tr>
				<!-- Certificate of Character (COC) ID -->
				<tr>
					<th><label for="">Certificate of Character Number</label></th>
					<td>
						<input type="text" name="hsse_user_coc_id" id="hsse_user_coc_id" value="<?= esc_attr($coc_id);?>" class="regular-text">
					</td>
				</tr>
				<!-- Certificate of Character (COC) Image -->
				<tr>
					<th><label for="">Certificate of Character Image</label></th>
					<td>
						<img src="<?= $coc_image; ?>" width="150" height="150" alt="">
					</td>
					<!-- <td>
						<input type="file" name="hsse_user_coc_image" id="hsse_user_coc_image">
					</td> -->
				</tr>
			</table>
		<?php

	}

	/**
	 * Save user custom fields values
	 */
	function save_custom_user_fields_data($user_id) {
		if(!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-user_'.$user_id)) {
			return;
		}

		if(!current_user_can('edit_user', $user_id)) {
			return;
		}

		update_user_meta($user_id, 'hsse_user_company', $_POST['hsse_user_company']);
		update_user_meta($user_id, 'hsse_user_phone', $_POST['hsse_user_phone']);
		update_user_meta($user_id, 'hsse_user_id', $_POST['hsse_user_id']);

		if(!empty($_POST['hsse_user_id_type'])) {
			update_user_meta($user_id, 'hsse_user_id_type', $_POST['hsse_user_id_type']);
		}
		update_user_meta($user_id, 'hsse_user_plea_id', $_POST['hsse_user_plea_id']);
		if(!empty($_POST['hsse_user_drugtest_date'])) {
			update_user_meta($user_id, 'hsse_user_drugtest_date', $_POST['hsse_user_drugtest_date']);
		}
		
		update_user_meta($user_id, 'hsse_user_coc_id', $_POST['hsse_user_coc_id']);
		
	}


}
