<?php  get_header(); ?>
<div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo get_theme_file_uri('/images/library-hero.jpg')?>)"></div>
      <div class="page-banner__content container t-center c-white">
        <h1 class="headline headline--large">Welcome, to our blogs!</h1>
        <div class="page-banner__intro">
          <p> The test blog</p>
        </div>        
      </div>
</div>
<div class="container container--narrow page-section">
  <?php 
  // $args = array(
  //   'post_type' => array('events','blog','about-us')
  // );
  // $args = 'post_type = blog';
  // $query = new WP_Query( $args );
  //     echo"<pre>";
  //     print_r($query->posts);
  //     exit;
  ?>
  <?php 
    while (have_posts()) {
      the_post(); ?>
      <div class="post-item">
        <h2 class="headline headline--medium headline--post-title">
          <a href="<?php echo the_permalink();?>" ><?php echo the_title();  ?> </a>
        </h2>
        <div class="metabox">
          <p>Posted by <?php the_author_posts_link();?> : <?php the_time('F j, Y');?> in <?php echo get_the_category_list(', ');?></p>
        </div>
        <div class="generic-content">
          <p><?php the_content();?></p>
          <!-- add more than 1 paragrap in one paragraph--> 
          <p><?php the_excerpt();?></p>
          <p><a class="btn btn--blue" href="<?php the_permalink();?>">Continue reading >></a></p>
        </div>
      </div>
    <?php }
    echo paginate_links();
  ?>
</div>
<?php get_footer(); ?>


