 $(document).ready(function(){
      var birthdateOptions={
        format: 'yyyy-mm-dd',
        container: '#date',
        todayHighlight: true,
        autoclose: true,
        startView: 2,
      };
      var sessionOptions={
        format: 'yyyy-mm-dd',
        container: '#date1',
        todayHighlight: true,
        startDate: new Date(), 
        autoclose: true,
      };
      $('#date').datepicker(birthdateOptions);
      $('#date1').datepicker(sessionOptions);

      $('.timepicker').timepicker({
          timeFormat: 'HH:mm',
          interval: 30,
          minTime: '00',
          maxTime: '23:30',
          defaultTime: '12',
          startTime: '00',
          dynamic: false,
          dropdown: true,
          scrollbar: true
      });
 
 
      $(document).on('click', '#pagination-button a', function () {
          var thisHref = $(this).attr('href');
          if (!thisHref) {
              return false;
          }
          $.get(thisHref, function(data) {
              var users = $(data).find('#users');
              var paging = $(data).find('div.paging');
              $('#users').append(users);
              $( "div.paging" ).replaceWith(paging);
              $('span.stars').stars();
          });
          return false;
      });
 
      $('div.pagination').on('click','a', function () {
          var thisHref = $(this).attr('href');
          $('$ajaxContainer').load(thisHref, function() {
              $('html, body').animate({
                  scrollTop: $('$ajaxContainer').offset().top
              }, 200);
          });
          return false;
      });
 
      $.fn.stars = function() {
          return $(this).each(function() {
              // Get the value
              var val = parseFloat($(this).data('rating'));
              // Make sure that the value is in 0 - 5 range, multiply to get width
              var size = Math.max(0, (Math.min(5, val))) * 16;
              // Create stars holder
              var $span = $('<span />').width(size);
              // Replace the numerical value with stars
              $(this).html($span);
          });
      };
 
      $(function() {
          $('span.stars').stars();
      });

      $( function() {
       $( "#tabs" ).tabs({
         beforeLoad: function( event, ui ) {
           ui.jqXHR.fail(function() {
             ui.panel.html("Couldn't load this tab. We'll try to fix this as soon as possible. " );
           });
         }
       });
      });

      $(document).ready(function() {
        $(document).on('click', '.paging a', function () {
          var thisHref = $(this).attr('href');
          if (!thisHref) {
            return false;
          }
          $('#pagination-container').fadeTo(300, 0);

          $('#pagination-container').load(thisHref.concat(' #pagination-container'), function() {
            $(this).fadeTo(200, 1);
          });
          return false;
        });
      });
 });