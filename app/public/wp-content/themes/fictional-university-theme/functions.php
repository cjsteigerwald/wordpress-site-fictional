<?php 

require get_theme_file_path('/inc/search-route.php');

function university_custom_rest() {
  register_rest_field('post', 'author_name', array(
    'get_callback' => function() {return get_author_name();},
  ));
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args = NULL) {
  
  if (!$args['title']) {
    $args['title'] = get_the_title();
  }

  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!$args['photo']) {
    if (get_field('page_banner_background_image') AND !is_archive() AND !is_home() ) {
      $args['photo'] = get_field('page_banner_background_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }

  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title']; ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>  
  </div>
<?php }


  function university_files() {
    wp_enqueue_style('custom-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');    
    wp_enqueue_style('font-awsome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_script('googleMap', '//maps.googleapis.com/maps/api/js?key=AIzaSyAEdABXm-C0dhstfaqewQoMrD7y3KiVibw', NULL, '1.0', true);
    if (strstr($_SERVER['SERVER_NAME'], 'fictional-university.local')) {
      wp_enqueue_script('main-university-js', 'http://localhost:3000/bundled.js', NULL, '1.0', true);
    } else {
      wp_enqueue_script('our-vendors-js', get_theme_file_uri('/bundled-assets/vendors~scripts.6acc14197e9cd6afafcc.js'), NULL, '1.0', true);
      wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.13d81c5ef3e64cd57228.js'), NULL, '1.0', true);
      wp_enqueue_style( 'our-main-styles', get_theme_file_uri('/bundled-assets/styles.13d81c5ef3e64cd57228.css'));
    }
    wp_localize_script('main-university-js', 'universityData', array(
      'root_url' => get_site_url(),
    ));
  }

  add_action('wp_enqueue_scripts', 'university_files');

  function university_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_image_size('professorLandscape', 400, 260, true);
    add_image_size('professorPortrait', 480, 650, true);
    add_image_size('pageBanner', 1500, 350, true);

    // register_nav_menu( 'headerMenuLocation', 'Header Menu Location' );
    // register_nav_menu( 'footeLocationOne', 'Footer Location One' );
    // register_nav_menu( 'footeLocationTwo', 'Footer Location Two' );    
  }
  add_action('after_setup_theme', 'university_features');

  
  function university_adjust_queries($query) {
    // display all campuses on google map
    if(!is_admin() AND is_post_type_archive('campus') AND $query->is_main_query()) {      
      $query->set('posts_per_page', '-1');
    }
    // display all programs by title in ascending order
    if(!is_admin() AND is_post_type_archive('program') AND $query->is_main_query()) {      
      $query->set('orderby', 'title');
      $query->set('order', 'ASC');
      $query->set('posts_per_page', '-1');
    }

    // display all events after current day in ascending order
    if(!is_admin() AND is_post_type_archive('event') AND $query->is_main_query()) {
      $today = date('Ymd');
      $query->set('meta_key', 'event_date');
      $query->set('orderby', 'meta_value_num');
      $query->set('order', 'ASC');
      $query->set('meta_query', array(
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric',
      ));      
    }
  }

  add_action('pre_get_posts', 'university_adjust_queries');

  // For Google API key 
  function universityMapKey($api) {
    $api['key'] = 'AIzaSyAEdABXm-C0dhstfaqewQoMrD7y3KiVibw';
    return $api;
  }

  add_filter('acf/fields/google_map/api', 'universityMapKey');

?>
