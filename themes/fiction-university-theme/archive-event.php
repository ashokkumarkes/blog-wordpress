
<?php get_header();?>
<?php while (have_posts()) {
      the_post(); ?>
        <div class="event-summary">
          <a class="event-summary__date t-center" href="<?php echo the_permalink();?>">
            <span class="event-summary__month"><?php echo the_time('M');?></span>
            <span class="event-summary__day"><?php echo the_time('d');?></span>
          </a>
          <div class="event-summary__content">
            <h5 class="event-summary__title headline headline--tiny"><a href="<?php echo the_permalink();?>"><?php echo get_the_title();?></a></h5>
            <p><?php echo wp_trim_words(get_the_content(),20); ?> <a href="<?php echo site_url('/events')?>" class="nu gray">Learn more</a></p>
          </div>
        </div>
<?php } get_footer();?>