
<?php get_header();?>
<?php while (have_posts()) {
      the_post(); ?>    
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg')?>)"></div>
      <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome, <?php echo get_the_title();?> !</h1>
        <div class="page-banner__intro">
          <p><?php echo get_the_title();?></p>
        </div>        
      </div>
</div>
<div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
            <a class="metabox__blog-home-link" href="<?php echo site_url('/blog'); ?>"><i class="fa fa-home" aria-hidden="true"></i> Blog Home</a> <span class="metabox__main"></span>
        </p>
    </div>
    <div class="generic-content">
        <p><?php the_content();?></p>
    </div>
    <?php } ?>
</div>
<?php get_footer();?>