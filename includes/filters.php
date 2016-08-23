<?php
/**
 * The bbPress Support Extension Plugin
 * Convert BBPress Forums into full fledges support system.
 * @package bbPress-support-extension
 * @subpackage Main
 */
if ( !defined( 'ABSPATH' ) ) exit;

class BBPSE_Filters{

	public static $instance;
	
	public static function init(){

        if ( is_null( self::$instance ) )
            self::$instance = new BBPSE_Filters();
        return self::$instance;
    }

	public function __construct(){
		
	}

}

BBPSE_Filters::init();