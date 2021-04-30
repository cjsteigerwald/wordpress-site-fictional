<?php 

require get_theme_file_path('/inc/search-route.php');

function university_custom_rest() {
  register_rest_field('post', 'author_name', array(
    'get_callback' => function() {return get_author_name();},
  ));

  register_rest_field('note', 'userNoteCount', array(
    'get_callback' => function() {return count_user_posts(get_current_user_id(), 'note');}
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
      wp_enqueue_script('main-university-js', get_theme_file_uri('/bundled-assets/scripts.54849d39ccc1ed510bc0.js'), NULL, '1.0', true);
      wp_enqueue_style( 'our-main-styles', get_theme_file_uri('/bundled-assets/styles.54849d39ccc1ed510bc0.css'));
    }
    wp_localize_script('main-university-js', 'universityData', array(
      'root_url' => get_site_url(),
      'nonce' => wp_create_nonce('wp_rest'),
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

  // redirect subscriber account out of admin and onto homepage
  function redirectSubsToFrontend() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      wp_redirect(site_url('/'));
      exit;
    }
  }
  add_action('admin_init', 'redirectSubsToFrontend');

  // remove admin bar for Subscriber Account
  function noSubsAdminBar() {
    $ourCurrentUser = wp_get_current_user();
    if (count($ourCurrentUser->roles) == 1 AND $ourCurrentUser->roles[0] == 'subscriber') {
      add_filter('show_admin_bar', '__return_false', PHP_INT_MAX);
    }
  }
  add_action('wp_loaded', 'noSubsAdminBar');

  // Customize Login Screen
  function ourHeaderUrl() {
    return esc_url(site_url('/'));
  }
  add_filter('login_headerurl', 'ourHeaderUrl');


  // Load custom CSS scripts on login page
  function ourLoginCSS() {
    wp_enqueue_style( 'our-main-styles', get_theme_file_uri('/bundled-assets/styles.54849d39ccc1ed510bc0.css'));
    wp_enqueue_style('custom-font', 'https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');  
  }
  add_action('login_enqueue_scripts', 'ourLoginCSS');


  // Change login title from Wordpress to custom
  function ourLoginTitle() {
    return get_bloginfo('name');
  }
  add_filter('login_headertitle', 'ourLoginTitle');

  // Enforce note posts to be private
  function makeNotePrivate($data, $postarr) {
    if ($data['post_type'] == 'note') {
      if (count_user_posts(get_current_user_id(), 'note')  > 4 AND !$postarr['ID']) {
        die("You reached your note limit.");
      }
      $data['post_content'] = sanitize_textarea_field($data['post_content']);
      $data['post_title'] = sanitize_text_field($data['post_title']);
    }
    if ($data['post_type'] == 'note' AND $data['post_status'] != 'trash') {
      $data['post_status'] = 'private';
    }
    return $data;
  }
  // Arguments: hook, function to call, priority(of callback function running), number of arguments(default is 1)
  // priority: important if running more than 1 method on a hook, lower number earlier it runs, higher number later it runs
  add_filter('wp_insert_post_data', 'makeNotePrivate', 10, 2);


?>
