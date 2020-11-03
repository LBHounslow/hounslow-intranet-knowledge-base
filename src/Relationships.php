<?php
namespace KnowledgeBase;

class Relationships {

  public static function register_relationships() {

    // Guides -> Guides
    \MB_Relationships_API::register( [
        'id' => 'guides_to_guides',
        'from'       => 'guide',
        'to'         => 'guide',
        'reciprocal' => true, // THIS
    ] );

    }
}
