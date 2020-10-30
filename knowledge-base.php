<?php
/**
 * Plugin Name:       Knowledge Base
 * Plugin URI:        https://hounslow.digital/plugins/hounslow-intranet-custom
 * Description:       WordPress plugin for turning a website into a knowledge base.
 * Version:           0.0.1
 * Requires at least: 5.4
 * Requires PHP:      7.3.2
 * Author:            London Borough of Hounslow
 * Author URI:        https://www.hounslow.gov.uk
 * License:           GPL v3 or later
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       knowledge-base
 * Domain Path:       /public/lang
 * Network:			      false
 */

 /*
Copyright (C) 2020  London Borough of Hounslow

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License along
with this program; if not, write to the Free Software Foundation, Inc.,
51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
namespace KnowledgeBase;

/* Set the plugin directory path constant. */
define( 'KNOWLEDGE_BASE_DIR', plugin_dir_path( __FILE__ ) );

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 *
 */
register_activation_hook( __FILE__, function() { require_once KNOWLEDGE_BASE_DIR . 'src/Activation.php'; Activation::activate(); } );
register_deactivation_hook( __FILE__, function() { require_once KNOWLEDGE_BASE_DIR . 'src/Deactivation.php'; Deactivation::deactivate(); } );

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/

/* Load required classes. */
require_once KNOWLEDGE_BASE_DIR . 'src/Util.php';
require_once KNOWLEDGE_BASE_DIR . 'src/PluginPublic.php';
require_once KNOWLEDGE_BASE_DIR . 'src/PostTypes.php';

/* Run classes on init hook. */
add_action( 'init', array( 'KnowledgeBase\PostTypes', 'init' ));

/* Run classes on plugins_loaded hook. */
add_action( 'plugins_loaded', array( 'KnowledgeBase\PluginPublic', 'init' ));

/* Run classes on widgets_init hook. */
add_action( 'widgets_init',  array( 'KnowledgeBase\PluginPublic', 'register_widgets' ));


/*----------------------------------------------------------------------------*
* Admin-Facing Functionality
*----------------------------------------------------------------------------*/

if ( is_admin() ) {

  /* Load required classes. */
  require_once KNOWLEDGE_BASE_DIR . 'src/Admin.php';

  /* Run classes on admin_menu hook. */
  add_action( 'admin_menu', array( 'KnowledgeBase\Admin', 'init' ));

}
