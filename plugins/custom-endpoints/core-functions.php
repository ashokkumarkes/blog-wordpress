<?php

use \Firebase\JWT\JWT;

function ct_get_headers(WP_REST_Request $data){
    $result = array();
    $menu = wp_get_nav_menu_object("Main Menu");
    $primaryNav = wp_get_nav_menu_items($menu->term_id);
    if($primaryNav){
        $i=0;
        foreach($primaryNav as $nav){
            $result['primary_header'][$i]['title'] = $nav->title;
            $result['primary_header'][$i]['url'] = $nav->url;
            $i++;
        }
    }

    $menu = wp_get_nav_menu_object("Social Media Menu" );
    $socialMediaNav = wp_get_nav_menu_items($menu->term_id);
    if($socialMediaNav){
        $i=0;
        foreach($socialMediaNav as $nav){
            $result['social_media_header'][$i]['title'] = $nav->title;
            $result['social_media_header'][$i]['url'] = $nav->url;
            $i++;
        }
    }
    return new \WP_REST_Response($result, 200);
}

function ct_get_footers(WP_REST_Request $data){
    $result = array();
    $menu = wp_get_nav_menu_object("Footer Menu");
    $footerNav = wp_get_nav_menu_items($menu->term_id);
    if($footerNav){
        $i=0;
        foreach($footerNav as $nav){
            $result['footer_menu'][$i]['title'] = $nav->title;
            $result['footer_menu'][$i]['url'] = $nav->url;
            $i++;
        }
    }

    $menu = wp_get_nav_menu_object("Social Media Menu" );
    $socialMediaNav = wp_get_nav_menu_items($menu->term_id);
    if($socialMediaNav){
        $i=0;
        foreach($socialMediaNav as $nav){
            $result['social_media_header'][$i]['title'] = $nav->title;
            $result['social_media_header'][$i]['url'] = $nav->url;
            $i++;
        }
    }

    // Footer Left
    if (is_active_sidebar('left-footer-widget-area')) :
        ob_start();
        dynamic_sidebar('left-footer-widget-area');
        $footer_left = ob_get_contents();
        ob_end_clean();
    endif;

    // Footer Right
    if (is_active_sidebar('right-footer-widget-area')) :
        ob_start();
        dynamic_sidebar('right-footer-widget-area');
        $footer_right = ob_get_contents();
        ob_end_clean();
    endif;

    // Copyright
    if (is_active_sidebar('copyright-widget-area')) :
        ob_start();
        dynamic_sidebar('copyright-widget-area');
        $copyright = ob_get_contents();
        ob_end_clean();
    endif;

    $result['footer_left']['content'] = $footer_left;
    $result['footer_right']['content'] = $footer_right;
    $result['copyright']['content'] = $copyright;

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                            Homepage 
========================================================================== */
function ct_get_live_streaming(WP_REST_Request $data){
    // Homepage ID
    $page = get_page_by_path( 'homepage' );
    if( !empty(get_field('live_streaming', $page->ID))):
        $result['live_streaming'] = get_field('live_streaming', $page->ID);
    else:
        $result['live_streaming'] = array();
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_tournament_stats(WP_REST_Request $data){   
    // Upcoming Fixtures
    $UpfixtureArgs = array(
        "posts_per_page" => 3,
        "post_type"      => 'fixtures',
        "orderby"        => "date",
        "order"          => "ASC",
        "post_status"    => "future"
    );      
    $upfixtures = new WP_Query( $UpfixtureArgs );
    if ( count($upfixtures->posts) > 0 ) :
        foreach($upfixtures->posts as $f => $fixture) :
            $result['upcoming_matches'][$f]['ID'] = $fixture->ID;
            $result['upcoming_matches'][$f]['bg_image'] = wp_get_attachment_image_src(get_post_thumbnail_id($fixture->ID), 'full')[0];
            $result['upcoming_matches'][$f]['post_date'] = $fixture->post_date;

            // Place Details
            $result['upcoming_matches'][$f]['date_time'] = get_field("fixtures_date_and_time", $fixture->ID);
            $result['upcoming_matches'][$f]['venue'] = get_field("fixtures_venue", $fixture->ID);

            // Team Data
            $result['upcoming_matches'][$f]['team_a_flag'] = get_field('fixtures_team_a_logo', $fixture->ID)['url'];
            $result['upcoming_matches'][$f]['team_a'] = get_field("fixtures_team_a", $fixture->ID);
            $result['upcoming_matches'][$f]['team_b_flag'] = get_field('fixtures_team_b_logo', $fixture->ID)['url'];
            $result['upcoming_matches'][$f]['team_b'] = get_field('fixtures_team_b', $fixture->ID);
        endforeach;
    endif; 

    return new \WP_REST_Response($result, 200);
}

function ct_latest_posts(WP_REST_Request $data){
    $recent_args = array(
        "posts_per_page" => 9,
        "post_type"      => 'post',
        "orderby"        => "date",
        "order"          => "DESC"
    );      
    $recent_posts = new WP_Query( $recent_args );
    if ( $recent_posts->have_posts() ) :
        for( $i = 0; $i < count($recent_posts->posts); $i++ ) :
            $result['posts'][$i]['ID'] = $recent_posts->posts[$i]->ID;
            $result['posts'][$i]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($recent_posts->posts[$i]->ID), 'full')[0];
            $result['posts'][$i]['link'] = get_the_permalink($recent_posts->posts[$i]->ID);
            $result['posts'][$i]['title'] = $recent_posts->posts[$i]->post_title;
            $result['posts'][$i]['date'] = $recent_posts->posts[$i]->post_date;
        endfor;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_fixtures_slider(WP_REST_Request $data){
    $UpfixtureArgs = array(
        "posts_per_page" => -1,
        "post_type"      => 'fixtures',
        "orderby"        => "date",
        "order"          => "ASC",
        "post_status"    => array("publish", "future")
    );      
    $Upfixtures = new WP_Query( $UpfixtureArgs );
    if ( count($Upfixtures->posts) > 0 ) :
        foreach($Upfixtures->posts as $f => $fixture) :
            if($fixture->post_status == "future") :
                $time = get_the_date("jS F, Y", $fixture->ID)." - ".get_the_time("g:i A", $fixture->ID);
                $tournament = get_category_by_slug('tournament')->term_id;
                $catLoop = get_the_category($fixture->ID);
                foreach($catLoop as $cat){
                    if($cat->parent == $tournament){
                        $catName = $cat->name;
                    } 
                }

                // Result Array
                $result['fixtures']['upcoming'][$f]['tournament'] = $catName;
                $result['fixtures']['upcoming'][$f]['date_time'] = $time;
                $result['fixtures']['upcoming'][$f]['venue'] = get_field('fixtures_venue', $fixture->ID);
                $result['fixtures']['upcoming'][$f]['team_a_flag'] = get_field('fixtures_team_a_logo', $fixture->ID)['url'];
                $result['fixtures']['upcoming'][$f]['team_a'] = get_field('fixtures_team_a', $fixture->ID);
                $result['fixtures']['upcoming'][$f]['team_b_flag'] = get_field('fixtures_team_b_logo', $fixture->ID)['url'];
                $result['fixtures']['upcoming'][$f]['team_b'] = get_field('fixtures_team_b', $fixture->ID);
            elseif ($fixture->post_status == "publish") :
                $time = get_the_date("jS F, Y", $fixture->ID)." - ".get_the_time("g:i A", $fixture->ID);
                $tournament = get_category_by_slug('tournament')->term_id;
                $catLoop = get_the_category($fixture->ID);
                foreach($catLoop as $cat){
                    if($cat->parent == $tournament){
                        $catName = $cat->name;
                    } 
                }

                // Result Array
                $result['fixtures']['completed'][$f]['tournament'] = $catName;
                $result['fixtures']['completed'][$f]['venue'] = get_field('fixtures_venue', $fixture->ID);
                $result['fixtures']['completed'][$f]['team_a_flag'] = get_field('fixtures_team_a_logo', $fixture->ID)['url'];
                $result['fixtures']['completed'][$f]['team_a'] = get_field('fixtures_team_a', $fixture->ID);
                $result['fixtures']['completed'][$f]['team_b_flag'] = get_field('fixtures_team_b_logo', $fixture->ID)['url'];
                $result['fixtures']['completed'][$f]['team_b'] = get_field('fixtures_team_b', $fixture->ID);
                $result['fixtures']['completed'][$f]['result'] = get_field('fixtures_match_result', $fixture->ID);;
            endif;
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_news_slider(WP_REST_Request $data){  
    // Get all Sub Catgories of News
    $newObj = get_category_by_slug('news');
    $query = get_terms( $newObj->taxonomy, array(
        'parent'    => $newObj->term_id,
        'order' => "DESC",
    ) );
    if(!empty($query)) :
        foreach($query as $c => $cat) :
            $result['news']['category'][$c]['slug'] = $cat->slug;
            $result['news']['category'][$c]['name'] = $cat->name;
        
            $news = array(
                "posts_per_page" => -1,
                "post_type"      => 'post',
                "orderby"        => "date",
                "order"          => "DESC",
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'term_id',
                        'terms'    => array($cat->term_id),
                    ),
                ),
            );      
            $posts = new WP_Query( $news );
            if ( $posts->have_posts() ) : 
                foreach($posts->posts as $p => $post) : 
                    $result['news']['category'][$c]['posts'][$p]['ID'] = $post->ID;
                    $result['news']['category'][$c]['posts'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'card-thumbnail')[0];
                    $result['news']['category'][$c]['posts'][$p]['title'] = $post->post_title;
                    $result['news']['category'][$c]['posts'][$p]['content'] = wp_trim_words( $post->post_content, 15, '...' );
                    $result['news']['category'][$c]['posts'][$p]['date'] = $post->post_date;
                    $result['news']['category'][$c]['posts'][$p]['author'] = get_the_author_meta('display_name' , $post->post_author);
                    $result['news']['category'][$c]['posts'][$p]['category_name'] = $cat->name;
                endforeach;        
            endif;
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_team_types(WP_REST_Request $data){
    // Homepage ID
    $page = get_page_by_path( 'homepage' );
    
    $idObj = get_category_by_slug('teams');
    $query = get_terms( $idObj->taxonomy, array(
        'parent'    => $idObj->term_id,
        'hide_empty' => false
    ) );
    if(!empty($query)) :
        foreach($query as $c => $cat) :
            if($cat->slug == "men") :
                $result['team_type'][$c]['image'] = get_field('mens_team_image', $page->ID)['url'];
            else :
                $result['team_type'][$c]['image'] = get_field('womens_team_image', $page->IDs)['url'];
            endif;
            $result['team_type'][$c]['name'] = $cat->name." Cricket Teams";
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_gallery_slider(WP_REST_Request $data){
    $idObj = get_category_by_slug('photos'); 
    $fid = $idObj->term_id;
    $photos = array(
        "posts_per_page" => -1,
        "post_type"      => 'post',
        "orderby"        => "date",
        "order"          => "DESC",
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => array($fid),
            ),
        ),
    );      
    $query = new WP_Query( $photos );
    if ( $query->have_posts() ) :
        foreach ($query->posts as $p => $post) :
            $result['gallery'][$p]['ID'] = $post->ID;
            $result['gallery'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'card-thumbnail')[0];
            $result['gallery'][$p]['link'] = get_the_permalink($post->ID);
            $result['gallery'][$p]['title'] = $post->post_title;
            $result['gallery'][$p]['date'] = $post->post_date;
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_videos_slider(WP_REST_Request $data){
    $jsonp = get_JSONP("https://acc-matchcentre-noesis-spotsmechanics.s3.ap-south-1.amazonaws.com/cpanel/client_14/2022/mediadoc/device/videos/1/videos.js");
    $data = jsonp_decode($jsonp, true);
    if ( count($data['videos']) != 0 ) :
        $cnt = 1;
        foreach ($data['videos'] as $p => $post) :
            if($cnt <= 4) :
                $result['videos'][$p]['ID'] = $post['SNo'];
                if(!empty($post['videothumbmed'])) :
                $result['videos'][$p]['image'] = $post['videothumbmed'];
                elseif (!empty($post['MediaData']['videoMedImg'])) :
                $result['videos'][$p]['image'] = $post['MediaData']['videoMedImg'];
                else :
                $result['videos'][$p]['image'] = site_url("/wp-content/uploads/2022/06/287329193_7683166665058731_8882228154382296874_n-1-267x165.png");
                endif;
                $result['videos'][$p]['link'] = $post['MediaData']['videos'];
                $result['videos'][$p]['duration'] = $post['MediaData']['videoDuration'];
                $result['videos'][$p]['title'] = $post['Title'];
                $result['videos'][$p]['date'] = $post['date'];
            endif;
            $cnt++;
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_about_acc_section(WP_REST_Request $data){
    $page = get_page_by_path( 'homepage' );
    if( get_field('home_acc_featured_image', $page->ID) && get_field('home_acc_featured_content', $page->ID) ): 
        $result['about_acc']['image'] = get_field('home_acc_featured_image', $page->ID)['url'];
        $result['about_acc']['content'] = get_field('home_acc_featured_content', $page->ID);
    endif;

    return new \WP_REST_Response($result, 200);
}

// function ct_get_partners(WP_REST_Request $data){
//     $idObj = get_category_by_slug('partners'); 
//     $pid = $idObj->term_id; 
//     $partners = array(
//         'post_type' => 'partners',
//         'post_status' => 'publish',
//         'orderby' => 'date',
//         'order' => "ASC",
//         'posts_per_page' => -1,
//         'tax_query' => array(
//             array(
//                 'taxonomy' => 'category',
//                 'field'    => 'term_id',
//                 'terms'    => array($pid),
//                 'operator' => 'IN',
//             ),
//         ),
//     );      
//     $query = new WP_Query( $partners );
//     if ( $query->have_posts() ) :
//         foreach($query->posts as $p => $post) :
//             $result['partners']['normal'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail')[0];
//         endforeach;

//         $idObj1 = get_category_by_slug('broadcast-partners'); 
//         $bid = $idObj1->term_id; 
        
//         $broadcast = array(
//             'post_type' => 'partners',
//             'post_status' => 'publish',
//             'posts_per_page' => -1,
//             'tax_query' => array(
//                 array(
//                     'taxonomy' => 'category',
//                     'field'    => 'term_id',
//                     'terms'    => array($bid),
//                     'operator' => 'IN',
//                 ),
//             ),
//         );      
//         $query1 = new WP_Query( $broadcast );
//         if ( $query1->have_posts() ) :
//             foreach($query1->posts as $k => $post) :
//                 $result['partners']['broadcast'][$k]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail')[0];
//             endforeach;
//         endif;
//     endif;

//     return new \WP_REST_Response($result, 200);
// }

function ct_get_members_list(WP_REST_Request $data){
    $members = array(
        'post_type' => 'members',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'orderby' => "title",
        'order' => "ASC"
    );      
    $query = new WP_Query( $members );
    if ( $query->have_posts() ) :
        foreach($query->posts as $p => $post) :
            $result['members'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'single-post-thumbnail')[0];
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                            About Us 
========================================================================== */
function ct_get_annual_reports(WP_REST_Request $data){
    $page = get_page_by_path( 'annual-report-and-accounts' );

    $result['annual_reports']['title'] = get_the_title($page->ID);
    if( !empty(get_field('annual_reports_file_list', $page->ID))):
        $reports = get_field('annual_reports_file_list', $page->ID);
        foreach($reports as $p => $post) :
            $result['annual_reports']['files'][$p]['name'] = $post['annual_reports_file_name'];
            $result['annual_reports']['files'][$p]['image'] = $post['annual_reports_file_image']['url'];
            if(!empty($post['annual_reports_download_link'])) :
            $result['annual_reports']['files'][$p]['link'] = $post['annual_reports_download_link'];
            else:
            $result['annual_reports']['files'][$p]['link'] = "";
            endif;
            $result['annual_reports']['files'][$p]['size'] = $post['annual_reports_file_size'];
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_board_members(WP_REST_Request $data){
    $page = get_page_by_path( 'board-members' );

    $result['board_members']['title'] = get_the_title($page->ID);
    if(!empty(get_field('board_members_group', $page->ID))):
        $board = get_field('board_members_group', $page->ID);
        foreach($board as $p => $post) :
            $result['board_members'][$p]['category_name'] = $post['board_member_category'];
            if(!empty($post['board_member_list'])):
                foreach($post['board_member_list'] as $i => $item) :
                    $result['board_members'][$p]['list'][$i]['name'] = $item['board_member_name'];
                    $result['board_members'][$p]['list'][$i]['designation'] = strip_tags($item['board_designation']);
                    if(!empty($item['board_member_image']['url'])) :
                    $result['board_members'][$p]['list'][$i]['image'] = $item['board_member_image']['url'];
                    else:
                    $result['board_members'][$p]['list'][$i]['image'] = get_template_directory_uri(). "/assets/img/dummy_user.jpg";
                    endif;
                endforeach;
            endif;
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_member_contacts(WP_REST_Request $data){
    $args = array(
        "post_type"      => 'members',
        'posts_per_page' => -1,
        'order'          => 'ASC',
        'orderby'        => 'title',
    );      
    $query = new WP_Query( $args );
    if ( $query->have_posts() ) :
        foreach($query->posts as $e => $entry) :
            $result['member'][$e]['name'] = $entry->post_title;
            $result['member'][$e]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($entry->ID), 'full')[0];
            $result['member'][$e]['address'] = get_field('member_address', $entry->ID);
            $result['member'][$e]['phone'] = get_field('member_phone', $entry->ID);
            $result['member'][$e]['fax'] = get_field('member_fax', $entry->ID);
            $emails = get_field('email_loop', $entry->ID);
            if(!empty($emails) && count($emails) > 0) :
                foreach($emails as $i => $email) :
                    $result['member'][$e]['email'][$i] = $email['member_email'];
                endforeach;
            endif;
            $result['member'][$e]['website'] = get_field('member_website', $entry->ID);
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                           News
========================================================================== */
function ct_get_news(WP_REST_Request $data){
    $idObj = get_category_by_slug('news'); 
    $nid = $idObj->term_id; 
    $news = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => "DESC",
        'orderby' => 'date',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => array($nid),
                'operator' => 'IN',
            ),
        ),
    );      
    $query = new WP_Query( $news );
    if ( $query->have_posts() ) :
        $catObj = get_category_by_slug('news');
        $catQuery = get_term_children( $catObj->term_id, $catObj->taxonomy);
        if(!empty($catQuery)) :
            foreach($catQuery as $c => $child) :
                $category = get_category($child);
                $term = get_term_by('id', $child, $catObj->taxonomy);

                $result['news'][$c]['category_slug'] = $term->slug;
                $result['news'][$c]['category_name'] = $term->name;

                $tags = get_tags_in_use($child, 'name');
                if(!empty($tags)) :
                    foreach($tags as $t => $tag) :
                        $result['news'][$c]['category'][$t]['tag_name'] = $tag;
                        $listing = array(
                            "posts_per_page" => -1,
                            "post_type"      => 'post',
                            "orderby"        => "date",
                            "order"          => "DESC",
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'post_tag',
                                    'field'    => 'name',
                                    'terms'    => $tag,
                                ),
                            ),
                        );      
                        $query = new WP_Query( $listing );
                        if ( $query->have_posts() ) :
                            foreach($query->posts as $p => $post) :
                                $result['news'][$c]['category'][$t]['posts'][$p]['ID'] = $post->ID;
                                $result['news'][$c]['category'][$t]['posts'][$p]['title'] = $post->post_title;
                                $result['news'][$c]['category'][$t]['posts'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'card-thumbnail')[0];
                                $result['news'][$c]['category'][$t]['posts'][$p]['content'] = wp_trim_words( $post->post_content, 15, '...' );
                                $result['news'][$c]['category'][$t]['posts'][$p]['date'] = $post->post_date;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
        endif;
    endif;

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                       Video Detail Page 
========================================================================== */
function ct_get_video_detail(WP_REST_Request $data){
    $post_ID = $_GET['id'];
    $jsonp = get_JSONP("https://acc-matchcentre-noesis-spotsmechanics.s3.ap-south-1.amazonaws.com/cpanel/client_14/2022/mediadoc/device/videos/1/videos.js");
    $data = jsonp_decode($jsonp, true);
    foreach($data['videos'] as $v => $video) {
        if($video['SNo'] == $post_ID){
            $result['videos']['post']['post'][$v]['ID'] = $video['SNo'];
            if(!empty($video['videothumbmed'])) :
            $result['videos']['post'][$v]['image'] = $video['videothumbmed'];
            elseif (!empty($video['MediaData']['videoMedImg'])) :
            $result['videos']['post'][$v]['image'] = $video['MediaData']['videoMedImg'];
            else :
            $result['videos']['post'][$v]['image'] = site_url("/wp-content/uploads/2022/06/287329193_7683166665058731_8882228154382296874_n-1-267x165.png");
            endif;          
            $result['videos']['post'][$v]['link'] = $video['MediaData']['videos'];
            $result['videos']['post'][$v]['title'] = $video['Title'];
            $result['videos']['post'][$v]['duration'] = $video['MediaData']['videoDuration'];
            $result['videos']['post'][$v]['date'] = $video['date'];
        }
        else if($video['SNo'] != $post_ID){
            $result['videos']['more'][$v]['ID'] = $video['SNo'];
            if(!empty($video['videothumbmed'])) :
            $result['videos']['more'][$v]['image'] = $video['videothumbmed'];
            elseif (!empty($video['MediaData']['videoMedImg'])) :
            $result['videos']['more'][$v]['image'] = $video['MediaData']['videoMedImg'];
            else :
            $result['videos']['more'][$v]['image'] = site_url("/wp-content/uploads/2022/06/287329193_7683166665058731_8882228154382296874_n-1-267x165.png");
            endif;
            $result['videos']['more'][$v]['link'] = $video['MediaData']['videos'];
            $result['videos']['more'][$v]['title'] = $video['Title'];
            $result['videos']['more'][$v]['duration'] = $video['MediaData']['videoDuration'];
            $result['videos']['more'][$v]['date'] = $video['date'];
        }
    }
    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                      Gallery Detail Page 
========================================================================== */
function ct_get_gallery_detail(WP_REST_Request $data){
    $post_ID = $_GET['id'];

    $query = get_post($post_ID);
    $result['post']['ID'] = $query->ID;
    $result['post']['title'] = $query->post_title;
    $result['post']['date'] = $query->post_date;
    $result['post']['author'] = get_userdata($query->post_author)->display_name;
    if (has_shortcode( $query->post_content, 'Best_Wordpress_Gallery' ) ) {
        $result['post']['content'] = do_shortcode($query->post_content);
    }
    else{
        $result['post']['content'] = $query->post_content;
    }

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                           Fixtures
========================================================================== */
function ct_get_fixtures(WP_REST_Request $data){
    $UpfixtureArgs = array(
        "posts_per_page" => -1,
        "post_type"      => 'fixtures',
        "orderby"        => "date",
        "order"          => "ASC",
        "post_status"    => array("future", "live"),
    );
    $Upfixtures = new WP_Query( $UpfixtureArgs );
    if ( count($Upfixtures->posts) > 0 ) :
        foreach($Upfixtures->posts as $f => $upFixture) :
            // Get Game Format Name
            $format = get_category_by_slug('format');
            $args = array(
                "taxonomy"=> "category",
                "parent"  => $format->term_id
            ); 
            $child = wp_get_post_categories($upFixture->ID, $args);

            // Get Tournament Name
            $tournament = get_category_by_slug('tournament');
            $query = array(
                "taxonomy"=> "category",
                "parent"  => $tournament->term_id
            ); 
            $tourney = wp_get_post_categories($upFixture->ID, $query);

            // Pass Data
            $result['fixtures']['upcoming'][$f]['format'] = get_cat_name($child[0]);
            $result['fixtures']['upcoming'][$f]['tournament'] = get_cat_name($tourney[0]);
            $result['fixtures']['upcoming'][$f]['venue'] = get_field('fixtures_venue', $upFixture->ID);
            $result['fixtures']['upcoming'][$f]['date'] = get_the_date("jS F, Y", $upFixture->ID);
            $result['fixtures']['upcoming'][$f]['time'] = get_the_time("g:i A", $upFixture->ID);
            $result['fixtures']['upcoming'][$f]['team_a'] = get_field("fixtures_team_a", $upFixture->ID);
            $result['fixtures']['upcoming'][$f]['team_a_flag'] = get_field('fixtures_team_a_logo', $upFixture->ID)['url'];
            $result['fixtures']['upcoming'][$f]['team_b'] = get_field("fixtures_team_b", $upFixture->ID);
            $result['fixtures']['upcoming'][$f]['team_b_flag'] = get_field('fixtures_team_b_logo', $upFixture->ID)['url'];
        endforeach;
    endif;

    // Completed
    $fixtureArgs = array(
        "posts_per_page" => -1,
        "post_type"      => 'fixtures',
        "orderby"        => "date",
        "order"          => "ASC",
        "post_status"    => array("publish"),
    );
    $fixtures = new WP_Query( $fixtureArgs );
    if ( count($Upfixtures->posts) > 0 ) :
        foreach($fixtures->posts as $f => $fixture) :
            // Get Game Format Name
            $format = get_category_by_slug('format');
            $args = array(
                "taxonomy"=> "category",
                "parent"  => $format->term_id
            ); 
            $child = wp_get_post_categories($fixture->ID, $args);

            // Get Tournament Name
            $tournament = get_category_by_slug('tournament');
            $query = array(
                "taxonomy"=> "category",
                "parent"  => $tournament->term_id
            ); 
            $tourney = wp_get_post_categories($fixture->ID, $query);

            // Pass Data
            $result['fixtures']['completed'][$f]['format'] = get_cat_name($child[0]);
            $result['fixtures']['completed'][$f]['tournament'] = get_cat_name($tourney[0]);
            $result['fixtures']['completed'][$f]['venue'] = get_field('fixtures_venue', $fixture->ID);
            $result['fixtures']['completed'][$f]['date'] = get_the_date("jS F, Y", $fixture->ID);
            $result['fixtures']['completed'][$f]['team_a'] = get_field("fixtures_team_a", $fixture->ID);
            $result['fixtures']['completed'][$f]['team_a_flag'] = get_field('fixtures_team_a_logo', $fixture->ID)['url'];
            $result['fixtures']['completed'][$f]['team_b'] = get_field("fixtures_team_b", $fixture->ID);
            $result['fixtures']['completed'][$f]['team_b_flag'] = get_field('fixtures_team_b_logo', $fixture->ID)['url'];
            $result['fixtures']['completed'][$f]['team_b_flag'] = get_field("fixtures_match_result", $fixture->ID);
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                           Teams
========================================================================== */
function ct_get_teams(WP_REST_Request $data){
    $idObj = get_category_by_slug('teams'); 
    $tid = $idObj->term_id; 
    $teams = array(
        'post_type' => 'team',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'order' => "DESC",
        'order_by' => 'date',
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => array($tid),
                'operator' => 'IN',
            ),
        ),
    );      
    $query = new WP_Query( $teams );
    if ( $query->have_posts() ) :
        $catObj = get_category_by_slug('teams');
        $catQuery = get_term_children( $catObj->term_id, $catObj->taxonomy);
        if(!empty($catQuery)) :
            foreach($catQuery as $c => $child) :
                $category = get_category($child);
                $term = get_term_by('id', $child, $catObj->taxonomy);

                $result['teams']['category'][$c]['slug'] = $term->slug;
                $result['teams']['category'][$c]['name'] = $term->name;

                $childTeam = array(
                    'post_type' => 'team',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'order' => "ASC",
                    'orderby' => 'title',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'category',
                            'field'    => 'term_id',
                            'terms'    => array($term->term_id),
                            'operator' => 'IN',
                        ),
                    ),
                );      
                $children = new WP_Query( $childTeam );
                if ( $children->have_posts() ) :
                    foreach($children->posts as $p => $child) :
                        $name = $child->post_title;
                        $name = explode(" W", $name);

                        // Pass Data
                        $result['teams']['category'][$c]['posts'][$p]['ID'] = $child->ID;
                        $result['teams']['category'][$c]['posts'][$p]['team_image'] = wp_get_attachment_image_src(get_post_thumbnail_id($child->ID), 'full')[0];
                        $result['teams']['category'][$c]['posts'][$p]['team_flag'] = get_field("team_flag_image", $child->ID)['url'];
                        $result['teams']['category'][$c]['posts'][$p]['name'] = $name[0];
                        $result['teams']['category'][$c]['posts'][$p]['link'] = get_the_permalink($child->ID);
                    endforeach;
                endif;
            endforeach;
        endif;
    endif;

    return new \WP_REST_Response($result, 200);
}

function ct_get_single_team(WP_REST_Request $data){
    $post_ID = $_GET['id'];

    // Default Post Data
    $result['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post_ID), 'full')[0];
    $result['flag'] = get_field("team_flag_image", $post_ID)['url'];
    $result['title'] = get_the_title($post_ID);

    // Team Ranking
    $group = get_field('team_ranking_group', $post_ID);
    $result['overview']['ranking']['test'] = $group['team_test_ranking'];
    $result['overview']['ranking']['odi'] = $group['team_odi_ranking'];
    $result['overview']['ranking']['t20'] = $group['team_t20_ranking'];


    $UpfixtureArgs = array(
        "posts_per_page" => -1,
        "post_type"      => 'fixtures',
        "orderby"        => "date",
        "order"          => "ASC",
        "post_status"    => array("publish", "future", "live"),
        "tax_query" => array(
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'name',
                'terms'    => strtolower(get_the_title($post_ID)),
            ),
        ),
    );      
    $Upfixtures = new WP_Query( $UpfixtureArgs );
    if ( count($Upfixtures->posts) > 0 ) :
        foreach($Upfixtures->posts as $f => $fixture) :
            if($fixture->post_status == "future") :
                $time = get_the_date("jS F, Y", $fixture->ID)." - ".get_the_time("g:i A", $fixture->ID);
                $tournament = get_category_by_slug('tournament')->term_id;
                $catLoop = get_the_category($fixture->ID);
                foreach($catLoop as $cat){
                    if($cat->parent == $tournament){
                        $catName = $cat->name;
                    } 
                }
                $result['overview']['fixtures']['upcoming'][$f]['tournament'] = $catName;
                $result['overview']['fixtures']['upcoming'][$f]['date_time'] = $time;
                $result['overview']['fixtures']['upcoming'][$f]['venue'] = get_field('fixtures_venue', $fixture->ID);
                $result['overview']['fixtures']['upcoming'][$f]['team_a'] = get_field('fixtures_team_a', $fixture->ID);
                $result['overview']['fixtures']['upcoming'][$f]['team_a_flag'] = get_field('fixtures_team_a_logo', $fixture->ID)['url'];
                $result['overview']['fixtures']['upcoming'][$f]['team_b'] = get_field('fixtures_team_b', $fixture->ID);
                $result['overview']['fixtures']['upcoming'][$f]['team_b_flag'] = get_field('fixtures_team_b_logo', $fixture->ID)['url'];

            elseif($fixture->post_status == "publish"):
                $time = get_the_date("jS F, Y", $fixture->ID)." - ".get_the_time("g:i A", $fixture->ID);
                $tournament = get_category_by_slug('tournament')->term_id;
                $catLoop = get_the_category($fixture->ID);
                foreach($catLoop as $cat){
                    if($cat->parent == $tournament){
                        $catName = $cat->name;
                    } 
                }
                $result['overview']['fixtures']['completed'][$f]['tournament'] = $catName;
                $result['overview']['fixtures']['completed'][$f]['venue'] = get_field('fixtures_venue', $fixture->ID);
                $result['overview']['fixtures']['completed'][$f]['team_a'] = get_field('fixtures_team_a', $fixture->ID);
                $result['overview']['fixtures']['completed'][$f]['team_a_flag'] = get_field('fixtures_team_a_logo', $fixture->ID)['url'];
                $result['overview']['fixtures']['completed'][$f]['team_b'] = get_field('fixtures_team_b', $fixture->ID);
                $result['overview']['fixtures']['completed'][$f]['team_b_flag'] = get_field('fixtures_team_b_logo', $fixture->ID)['url'];
                $result['overview']['fixtures']['completed'][$f]['result'] = get_field('fixtures_match_result', $fixture->ID);
            endif;
        endforeach;
    endif;

    // Players
    $players = get_field('team_players_list', $post_ID);
    if(!empty($players)) :
        foreach($players as $p => $player):
            $result['overview']['players'][$p]['name'] = $player['team_player_name'];
            $result['overview']['players'][$p]['type'] = $player['team_player_type'];
            $result['overview']['players'][$p]['image'] = $player['team_player_image']['url']; 
        endforeach;
    endif;

    // News
    $newObj = get_category_by_slug('news');
    $query = get_terms( $newObj->taxonomy, array(
        'parent'    => $newObj->term_id,
        'order' => "DESC",
    ) );
    if(!empty($query)) :
        foreach($query as $c => $cat) :
            $result['overview']['news']['category'][$c]['slug'] = $cat->slug;
            $result['overview']['news']['category'][$c]['name'] = $cat->name;
        
            $news = array(
                "posts_per_page" => -1,
                "post_type"      => 'post',
                "orderby"        => "date",
                "order"          => "DESC",
                'tax_query' => array(
                    array(
                        'taxonomy' => 'category',
                        'field'    => 'term_id',
                        'terms'    => array($cat->term_id),
                    ),
                    array(
                        'taxonomy' => 'post_tag',
                        'field'    => 'name',
                        'terms'    => strtolower(get_the_title($post_ID)),
                    ),
                ),
            );      
            $posts = new WP_Query( $news );
            if ( $posts->have_posts() ) : 
                foreach($posts->posts as $p => $post) : 
                    $result['overview']['news']['category'][$c]['posts'][$p]['ID'] = $post->ID;
                    $result['overview']['news']['category'][$c]['posts'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'card-thumbnail')[0];
                    $result['overview']['news']['category'][$c]['posts'][$p]['title'] = $post->post_title;
                    $result['overview']['news']['category'][$c]['posts'][$p]['content'] = wp_trim_words( $post->post_content, 15, '...' );
                    $result['overview']['news']['category'][$c]['posts'][$p]['date'] = $post->post_date;
                    $result['overview']['news']['category'][$c]['posts'][$p]['author'] = get_the_author_meta('display_name' , $post->post_author);
                    $result['overview']['news']['category'][$c]['posts'][$p]['category_name'] = $cat->name;
                endforeach;        
            endif;
        endforeach;
    endif;

    // Photos
    $idObj = get_category_by_slug('photos'); 
    $fid = $idObj->term_id;
    $photos = array(
        "posts_per_page" => -1,
        "post_type"      => 'post',
        "orderby"        => "date",
        "order"          => "DESC",
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => array($fid),
            ),
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'name',
                'terms'    => strtolower(get_the_title()),
            ),
        ),
    );      
    $query = new WP_Query( $photos );
    if ( $query->have_posts() ) :
        foreach ($query->posts as $p => $post) :
            $result['overview']['gallery'][$p]['ID'] = $post->ID;
            $result['overview']['gallery'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'card-thumbnail')[0];
            $result['overview']['gallery'][$p]['link'] = get_the_permalink($post->ID);
            $result['overview']['gallery'][$p]['title'] = $post->post_title;
            $result['overview']['gallery'][$p]['date'] = $post->post_date;
        endforeach;
    endif;

    // Videos Slider
    $idObj = get_category_by_slug('videos'); 
    $vid = $idObj->term_id;
    $videos = array(
        "posts_per_page" => 4,
        "post_type"      => 'post',
        "orderby"        => "date",
        "order"          => "DESC",
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => array($vid),
            ),
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'name',
                'terms'    => strtolower(get_the_title()),
            ),
        ),
    );      
    $query = new WP_Query( $videos );
    if ( $query->have_posts() ) :
        foreach ($query->posts as $p => $post) :
            $result['overview']['videos'][$p]['ID'] = $post->ID;
            $result['overview']['videos'][$p]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'card-thumbnail')[0];
            $result['overview']['videos'][$p]['link'] = get_the_permalink($post->ID);
            $result['overview']['videos'][$p]['title'] = $post->post_title;
            $result['overview']['videos'][$p]['date'] = $post->post_date;
        endforeach;
    endif;

    // Videos
    $vidObj = get_category_by_slug('videos'); 
    $vid2 = $vidObj->term_id; 
    $videos = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'category',
                'field'    => 'term_id',
                'terms'    => array($vid2),
                'operator' => 'IN',
            ),
            array(
                'taxonomy' => 'post_tag',
                'field'    => 'name',
                'terms'    => strtolower(get_the_title($post_ID)),
                'operator' => 'IN',
            ),
        ),
    );      
    $query2 = new WP_Query( $videos );
    if ( $query2->have_posts() ) :
        foreach($query2->posts as $v => $video) :
            $result['videos'][$v]['ID'] = $video->ID;
            $result['videos'][$v]['image'] = wp_get_attachment_image_src(get_post_thumbnail_id($video->ID), 'card-thumbnail')[0];
            $result['videos'][$v]['link'] = get_the_permalink($video->ID);
            $result['videos'][$v]['title'] = $video->post_title;
            $result['videos'][$v]['date'] = $video->post_date;
        endforeach;
    endif;

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                           Contact Form
========================================================================== */
function ct_post_form_submit(WP_REST_Request $data){
    $params = $data->get_params();
    $url = site_url().'/wp-json/gf/v2/forms/1/submissions';

    if(!empty($params)){
        $array = array(
            "input_5" => $params['subject'],
            "input_3.3" => $params['name'],
            "input_4" => $params['email'],
            "input_9" => $params['message'],
        );
        $postData = json_encode($array);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $headers = array();
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }

    return new \WP_REST_Response($result, 200);
}

/* =======================================================================
                    Ultimate Member
========================================================================== */
function ct_post_register(WP_REST_Request $data){
    $params = $data->get_params();

    $username   = !empty($params['username']) ? $params['username'] : "";
    $first_name = !empty($params['first_name']) ? $params['first_name'] : "";
    $last_name = !empty($params['last_name']) ? $params['last_name'] : "";
    $email   = !empty($params['email']) ? $params['email'] : "";
    $password   = !empty($params['password']) ? $params['password'] : "";
    $confirm_password   = !empty($params['confirm_password']) ? $params['confirm_password'] : "";
    $uid   = !empty($params['uid']) ? $params['uid'] : "";

    if(!empty($first_name) && !empty($email) && !empty($username) && !empty($password) && !empty($confirm_password) && $uid == '')
    {
        if($password == $confirm_password)
        {
            if(!username_exists($email) && !email_exists($email)) 
            {
                $user_args = array(
                    'user_login' =>  $username,
                    'user_email'  =>  $email,
                    'user_pass'  =>  $password,
                );
                $user_id = wp_insert_user($user_args);
                if(!is_wp_error($user_id)) 
                {
                    update_user_meta($user_id, 'first_name', $first_name);
                    update_user_meta($user_id, 'last_name', $last_name);
                    return new WP_REST_Response(get_user_by('ID', $user_id), 200);
                }
                return new WP_Error( 'error', __( 'Unable to create user. Please try again.', 'custom-endpoint' ), array( 'status' => 200 ) );
            }
            else
            {
                return new WP_Error( 'error', __( 'Email id already exists', 'custom-endpoint' ), array( 'status' => 200 ) );
            }
        }       
        else
        {
            return new WP_Error( 'error', __( 'Password and confirm password must be same.', 'custom-endpoint' ), array( 'status' => 200 ) );
        }
    }
    elseif(!empty($first_name) && !empty($last_name) && !empty($email) && $uid != '')
    {
         if(!username_exists($email) && !email_exists($email)) 
         {
             $user_args = array(
                 'user_login' =>  $email,
                 'user_email'  =>  $email,
                 'user_pass'  =>  $email,
             );
             $user_id = wp_insert_user($user_args);
             if(!is_wp_error($user_id)) 
             {
                 update_user_meta($user_id, 'first_name', $first_name);
                 update_user_meta($user_id, 'last_name', $last_name);
                 update_user_meta($user_id, 'uid', $uid);
         
                 return new WP_REST_Response($user_id, 200);
             }
             return new WP_Error( 'error', __( 'Unable to create user. Please try again.', 'custom-endpoint' ), array( 'status' => 200 ) );
         }
         else
         {
             $user_info = get_user_by( 'email', $email );
             update_user_meta($user_info->ID, 'uid', $uid);
            return new WP_REST_Response($user_info, 200);
         }   
    }else
    {
        return new WP_Error( 'error', __( 'Required parameter(s) is missing', 'custom-endpoint' ), array( 'status' => 200 ) ); 
    }
    
}

function ct_post_login(WP_REST_Request $data)
{
    $params = $data->get_params();
    
    $email   = !empty($params['email']) ? $params['email'] : "";
    $password   = !empty($params['password']) ? $params['password'] : "";
    $uid   = !empty($params['uid']) ? $params['uid'] : "";

    if(!empty($email) && !empty($password))
    {
        $user_id = email_exists($email);
        if($user_id) 
        {
            $user_info = wp_authenticate($email, $password);
            if(!is_wp_error($user_info))
            {
                return new WP_REST_Response($user_info, 200);
            }
            else
            {
                return new WP_Error( 'error', __( 'Your email ID or password is incorrect or this account doesn\'t exist. Please reset your password or create a new account.', 'custom-endpoint' ), array( 'status' => 200 ) );
            }
        }
        else 
        {
            return new WP_Error( 'error', __( 'Your email ID or password is incorrect or this account doesn\'t exist. Please reset your password or create a new account.', 'custom-endpoint' ), array( 'status' => 404 ) );
        }
    }
    elseif(!empty($uid)){
       $user_info =  get_users(array(
            'meta_key' => 'uid',
            'meta_value' => $uid
        ));
        if($user_info){
            return new WP_REST_Response($user_info, 200);
        }else{
            return new WP_Error( 'error', __( 'Your UID is incorrect or this account doesn\'t exist.', 'custom-endpoint' ), array( 'status' => 200 ) );

        }

    }
    else
    {
        return new WP_Error( 'error', __( 'Required parameter(s) is missing', 'custom-endpoint' ), array( 'status' => 200 ) );
    }
}

function ct_post_logout(WP_REST_Request $data){
    wp_destroy_current_session();
    wp_clear_auth_cookie();

    /**
     * Fires after a user is logged-out.
     *
     * @since 1.5.0
     */
    wp_logout();

    return new WP_REST_Response('Logged out', 200);
}

function ct_post_lost_password(WP_REST_Request $data){
    $params = $data->get_params();
    $email = !empty($params['email']) ? $params['email'] : "";
    
    if(!empty($email)){
        $user_id = email_exists($email);
        if($user_id){
            $codeD = $email.$user_id . time();  
            $code = sha1( $codeD );
            $activation_link = site_url()."/resetpassword?token=$code&token_id=$user_id&token_for=$email";
            update_user_meta($user_id, 'forgot_password_token', $code);
            $msg = "Hey,<br><br>
                You sent us a request to reset your password.<br><br>
                Click on the link below for the new password.<br><br>
                <a href='$activation_link'>click here</a> <br><br>
                You didn't send the request? Ignore this email and log into your account as usual.<br><br>
                Thanks,<br>
                Asian Cricket Council<br><br>";
            sendEmail($email, "Request for password reset", $msg);
            return new WP_REST_Response("Your reset password link has been sent to your email. Kindly check your inbox for further procedure.", 200);
        }
        else {
            return new WP_Error( 'error', __( 'This account doesn\'t exist.', 'custom-endpoint' ), array( 'status' => 403 ) );
        }
    }
    else{
        return new WP_Error( 'error', __( 'Required parameter(s) is missing', 'custom-endpoint' ), array( 'status' => 403 ) );
    }
}

function ct_post_reset_password(WP_REST_Request $data)
{
    $params = $data->get_params();

    $token_id   = !empty($params['token_id']) ? $params['token_id'] : "";
    $token   = !empty($params['token']) ? $params['token'] : "";
    $token_for   = !empty($params['token_for']) ? $params['token_for'] : "";
    $password   = !empty($params['password']) ? $params['password'] : "";
    $confirm_password   = !empty($params['confirm_password']) ? $params['confirm_password'] : "";

    if(!empty($token_id) && !empty($token) && !empty($token_for) && !empty($password) && !empty($confirm_password)){
        if($password == $confirm_password){
            $user_id = email_exists($token_for);
            if($user_id){
                $user_data = get_userdata($token_id);
                if($user_data->forgot_password_token == $token && $user_data->user_email == $token_for && $user_data->ID == $token_id){
                    $update_user = wp_update_user( array (
                        'ID' => $token_id, 
                        'user_pass' => $password
                    ));
                    if($update_user){
                        delete_user_meta($user_id, 'forgot_password_token');
                        return new WP_REST_Response("You have successfully reset your password.", 200);
                    }   
                    else{
                        return new WP_Error( 'error', __( 'The link has been expired.', 'custom-endpoint' ), array( 'status' => 200 ) );
                    }
                }    
                else{
                    return new WP_Error( 'error', __( 'The link has been expired.', 'custom-endpoint' ), array( 'status' => 200 ) );
                }
            }     
            else{
                return new WP_Error( 'error', __( 'User doesn\'t exist', 'custom-endpoint' ), array( 'status' => 200 ) );
            }
        }      
        else{
            return new WP_Error( 'error', __( 'Password and confirm password must be same.', 'custom-endpoint' ), array( 'status' => 200 ) );
        }
    }
    else{
        return new WP_Error( 'error', __( 'Required parameter(s) is missing', 'custom-endpoint' ), array( 'status' => 200 ) );
    }
}

