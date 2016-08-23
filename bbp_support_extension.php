<?php
/**
 * The bbPress Support Extension Plugin
 * Convert BBPress Forums into full fledges support system.
 * @package bbPress-support-extension
 * @subpackage Main
 */

/**
 * Plugin Name: bbp-support-extension
 * Plugin URI:  https://vibethemes.com
 * Description: BBPRess Support extension is a plugin to convert BBPress forums into full fledged support system
 * Author:      VibeThemes
 * Author URI:  https://vibethemes.com
 * Version:     1.0
 * Text Domain: bbpse
 * Domain Path: /languages/
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

define('BBP_SUPPORT_EXTENSION_VERSION', '1.0');


/** Core **************************************************************/

include_once('includes/classes/class.settings.php'	);
include_once('includes/classes/class.ratings.php'	);

/** Components ********************************************************/

include_once('includes/functions.php'	);
include_once('includes/adminsettings.php'		);
include_once('includes/actions.php'		);
include_once('includes/filters.php'		);
include_once('includes/init.php'		);