<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://chinarajames.com
 * @since      1.0.0
 *
 * @package    Cgcl_Settings
 * @subpackage Cgcl_Settings/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cgcl_Settings
 * @subpackage Cgcl_Settings/public
 * @author     Chinara James <cjwd@chinarajames.com>
 */
class Cgcl_Settings_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cgcl-settings-public.css', array(), $this->version, 'all' );

		if(is_page(611)) {
			wp_enqueue_style($this->plugin_name);
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cgcl_Settings_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cgcl_Settings_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cgcl-settings-public.js', array( 'jquery' ), $this->version, false );

		wp_enqueue_script( $this->plugin_name );

		/**
		 * Pass the HSSE member user meta to a javascript file
		 * For passing the ID and document images urls 
		 */
		$cgcl_nonce = wp_create_nonce('cgclff');
		$ajax_url = admin_url('admin_ajax.php');
		$user_id = get_current_user_id();
		$plea_img = get_user_meta($user_id, 'hsse_user_plea_image', true);
		$drugtest_img = get_user_meta($user_id, 'hsse_user_drugtest_image', true);
		$coc_img = get_user_meta($user_id, 'hsse_user_coc_image', true);
		$id_img = get_user_meta($user_id, 'hsse_id_upload', true);

		// Pass ajax object to the javascript file
		// Add nonce value to this object so that we can access them in the javascript file
		wp_localize_script( $this->plugin_name, 'cgcl_ajax_object', array(
			'ajax_url' =>  $ajax_url,
			'cgcl_nonce' => $cgcl_nonce,
			'plea_img' => $plea_img,
			'drugtest_img' => $drugtest_img,
			'coc_img' => $coc_img,
			'id_img' => $id_img
		));

	}

	/**
	 * Retrict Access to HSSE Pages
	 */
	function restrict_hsse_pages() {
		$protected_pages = get_option('hsse_protected_pages');
		$login_page_id = get_option('hsse_login_page');

		if(!empty($protected_pages)) {
			 // If the user is not logged in and is accessing a protected page, redirect them to the login page
			if ( ! is_user_logged_in() && ! empty( $login_page_id ) && in_array( get_queried_object_id(), explode( ',', $protected_pages ) ) ) {
				wp_redirect( get_permalink( $login_page_id ) );
				exit;
			}
			// $page_ids = explode(',', $protected_pages);

			// foreach($page_ids as $page_id) {
			// 	if(!is_user_logged_in() && is_page($page_id) ) {
			// 		wp_redirect(get_permalink($login_page_id));
			// 	}
			// }
		}
		
	}

	/**
	 * Password Protect Page
	 * @todo Add a setting for user to select the login page
	 * action pre_get_posts
	 */
	function password_protect_page() {
		$page = get_option('cgcl_hsse_page');

		if(is_page($page) && !is_user_logged_in()) {
			/* Redirect to the login page */
			wp_redirect('/hsse-login/');
			exit();
		}
		
	}

	/**
	 * Password protect my account page
	 * action pre_get_posts
	 */
	function password_protect_myaccount() {
		$page = get_option('cgcl_myaccount_page');
		
		if(is_page($page) && !is_user_logged_in()) {
			/* Redirect to the login page */
			wp_redirect('/hsse-login/');
			exit();
		}
	}

	/**
	 * Password protect course single posts
	 * The course CPT is set to have no archive
	 * action wp
	 */
	function password_protect_course() {

		if('course' === get_post_type() && !is_user_logged_in()) {
			wp_redirect('/hsse-login/');
			exit();
		}
	}

	/**
	 * Shortcode for displaying user meta data
	 */
	function cgcl_usermeta_shortcode() {

		if(!is_user_logged_in()) {
			return;
		}
		
		$user_id = get_current_user_id();
		
		$company = get_user_meta($user_id, 'hsse_user_company', true);
		$id_num = get_user_meta($user_id, 'hsse_user_id', true);
		$id_type = get_user_meta($user_id, 'hsse_user_id_type', true);
		$plea_num = get_user_meta($user_id, 'hsse_user_plea_id', true);
		$coc_num = get_user_meta($user_id, 'hsse_user_coc_id', true);
		$dtest_date = get_user_meta($user_id, 'hsse_user_drugtest_date', true);
		$sub_date = get_user_meta($user_id, 'hsse_user_submitted_on', true);

		$usermeta = array(
			'Company' => $company,
			'ID Type' => $id_type,
			'ID Number' => $id_num,
			'PLEA Number' => $plea_num,
			'COC Number' => $coc_num,
			'Drug Test Date' => $dtest_date,
			'Submission Date' => $sub_date,
			'Expiration Date' => $this->add_one_year_to_date($sub_date),
		);

		ob_start();
		?>
		<ul class="hsse-user-details">
		<?php foreach($usermeta as $key => $data) : ?>
			<li><?php echo '<strong>' . $key .'</strong>' . ': ' .  $data; ?></li>
		<?php endforeach; ?>
		</ul>
		<?php

		return ob_get_clean();
		
	}

	/**
	 * Print button shortcode
	 */
	function print_btn_shortcode($atts) {
		return '<a id="print-page" class="kt-button button kt-btn-style-basic kt-btn-has-text-true" href="javascript:window.print()"><span class="kt-btn-inner-text"><i class="fas fa-print"></i> Print This Page</span></a>';
	}

	/**
	 * Add 1 year to a given date.
	 *
	 * @param string $date The date to add 1 year to, in Y-m-d H:i:s format.
	 * @return string The updated date in Y-m-d H:i:s format.
	 */
	function add_one_year_to_date( $date ) {
		// Convert the date to a Unix timestamp.
		$timestamp = strtotime( $date );

		// Add 1 year to the timestamp.
		$new_timestamp = strtotime( '+1 year', $timestamp );

		// Convert the new timestamp back to a date string.
		$new_date = date( 'Y-m-d', $new_timestamp );

		// Return the updated date.
		return $new_date;
	}


}