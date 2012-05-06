<?php

/**
 * Initialization file for the Did You Know extension.
 *
 * Documentation:	 		https://www.mediawiki.org/wiki/Extension:Did_You_Know
 * Support					https://www.mediawiki.org/wiki/Extension_talk:Did_You_Know
 * Source code:				https://gerrit.wikimedia.org/r/gitweb?p=mediawiki/extensions/DidYouKnow.git
 *
 * @file
 * @ingroup DYK
 *
 * @licence GNU GPL v2+
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */

/**
 * This documentation group collects source code files belonging to Did You Know.
 *
 * @defgroup DYK Did You Know
 */

if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'Not an entry point.' );
}

if ( version_compare( $wgVersion, '1.18c', '<' ) ) { // Needs to be 1.18c because version_compare() works in confusing ways.
	die( '<b>Error:</b> Did You Know requires MediaWiki 1.18 or above.' );
}

define( 'DYK_VERSION', '0.1 alpha' );

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Did You Know',
	'version' => DYK_VERSION,
	'author' => array(
		'[http://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Did_You_Know',
	'descriptionmsg' => 'didyouknow-desc'
);

// i18n
$wgExtensionMessagesFiles['DidYouKnow'] 			= dirname( __FILE__ ) . '/DidYouKnow.i18n.php';

// Autoloading
$wgAutoloadClasses['DYKSettings'] 					= dirname( __FILE__ ) . '/DidYouKnow.settings.php';

// Hooks
$wgHooks['LoadExtensionSchemaUpdates'][] 			= 'EPHooks::onSchemaUpdate';

// Resource loader modules
$moduleTemplate = array(
	'localBasePath' => dirname( __FILE__ ) . '/resources',
	'remoteExtPath' => 'DidYouKnow/resources'
);


$wgResourceModules['didyouknow'] = $moduleTemplate + array(
	'scripts' => array(
	),
);

unset( $moduleTemplate );

$egDYKSettings = array();

