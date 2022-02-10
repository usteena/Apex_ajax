<?php

namespace App;

/**
 * Theme customizer
 */
add_action('customize_register', function (\WP_Customize_Manager $wp_customize) {
    // Add postMessage support
    $wp_customize->get_setting('blogname')->transport = 'postMessage';
    $wp_customize->selective_refresh->add_partial('blogname', [
        'selector' => '.brand',
        'render_callback' => function () {
            bloginfo('name');
        }
    ]);
});

/**
 * Customizer JS
 */
add_action('customize_preview_init', function () {
    wp_enqueue_script('sage/customizer.js', asset_path('scripts/customizer.js'), ['customize-preview'], null, true);
});

add_action('wp_ajax_load_content', __NAMESPACE__.'\\load_content');
add_action('wp_ajax_nopriv_load_content', __NAMESPACE__.'\\load_content');

add_action('wp_ajax_filter_content', __NAMESPACE__.'\\filter_content');
add_action('wp_ajax_nopriv_filter_content', __NAMESPACE__.'\\filter_content');

function load_content () {
  $response = array();

  $id = isset($_POST['id']) ? $_POST['id'] : false;

  if(!$id) {
    echo json_encode(['code'=>404, 'msg'=>'Some thing is wrong! Try again.']);
    die;
  }

  $post = get_post($id);

  $bg_img = get_field('bg_img', $id);

  ob_start();
  ?>
      <h1><?= $post->post_title; ?></h1>
      <div class="content">
        <?= $post->post_content; ?>
      </div>
  <?php

  // Content we want to load
  $response['html'] .= ob_get_contents();
  $response['image'] = $bg_img;
  ob_end_clean();
  if($response)
  {
      echo json_encode(['code'=>200, 'html' => $response['html'], 'image' => $response['image']]);
  }
  else{
      echo json_encode(['code'=>404, 'msg'=>'Some thing is wrong! Try again.']);
  }
  die;
}

function filter_content () {
  $response = array();
  $id = isset($_POST['id']) ? $_POST['id'] : false;

  $args = array(
    'post_type' => 'post',
    'posts_per_page' => -1,
    'status' => 'publish',
    'category__in' => $id,
  );

  $filtered_posts = new \WP_Query($args);

  if(!$id) {
    echo json_encode(['code'=>404, 'msg'=>'Some thing is wrong! Try again.']);
    die;
  }

  ob_start();
  if ($filtered_posts->have_posts()):
  while ($filtered_posts->have_posts()):
  
     $filtered_posts->the_post();
     $title = get_the_title();
     $content = get_the_content();
     $image = get_the_post_thumbnail_url( get_the_ID(), 'small' );
     $trimmed_content = wp_trim_words( $content, 10, '...' );
    ?>
    <div class="single-post bg-gray-200 grid grid-cols-3 items-center relative">
      <a href="#" class="absolute w-full h-full z-10 post-link" data-id="<?= get_the_id() ?>"></a>
      <div class="wrap ">
        <img class="h-24 col-span-1" src="<?= $image ?>" alt="">
      </div>
      <div class="col-span-2">
        <h4 class="post-title text-base font-light lg:font-medium lg:text-2xl"><?= $title ?></h4>
        <p class="text-xs"><?= $trimmed_content ?> </p>

      </div> 
    </div>
    <?php
  endwhile;
endif;
      
           
  

  // Content we want to load
  $response['html'] .= ob_get_contents();
  ob_end_clean();
  if($response)
  {
      echo json_encode(['code'=>200, 'html' => $response['html']]);
  }
  else{
      echo json_encode(['code'=>404, 'msg'=>'Some thing is wrong! Try again.']);
  }
  die;
}