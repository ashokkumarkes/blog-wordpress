
<?php get_header();?>
<?php while (have_posts()) {
      the_post(); ?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg')?>)"></div>
      <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome, <?php echo the_archive_title();?> !</h1>
        <div class="page-banner__intro">
          <p> <?php echo the_archive_description();?></p>
        </div>        
      </div>
</div>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Blog Home</a> <span class="metabox__main"><?php echo the_title();?><?php the_author_posts_link();?> : <?php the_time('F j, Y');?> in <?php echo get_the_category_list(', ');?></span>
        </p>
    </div>
    <div class="generic-content">
        <p><?php the_content();?></p>
    </div>
    <?php } ?>
</div>
<?php get_footer();?>