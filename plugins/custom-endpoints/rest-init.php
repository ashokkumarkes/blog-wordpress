<?php

/* =======================================================================
                       Header & Footer Data
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_headers');
add_action( 'rest_api_init', 'rest_api_get_footers');

/* =======================================================================
                            Homepage 
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_live_streaming');
add_action( 'rest_api_init', 'rest_api_get_tournament_stats');
add_action( 'rest_api_init', 'rest_api_get_latest_posts');
add_action( 'rest_api_init', 'rest_api_get_fixtures_slider');
add_action( 'rest_api_init', 'rest_api_get_news_slider');
add_action( 'rest_api_init', 'rest_api_get_team_types');
add_action( 'rest_api_init', 'rest_api_get_gallery_slider');
add_action( 'rest_api_init', 'rest_api_get_videos_slider');
add_action( 'rest_api_init', 'rest_api_get_about_acc_section');
// add_action( 'rest_api_init', 'rest_api_get_partners');
add_action( 'rest_api_init', 'rest_api_get_members_list');

/* =======================================================================
                        About Us 
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_annual_reports');
add_action( 'rest_api_init', 'rest_api_get_board_members');
add_action( 'rest_api_init', 'rest_api_get_member_contacts');

/* =======================================================================
                        News
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_news');

/* =======================================================================
                       Video Detail Page 
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_video_detail');

/* =======================================================================
                      Gallery Detail Page 
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_gallery_detail');

/* =======================================================================
                        Fixtures 
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_fixtures');

/* =======================================================================
                        Teams 
========================================================================== */
add_action( 'rest_api_init', 'rest_api_get_teams');
add_action( 'rest_api_init', 'rest_api_get_single_team');

/* =======================================================================
                    Contact Form 
========================================================================== */
add_action( 'rest_api_init', 'rest_api_post_form_submit');

/* =======================================================================
                    Ultimate Member
========================================================================== */
add_action( 'rest_api_init', 'rest_api_post_register');
add_action( 'rest_api_init', 'rest_api_post_login');
add_action( 'rest_api_init', 'rest_api_post_logout');