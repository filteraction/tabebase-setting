<?php
/*Plugin Name: Theme Option*/
class Option{
	private $general;
	private $header;
	private $footer;
	private $social;
	public function __construct(){
		add_action('admin_menu', array($this,'create_admin_menu'));
		add_action('admin_init', array($this, 'create_settings'));	
	}	
	public function create_admin_menu(){
		$page_title = 'Theme Option';
		$menu_title = 'Theme Option';
		$capability = 'manage_options';
		$slug = 'option';
		$callback = array($this, 'my_new_option_page');
		$icon = 'dashicons-admin-settings';
		$position = 2;
		add_menu_page($page_title,$menu_title,$capability,$slug,$callback,$icon,$position);
		add_submenu_page( $slug, 'Child Option', 'Child Option', $capability, 'child_option', array($this,'create_child_option_page'));
	}	
	public function my_new_option_page(){ 
		$GeneralTab = ( ! isset( $_GET['tab'] ) || isset( $_GET['tab'] ) && 'header' != $_GET['tab']  && 'footer' != $_GET['tab']  && 'social' != $_GET['tab'] ) ? true : false;
		$HeaderTab= (isset($_GET['tab']) && 'header' == $_GET['tab']) ? true : false;
		$FooterTab= (isset($_GET['tab']) && 'footer' == $_GET['tab']) ? true : false;
		$SocialTab= (isset($_GET['tab']) && 'social' == $_GET['tab']) ? true : false;
		?>
		<div class="wrap">
			<h1><b>My New Option Page</b></h1>
			<?php settings_errors(); ?>
			<h2 class="nav-tab-wrapper">
				<a href="<?php echo admin_url( 'admin.php?page=option' ); ?>" class="nav-tab <?php if( $GeneralTab ) echo 'nav-tab-active'; ?>">General</a>
				<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'header' ), admin_url( 'admin.php?page=option' ) ) ); ?>" class="nav-tab <?php if( $HeaderTab ) echo 'nav-tab-active'; ?> ">Header</a>
				<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'footer' ), admin_url( 'admin.php?page=option' ) ) ); ?>" class="nav-tab <?php if( $FooterTab ) echo 'nav-tab-active'; ?>">Footer</a>
				<a href="<?php echo esc_url( add_query_arg( array( 'tab' => 'social' ), admin_url( 'admin.php?page=option' ) ) ); ?>" class="nav-tab <?php if( $SocialTab ) echo 'nav-tab-active'; ?>">Social</a>
			</h2>
			 <form method="post" action="options.php">
			 	<?php 
				 if($HeaderTab) { 					
					settings_fields( 'option_settion_social' );
					do_settings_sections( 'option_settion_header' );
					submit_button();				
				} elseif($FooterTab) {
					settings_fields( 'footer_fields' );
					do_settings_sections( 'option_settion_footer' );
					submit_button();
				} elseif($SocialTab) {
					settings_fields( 'soical_fields' );
					do_settings_sections( 'option_settion_social' );
					submit_button();
				}else { 
					settings_fields( 'option_settion_general' );//same as register_setting()
					do_settings_sections( 'option_settion_general' );
					submit_button(); 
				} ?>
			</form>

		</div>
	<?php 	
	}
	public function create_child_option_page(){
		$args = array(
			array(
				'id' => 'general_section',
				'title' => 'General Settings',
				'callback' => array(),
				'page' => 'option_settion_general',
			),
			array(
				'id' => 'header_section',
				'title' => 'Header Settings',
				'callback' => array(),
				'page' => 'option_settion_header',
			),
			array(
				'id' => 'footer_section',
				'title' => 'Footer Settings',
				'callback' => array(),
				'page' => 'option_setting_footer',
			),
			array(
				'id' => 'social_section',
				'title' => 'Social Settings',
				'callback' => array(),
				'page' => 'option_setting_social',
			),
			);
		foreach ($args as $arg) {
			echo '<pre>';
		print_r($arg['id']);
		}
		
	}	
	public function create_settings(){
		require_once('option-fields.php');
	}
}//end class
new Option();