<?php 

add_action('rest_api_init', 'universityRegisterSearch');

  function universityRegisterSearch() {
    // $NAMESPACE, $ROUTE, array that describes what happens at url
    register_rest_route('university/v1', 'search', array(
      'methods' => WP_REST_SERVER::READABLE,
      'callback' => 'universitySearchResults'
    ));
  }

  

  function universitySearchResults($param) {
    $mainQuery = new WP_Query(array(
      'post_type' => array('post', 'page', 'professor', 'program', 'event', 'campus'),
      's' => sanitize_text_field($param['term']),
      'posts_per_page' => 10,
    ));

    $results = array(
      'generalInfo' => array(),
      'professors' => array(),
      'programs' => array(),
      'events' => array(),
      'campuses' => array(),
    );

    while($mainQuery->have_posts()) {
      $mainQuery->the_post();

      if (get_post_type() == 'post' OR get_post_type() == 'page') {
        array_push($results['generalInfo'], array(
          'post_type' => get_post_type(),
          'title' => get_the_title(),
          'permalink' => get_the_permalink('professorLandscape'),
          'author_name' => get_the_author(),
        ));
      }
      if (get_post_type() == 'professor') {
        array_push($results['professors'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
        ));
      }

      if (get_post_type() == 'program') {
        $relatedCampuses = get_field('related_campus');
        if ($relatedCampuses){
          foreach($relatedCampuses as $campus){
            array_push($results['campuses'], array(
              'title' => get_the_title($campus),
              'permalink' => get_the_permalink($campus), 
            ));
          }
        } // end if
        array_push($results['programs'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'id' => get_the_id(),
        ));
      } // end program
      if (get_post_type() == 'event') {
        $eventDate = new DateTime(get_field('event_date'));
        $description = null;
        if (has_excerpt()) {
          $description = get_the_excerpt();
        } else {
          $description = wp_trim_words(get_the_content(), 18);
        }
        array_push($results['events'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
          'month' => $eventDate->format('M'),
          'day' => $eventDate->format('d'),
          'description' => $description,
        ));
      }
      if (get_post_type() == 'campus') {
        array_push($results['campuses'], array(
          'title' => get_the_title(),
          'permalink' => get_the_permalink(),
        ));
      }
    } // end while


    // Query of professor(s) based on program taught
    if ($results['programs']) {
      
      $programsMetaQuery = array('relation' => 'OR');
  
      // create inner meta_query based on number of programs 
      // that match search term
      foreach($results['programs'] as $item) {
        array_push($programsMetaQuery, 
          array(
            'key' => 'related_programs',
            'compare' => 'LIKE',
            'value' => '"' . $item['id'] . '"',
          )
        ); // end array_push
      }
  
      $programRelationshipQuery = new WP_Query(array(
        'post_type' => array('professor', 'event'),
        'meta_query' => $programsMetaQuery,
      ));
  
      while($programRelationshipQuery->have_posts()) {
        $programRelationshipQuery->the_post();

        if (get_post_type() == 'event') {
          $eventDate = new DateTime(get_field('event_date'));
          $description = null;
          if (has_excerpt()) {
            $description = get_the_excerpt();
          } else {
            $description = wp_trim_words(get_the_content(), 18);
          }
          array_push($results['events'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'month' => $eventDate->format('M'),
            'day' => $eventDate->format('d'),
            'description' => $description,
          ));
        } // end event
        
        if (get_post_type() == 'professor') {
          array_push($results['professors'], array(
            'title' => get_the_title(),
            'permalink' => get_the_permalink(),
            'image' => get_the_post_thumbnail_url(0, 'professorLandscape'),
          ));
        } // end professors
      }
  
      // remove duplicates added to array due to query
      $results['professors'] = array_values(array_unique($results['professors'], SORT_REGULAR));
      $results['events'] = array_values(array_unique($results['events'], SORT_REGULAR));
    }

    

    return $results;
  }

?>