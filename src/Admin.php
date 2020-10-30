<?php
namespace KnowledgeBase;

class Admin {

	public static function init() {

		Admin::create_submenu();
	}

	public static function create_submenu() {

		add_options_page( 'Knowledge Base', 'Knowledge Base', 'manage_options', 'knowledge_base', array( 'KnowledgeBase\Admin', 'plugin_option_page' ) );
	}

	public static function plugin_option_page() {

		include_once( 'views/options-page.php' );
	}
}
