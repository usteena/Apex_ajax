export default {
  init() {
    // JavaScript to be fired on all pages
    const loadBackground = () => {
      var hero = $('.hero-section');
      var bg = $(hero).data('background');

      $(hero).css({
        'background-image': 'url('+bg+')',
      });
    }

    const loadContentOnClick = () => {

      $(document).on('click', '.post-link', function() {
        var id = $(this).data('id');

        if(id) {
          var ajaxUrl = $('.hero-section').data('ajax');
          var hero = $('.hero-section');
          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'load_content',
              id, //id: id 
            },
            // dataType: 'JSON',
            beforeSend: function() {
              console.log('Before send');
              $('#newsContent').html('<span class="loader"></span>');
            },
            success: function (data) {
              var returnedData = JSON.parse(data);
              console.log(returnedData);
              $('.loader').hide();
              $(hero).css({
                'background-image': 'url('+returnedData.image.url+')',
              });
              $('#newsContent').html(returnedData.html);
            },
          });
        }
      });
    }
    const filterLegends = () => {

      $(document).on('click', '.filter-btn', function() {
        var id = $(this).data('catid');

        if(id) {
          var ajaxUrl = $('.hero-section').data('ajax');
          $.ajax({
            url: ajaxUrl,
            type: 'POST',
            data: {
              action: 'filter_content',
              id, //id: id 
            },
            // dataType: 'JSON',
            beforeSend: function() {
              console.log('Before send');
              $('.posts-list').html('<span class="loader"></span>');
            },
            success: function (data) {
              var returnedData = JSON.parse(data);
              console.log(returnedData);
              $('.loader').hide();
              $('.posts-list').html(returnedData.html);
            },
          });
        }
      });
    }

    $(window).on('load', function () {
      loadBackground();
      loadContentOnClick();
      filterLegends();
    });

  },
  finalize() {
    // JavaScript to be fired on all pages, after page specific JS is fired
  },
};
