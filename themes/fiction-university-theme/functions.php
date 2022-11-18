<?php
    function university_files(){
        wp_enqueue_script('java-script', get_theme_file_uri('/build/index.js'), '1.0',true); 
        wp_enqueue_style('google-fonts','https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
        wp_enqueue_style('font-awesome','https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        wp_enqueue_style('university_main_styles', get_theme_file_uri('/build/style-index.css')); 
        wp_enqueue_style('university_index_styles', get_theme_file_uri('/build/index.css')); 
        // wp_enqueue_style('university_main_styles', get_stylesheet_uri()); 
    }
    add_action('wp_enqueue_scripts','university_files');

    function university_features(){
        // register_nav_menu('headerMenuLocation','Header Menu location');
        // register_nav_menu('footerMenuLocation','footer Menu location 1');
        // register_nav_menu('footerMenuLocation2','footer Menu location 2');
        add_theme_support('title-tag');
    }
    add_action('after_setup_theme','university_features');
   
    function get_breadcrumb() {
        
        echo '<a href="'.home_url().'" rel="nofollow">Home</a>';
        if (is_single()) {
            echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
            the_category(' &bull; ');
                if (is_single()) {
                    echo " &nbsp;&nbsp;&#187;&nbsp;&nbsp; ";
                    echo the_title();
                }
        } elseif (is_page()) {
           
            echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;";
            echo the_title();
        } elseif (is_search()) {
            echo "&nbsp;&nbsp;&#187;&nbsp;&nbsp;Search Results for... ";
            echo '"<em>';
            echo the_search_query();
            echo '</em>"';
        }
    }
    function latest_post_old(WP_REST_Request $request){
        $get_params = $request->get_params();
        $args = array(
            'post_type'         => 'blog',
            'post_status'       => 'publish',
            'meta_key'			=> 'published_date',
            'orderby' 			=> 'meta_value',
            'order' 			=> 'DESC',
            'post_per_page' 	=> '6'
        );
        $media = new WP_Query($args);
        print_r($media);
    }
    // It is online web exam application which is develop in wordpress
    
    // $request = new WP_REST_Request( 'GET', '/wp/v2/posts' );
    //     $request->set_query_params(array(
    //         'post_type'=>'blog',
    //         'per_page' => 1
    //     ));
        
        // function mediaArchive_list() {
        //     register_rest_route( 'latest_post/v1', '/post/', array(
        //         'methods' => 'GET',
        //         'callback' => 'latest_post',
        //     ) );
        // }
        add_action( 'rest_api_init', 'latest_post' );
        

?>