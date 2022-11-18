<?php  
     add_action('init', 'events_init');
    
     function events_init() {
        $args = array(
            'labels' => array(
                'name' => __('Events'),
                'singular_name' => __('Event'),
                'add_new_item' =>__('Add new event'),
                'edit_item' =>__('Edit event')
            ),
            'menu_icon'=>'dashicons-calendar',
            'public' => true,
            'has_archive' => true,
            'rewrite' => array("slug" => "events"), 
            'supports' => array('thumbnail','editor','title','custom-fields')
        );
        register_post_type( 'events' , $args );
        // program type post
        $program = array(
            'labels' => array(
                'name' => __('Program'),
                'singular_name' => __('Event'),
                'add_new_item' =>__('Add new event'),
                'edit_item' =>__('Edit event')
            ),
            'menu_icon'=>'dashicons-calendar',
            'public' => true,
            'has_archive' => true,
            'rewrite' => array("slug" => "program"), 
            'supports' => array('thumbnail','editor')
        );
        register_post_type( 'program' , $program );
 }
?>