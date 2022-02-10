<section class="hero-section" data-background="http://news.test/app/themes/news/resources/assets/images/hero-bg.jpg" data-ajax="<?= admin_url('admin-ajax.php'); ?>">
  <span class="overlay"></span>
  <div id="content" class="flex flex-col">

    <div class="filters">
      @php
        $categories = get_categories(array('taxonomy' => 'category', 'hide_empty' => true));
      @endphp
      @foreach ($categories as $category)
          @if ($category)
              <a href="#" data-catid="{{$category->term_id}}" class="filter-btn btn">{{$category->name}}</a>
          @endif
      @endforeach
    </div>

    <div class="main-content flex">
    <div class="left">

    @php

    $args = array(
      'post_type' => 'post',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'order' => 'DESC',
      'orderby' => 'date'
    );

    $posts = new WP_Query($args);

@endphp
     {{-- Posts query --}}
      @if ($posts->have_posts()) 
        <div class="posts-list gap-4">
        @while ($posts->have_posts())
        @php
           $posts->the_post();
           $title = get_the_title();
           $content = get_the_content();
           $image = get_the_post_thumbnail_url( get_the_ID(), 'small' );
           $trimmed_content = wp_trim_words( $content, 10, '...' );
        @endphp
          <div class="single-post bg-gray-200 grid grid-cols-3 items-center relative">
            <a href="#" class="absolute w-full h-full z-10 post-link" data-id="<?= get_the_id() ?>"></a>
            <div class="wrap ">
              <img class="h-24 col-span-1" src="{{ $image }}" alt="">
            </div>
            <div class="col-span-2">
              <h4 class="post-title text-base font-light lg:font-medium lg:text-2xl">{!! $title !!}</h4>
              <p class="text-xs">{!! $trimmed_content !!} </p>

            </div> 
          </div>
        @endwhile
      </div>
      @endif
      @php
          wp_reset_query();
      @endphp
    </div>

    <div class="right text-white">
      {{-- @php
          $post = $posts[0];
      @endphp --}}
      <div class="wrapper" id="newsContent">
        <h1>{!! $title !!}</h1>
        <div class="content">
          {!! $content !!}
        </div>
      </div>
    </div>
  </div>
  </div>
</section>