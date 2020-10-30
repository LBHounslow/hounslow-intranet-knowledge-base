<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	wp_die( sprintf(
		__( '%s should only be called when uninstalling the plugin.', 'knowledge-base' ),
		__FILE__
	) );
	exit;
}

// Run your uninstall code here.
