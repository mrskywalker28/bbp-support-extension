<?php
/**
 * The bbPress Support Extension Plugin
 * Convert BBPress Forums into full fledges support system.
 * @package bbPress-support-extension
 * @subpackage Main
 */
if ( !defined( 'ABSPATH' ) ) exit;


class BBPSE_Admin{

	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new BBPSE_Admin();
        return self::$instance;
    }

	public function __construct(){
		add_action('bbp_forum_metabox',array($this,'enable_support'),10,1);
		add_action('bbp_forum_attributes_metabox_save',array($this,'save_support'),10,1);

		add_action('bbp_topic_metabox',array($this,'topic_metabox'),10,1);
	}

	function enable_support($post_id){
		
		?>
		<hr>
		<p>
			<strong class="label"><?php esc_html_e( 'Support', 'bbpse' ); ?></strong>
			<label class="screen-reader-text" for="bbpse_forum_select"><?php esc_html_e( 'Support Forum:', 'bbpse' ) ?></label>
			<?php bbpse_support_forum_dropdown( array( 'forum_id' => $post_id ) ); ?>
		</p>
		<?php
	}

	function save_support($forum_id){
		if(isset($_POST['bbpse_forum_status'])){
			update_post_meta($forum_id,'bbpse_forum_status',$_POST['bbpse_forum_status']);
		}
	}


	function topic_metabox($topic_id){
		?>
		<hr>
		<p>
			<strong class="label"><?php esc_html_e( 'Support Status', 'bbpse' ); ?></strong>
			<label class="screen-reader-text" for="bbpse_support_status"><?php esc_html_e( 'Support Status', 'bbpress' ); ?></label>
			<?php bbpse_topic_status_dropdown( array( 'topic_id' => $topic_id ) ); ?>
		</p>
		<?php
		if(bbpse_support_staff()){
			?>
			<p>
				<strong class="label"><?php esc_html_e( 'Assigned', 'bbpse' ); ?></strong>
				<label class="screen-reader-text" for="bbpse_support_status"><?php esc_html_e( 'Support Status', 'bbpress' ); ?></label>
				<?php bbpse_topic_status_dropdown( array( 'topic_id' => $topic_id ) ); ?>
			</p>
			<?php
		}
	}
}

BBPSE_Admin::init();