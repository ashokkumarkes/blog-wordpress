<?php

/* =======================================================================
                        Header & Footer Data
========================================================================== */
function rest_api_get_headers($request) {
    register_rest_route( 'custom-endpoints/v1', 'getheader', array(
      'methods' => 'GET',
      'callback' => 'ct_get_headers',
    ) );
}

function rest_api_get_footers($request) {
    register_rest_route( 'custom-endpoints/v1', 'getfooter', array(
      'methods' => 'GET',
      'callback' => 'ct_get_footers',
    ) );
} 

/* =======================================================================
                            Homepage 
========================================================================== */
function rest_api_get_live_streaming($request) {
    register_rest_route( 'custom-endpoints/v1', 'live_streaming', array(
      'methods' => 'GET',
      'callback' => 'ct_get_live_streaming',
    ) );
} 
function rest_api_get_tournament_stats($request) {
    register_rest_route( 'custom-endpoints/v1', 'tournament_stats', array(
      'methods' => 'GET',
      'callback' => 'ct_get_tournament_stats',
    ) );
}
function rest_api_get_latest_posts($request){
    register_rest_route( 'custom-endpoints/v1', 'latest_posts', array(
        'methods' => 'GET',
        'callback' => 'ct_latest_posts',
    ) );
}
function rest_api_get_fixtures_slider($request) {
    register_rest_route( 'custom-endpoints/v1', 'fixtures_slider', array(
      'methods' => 'GET',
      'callback' => 'ct_get_fixtures_slider',
    ) );
} 
function rest_api_get_news_slider($request) {
    register_rest_route( 'custom-endpoints/v1', 'news_slider', array(
      'methods' => 'GET',
      'callback' => 'ct_get_news_slider',
    ) );
} 
function rest_api_get_team_types($request) {
    register_rest_route( 'custom-endpoints/v1', 'team_list', array(
      'methods' => 'GET',
      'callback' => 'ct_get_team_types',
    ) );
}
function rest_api_get_gallery_slider($request) {
    register_rest_route( 'custom-endpoints/v1', 'gallery_slider', array(
      'methods' => 'GET',
      'callback' => 'ct_get_gallery_slider',
    ) );
}
function rest_api_get_videos_slider($request) {
    register_rest_route( 'custom-endpoints/v1', 'videos_slider', array(
      'methods' => 'GET',
      'callback' => 'ct_get_videos_slider',
    ) );
}
function rest_api_get_about_acc_section($request) {
    register_rest_route( 'custom-endpoints/v1', 'about_acc_section', array(
      'methods' => 'GET',
      'callback' => 'ct_get_about_acc_section',
    ) );
}
// function rest_api_get_partners($request) {
//     register_rest_route( 'custom-endpoints/v1', 'partners', array(
//       'methods' => 'GET',
//       'callback' => 'ct_get_partners',
//     ) );
// }
function rest_api_get_members_list($request) {
    register_rest_route( 'custom-endpoints/v1', 'members_list', array(
      'methods' => 'GET',
      'callback' => 'ct_get_members_list',
    ) );
}

/* =======================================================================
                            About Us 
========================================================================== */
function rest_api_get_annual_reports($request) {
    register_rest_route( 'custom-endpoints/v1', 'annual_reports', array(
      'methods' => 'GET',
      'callback' => 'ct_get_annual_reports',
    ) );
}
function rest_api_get_board_members($request) {
    register_rest_route( 'custom-endpoints/v1', 'board_members', array(
      'methods' => 'GET',
      'callback' => 'ct_get_board_members',
    ) );
}
function rest_api_get_member_contacts($request) {
    register_rest_route( 'custom-endpoints/v1', 'member_contacts', array(
      'methods' => 'GET',
      'callback' => 'ct_get_member_contacts',
    ) );
}

/* =======================================================================
                           News 
========================================================================== */
function rest_api_get_news($request) {
    register_rest_route( 'custom-endpoints/v1', 'news', array(
      'methods' => 'GET',
      'callback' => 'ct_get_news',
    ) );
}

/* =======================================================================
                       Video Detail Page 
========================================================================== */
function rest_api_get_video_detail($request) {
    register_rest_route( 'custom-endpoints/v1', 'video_detail', array(
      'methods' => 'GET',
      'callback' => 'ct_get_video_detail',
    ) );
}

/* =======================================================================
                      Gallery Detail Page 
========================================================================== */
function rest_api_get_gallery_detail($request) {
    register_rest_route( 'custom-endpoints/v1', 'gallery_detail', array(
      'methods' => 'GET',
      'callback' => 'ct_get_gallery_detail',
    ) );
}

/* =======================================================================
                         Fixtures 
========================================================================== */
function rest_api_get_fixtures($request) {
    register_rest_route( 'custom-endpoints/v1', 'fixtures', array(
      'methods' => 'GET',
      'callback' => 'ct_get_fixtures',
    ) );
}

/* =======================================================================
                          Teams 
========================================================================== */
function rest_api_get_teams($request) {
    register_rest_route( 'custom-endpoints/v1', 'teams', array(
      'methods' => 'GET',
      'callback' => 'ct_get_teams',
    ) );
}
function rest_api_get_single_team($request) {
    register_rest_route( 'custom-endpoints/v1', 'single_team', array(
      'methods' => 'GET',
      'callback' => 'ct_get_single_team',
    ) );
}

/* =======================================================================
                        Contact Form 
========================================================================== */
function rest_api_post_form_submit($request) {
    register_rest_route( 'custom-endpoints/v1', 'form_submit', array(
      'methods' => 'POST',
      'callback' => 'ct_post_form_submit',
    ) );
}

/* =======================================================================
                    Ultimate Member
========================================================================== */
function rest_api_post_register($request) {
    register_rest_route( 'custom-endpoints/v1', 'register', array(
      'methods' => 'POST',
      'callback' => 'ct_post_register',
    ) );
}

function rest_api_post_login($request) {
    register_rest_route( 'custom-endpoints/v1', 'login', array(
      'methods' => 'POST',
      'callback' => 'ct_post_login',
    ) );
}

function rest_api_post_logout($request) {
    register_rest_route( 'custom-endpoints/v1', 'logout', array(
      'methods' => 'POST',
      'callback' => 'ct_post_logout',
    ) );
}

function rest_api_post_lost_password($request)
{
    register_rest_route( 'custom-endpoints/v1', 'lost_password', array(
        'methods' => 'GET',
        'callback' => 'ct_post_lost_password',
    ) );
}

function rest_api_post_reset_password($request)
{
    register_rest_route( 'custom-endpoints/v1', 'reset_password', array(
        'methods' => 'POST',
        'callback' => 'ct_post_reset_password',
    ) );
}