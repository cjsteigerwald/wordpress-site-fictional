<div class="post-item">
  <h2 class="headline headline--medium headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
  
  <div class="generic-content">
  <p><?php 
    // $content = the_field('main_body_content');
    // $hacked = substr($content, 0, 5);
    if (has_excerpt()) {
      echo get_the_excerpt();
    } else {
      echo wp_trim_words( get_field('main_body_content'), 18,);
    }?><a href="<?php the_permalink(); ?>" class="nu gray"> Read more</a></p>
    <p><a class="btn btn--blue" href="<?php the_permalink(); ?>">View program &raquo;</a></p>
  </div>

</div>