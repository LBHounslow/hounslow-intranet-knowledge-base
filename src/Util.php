<?php

namespace KnowledgeBase;

class Util {

	public static function hello() {
    echo 'Hello World!';
	}

	/* Function to check if any page is the current front page (for example, when you’re in a custom loop). It expects a page ID as argument.
	 * Source: https://developer.wordpress.org/reference/functions/is_front_page/#comment-4258
	*/
	public static function page_is_front_page( int $id ) {
    // If this is set to anything else than 'page' there is no front page
    // anyway, so always return false
    if ( 'page' !== get_option( 'show_on_front' ) ) {
        return false;
    }

    // Types for option values are string, so convert to int
    $front_id = (int) get_option( 'page_on_front' );

    return $front_id == $id;
	}

}
