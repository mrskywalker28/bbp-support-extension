<?php
/**
 * The bbPress Support Extension Plugin
 * Convert BBPress Forums into full fledges support system.
 * @package bbPress-support-extension
 * @subpackage Main
 */
if ( !defined( 'ABSPATH' ) ) exit;


class BBPSE_Actions{

	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new BBPSE_Actions();
        return self::$instance;
    }

	public function __construct(){
		/*
		Add Control for Topic status
		 */
		add_action('bbp_theme_after_topic_form_status',array($this,'add_support_control'));
	}

	function add_support_control(){

	}

}

BBPSE_Actions::init();