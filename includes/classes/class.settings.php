<?php

/* 	Add Settings Page in WP Admin - Settings
*	Configuration Options for BBPress Support Extension
*/

 if ( ! defined( 'ABSPATH' ) ) exit;

class BBPSE_Settings{

	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new BBPSE_Settings();
        return self::$instance;
    }

	public function __construct(){
		$this->option = 'bbpse';
		add_action( 'admin_menu', array($this,'admin_settings'));
		add_action('admin_enqueue_scripts',array($this,'enqueue_admin_scripts'));
	}

	function admin_settings(){
		add_menu_page(_x('BBPress Support Extension settings','Page title for the BBPSE page','bbpse'),_x('BBP Support Ext.','Menu title in Settings page','bbpse'),'install_plugins','bbpse',array($this,'dashboard'),'dashicons-sos');
		do_action('bbpse_admin_menu');
		add_submenu_page( 'bbpse', _x('Settings - BBPress Support Extension settings','Page title for the BBPSE page','bbpse'),_x('Settings','Menu title in Settings page','bbpse'),'install_plugins', 'bbpse_settings', array($this,'settings') );
		add_submenu_page( 'bbpse', _x('Reports - BBPress Support Extension settings','Page title for the BBPSE page','bbpse'),_x('Reports','Menu title in Settings page','bbpse'),'install_plugins', 'bbpse_reports', array($this,'reports') );
		add_submenu_page( 'bbpse', _x('AddOns - BBPress Support Extension settings','Page title for the BBPSE page','bbpse'),_x('AddOns','Menu title in Settings page','bbpse'),'install_plugins', 'bbpse_addons', array($this,'addons') );

	}

	function get(){

		if(!empty($this->settings))
			return $this->settings;

		$defaults = array(
			'staff' => '',
		);

		$options = get_option($this->option);
		$this->settings = wp_parse_args( $options, $defaults );
	}

	function dashboard(){

	}


	function reports(){

	}

	function settings(){
		$this->get();
		$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general';
		$this->settings_tabs($tab);
		$this->$tab();
	}

	function settings_tabs( $current = 'general' ) {
		$tabs = array( 
	    		'general'  => _x('General','Tab name in Settings','bbpse'), 
	    		'statuses' => _x('Statuses','Tab name in Settings','bbpse'), 
	    		'announcements' => _x('Announcements','Tab name in Settings','bbpse'), 
    		);
	    echo '<div id="icon-themes" class="icon32"><br></div>';
	    echo '<h2 class="nav nav-tab-wrapper">';
	    foreach( $tabs as $tab => $name ){
	        $class = ( $tab == $current ) ? ' nav-tab-active' : '';
	        echo "<a class='nav-tab$class' href='?page=bbpse_settings&tab=$tab'>$name</a>";

	    }
	    echo '</h2>';
	    if(isset($_POST['save'])){
	    	echo 'HAN BHAI';
	    	$this->save();
	    }
	}

	function enqueue_admin_scripts($hook){
		if(in_array($hook,array('toplevel_page_bbpse','bbp-support-ext_page_bbpse_settings','bbp-support-ext_page_bbpse_reports','bbp-support-ext_page_bbpse_addons'))){
			wp_enqueue_style('bbpse_admin_css',plugins_url('../assets/css/admin.css',__FILE__));
			wp_enqueue_script('bbpse_admin_css',plugins_url('../assets/js/admin.js',__FILE__));
		}
	}

	function general(){
		echo '<h3>'.__('General Settings','bbpse').'</h3>';
	
		$settings= apply_filters('bbpse_admin_general', array(
				array(
					'label' => __('Support Staff','bbpse' ),
					'name' =>'support_staff',
					'type' => 'select',
					'options'=> array(
						'' => __('All Admins','bbpse' ),
						'mods' => __('All Moderators','bbpse' ),
						'both' => __('All Admins and Moderators','bbpse' ),
						),
					'desc' => __('Set Support forum staff criteria','bbpse')
				),
			));

		$this->generate_form('general',$settings);
	}

	function statuses(){
		echo '<h3>'.__('Statuses','bbpse').'</h3>';
	
		$statuses=apply_filters('bbpse_admin_statuses',array(
				array(
					'label' => __('Forum Statuses','bbpse' ),
					'name' =>'forum_statuses',
					'type' => 'statuses',
					'dom'=> '<input type="text" rel-name="forum_status[\'status_label\'][]" placeholder="'._x('Enter custom forum status','bbpse').'"/><input type="radio" rel-name="forum_status[\'status_default\']"/>',
					'desc' => __('Define custom forum statuses','bbpse')
				),
				array(
					'label' => __('Topic Statuses','bbpse' ),
					'name' =>'topic_statuses',
					'type' => 'statuses',
					'desc' => __('Define custom forum statuses','bbpse')
				),
				array(
					'label' => __('Reply Statuses','bbpse' ),
					'name' =>'reply_statuses',
					'type' => 'statuses',
					'desc' => __('Define custom forum statuses','bbpse')
				),
			));

		$this->generate_form('stauses',$statuses);
	}

	function announcements(){
		echo '<h3>'.__('Forum Announcements','bbpse').'</h3>';
	
		$announcements=apply_filters('bbpse_forum_annoucements',array(
				array(
					'label' => __('Manage Forum Announcements','bbpse' ),
					'name' =>'announcement',
					'type' => 'announcements',
					'desc' => __('Define announcements for forums','bbpse')
				),
			));

		$this->generate_form('announcements',$announcements);
	}

	function generate_form($tab,$settings=array()){
		echo '<form method="post">
				<table class="form-table">';
		wp_nonce_field('save_settings','_wpnonce');   
		echo '<ul class="save-settings">';

		foreach($settings as $setting ){
			echo '<tr valign="top">';
			global $wpdb,$bp;
			switch($setting['type']){
				case 'textarea': 
					echo '<th scope="row" class="titledesc">'.$setting['label'].'</th>';
					echo '<td class="forminp"><textarea name="'.$setting['name'].'">'.(isset($this->settings[$setting['name']])?$this->settings[$setting['name']]:(isset($setting['std'])?$setting['std']:'')).'</textarea>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'select':
					echo '<th scope="row" class="titledesc">'.$setting['label'].'</th>';
					echo '<td class="forminp"><select name="'.$setting['name'].'" class="chzn-select">';
					foreach($setting['options'] as $key=>$option){
						echo '<option value="'.$key.'" '.(isset($this->settings[$setting['name']])?selected($key,$this->settings[$setting['name']]):'').'>'.$option.'</option>';
					}
					echo '</select>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'checkbox':
					echo '<th scope="row" class="titledesc">'.$setting['label'].'</th>';
					echo '<td class="forminp"><input type="checkbox" name="'.$setting['name'].'" '.(isset($this->settings[$setting['name']])?'CHECKED':'').' />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'number':
					echo '<th scope="row" class="titledesc">'.$setting['label'].'</th>';
					echo '<td class="forminp"><input type="number" name="'.$setting['name'].'" value="'.(isset($this->settings[$setting['name']])?$this->settings[$setting['name']]:'').'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'hidden':
					echo '<input type="hidden" name="'.$setting['name'].'" value="1"/>';
				break;
				case 'statuses':
					echo '<th scope="row" class="titledesc">'.$setting['label'].'</th>';
					echo '<td class="forminp">
					<a class="button-primary clone_statuses">'._x('Add new','add new in statuses field type','bbpse').' '.$setting['label'].'</a>
					<ul class="status_list">';
					if(!empty($this->settings[$setting['name']])){
						foreach($this->settings[$setting['name']] as $key=>$val){

						}
					}
					echo '</ul><div class="statuses_dom">'.$setting['dom'].'</div>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				case 'announcements':
					$forums_list = new WP_Query(array('post_type'=>'forum','posts_per_page'=>-1));
					$forums_array = array();
					if($forums_list->have_posts()){
						while($forums_list->have_posts()){
							$forums_list->the_post();
							$forums_array[get_the_ID()] = get_the_title();
						}
					}
					wp_reset_postdata();

					echo '<th scope="row" class="titledesc">'.$setting['label'].'</th>';
					echo '<td class="forminp">
					<a class="button-primary clone_announcements">'._x('Add new announcement','add new in statuses field type','bbpse').' '.$setting['label'].'</a>
					<ul class="announcement_list">'; 
					if(!empty($this->settings[$setting['name']]) && !empty($this->settings[$setting['name']]['forum'])){

						foreach($this->settings[$setting['name']]['forum'] as $key=>$val){
							echo '<li><select name="announcement[forum][]">
							<option value="all" '.(($val == 'all')?'selected':'').'>'._x('All Forums','all forums option in select','bbpse').'</option>';
							if(!empty($forums_array)){
								foreach($forums_array as $fid=>$fname){
									echo '<option value="'.$fid.'" '.(($val == $fid)?'selected':'').'>'.$fname.'</option>';
								}
							}
					echo '</select><textarea name="announcement[message][]">'.$this->settings[$setting['name']]['message'][$key].'</textarea><a class="remove_link"><span class="dashicons dashicons-no-alt"></span></a>
					</li>';
						}
					}
					echo '</ul><div class="announcement_dom">
						<select rel-name="announcement[forum][]">
							<option value="all">'._x('All Forums','all forums option in select','bbpse').'</option>';
							if(!empty($forums_array)){
								foreach($forums_array as $fid=>$fname){
									echo '<option value="'.$fid.'">'.$fname.'</option>';
								}
							}
					echo '</select><textarea rel-name="announcement[message][]"></textarea><a class="remove_link"><span class="dashicons dashicons-no-alt"></span></a>
					</div>';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
				default:
					echo '<th scope="row" class="titledesc">'.$setting['label'].'</th>';
					echo '<td class="forminp"><input type="text" name="'.$setting['name'].'" value="'.(isset($this->settings[$setting['name']])?$this->settings[$setting['name']]:(isset($setting['std'])?$setting['std']:'')).'" />';
					echo '<span>'.$setting['desc'].'</span></td>';
				break;
			}
			
			echo '</tr>';
		}
		echo '</tbody>
		</table>';
		echo '<input type="submit" name="save" value="'.__('Save Settings','bbpse').'" class="button button-primary" /></form>';
	}


	function put($value){
		update_option($this->option,$value);
	}

	function save(){
		$none = $_POST['save_settings'];
		if ( !isset($_POST['save']) || !isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'],'save_settings') ){
		    _e('Security check Failed. Contact Administrator.','bbpse');
		    die();
		}
		unset($_POST['_wpnonce']);
		unset($_POST['_wp_http_referer']);
		unset($_POST['save']);

		foreach($_POST as $key => $value){
			$this->settings[$key]=$value;
		}

		$this->put($this->settings);
	}


	function addons(){
		
	}
}

BBPSE_Settings::init();