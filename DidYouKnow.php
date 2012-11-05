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

if ( version_compare( $wgVersion, '1.21c', '<' ) ) { // Needs to be 1.21c because version_compare() works in confusing ways.
	die( '<b>Error:</b> Did You Know requires MediaWiki 1.21 or above.' );
}

define( 'DYK_VERSION', '0.2 alpha' );

$wgExtensionCredits['other'][] = array(
	'path' => __FILE__,
	'name' => 'Did You Know',
	'version' => DYK_VERSION,
	'author' => array(
		'[https://www.mediawiki.org/wiki/User:Jeroen_De_Dauw Jeroen De Dauw]',
	),
	'url' => 'https://www.mediawiki.org/wiki/Extension:Did_You_Know',
	'descriptionmsg' => 'didyouknow-desc'
);

// i18n
$wgExtensionMessagesFiles['DidYouKnow'] 	= __DIR__ . '/DidYouKnow.i18n.php';

// Autoloading
$wgAutoloadClasses['DYKSettings'] 			= __DIR__ . '/DidYouKnow.settings.php';
$wgAutoloadClasses['DYKHooks'] 				= __DIR__ . '/DidYouKnow.hooks.php';
$wgAutoloadClasses['DYKBox'] 				= __DIR__ . '/includes/DYKBox.php';

// Resource loader modules
$moduleTemplate = array(
	'localBasePath' => __DIR__ . '/resources',
	'remoteExtPath' => 'DidYouKnow/resources'
);

$wgResourceModules['ext.dyk'] = $moduleTemplate + array(
	'styles' => array(
		'ext.dyk.css',
	),
);

unset( $moduleTemplate );

$egDYKSettings = array();

