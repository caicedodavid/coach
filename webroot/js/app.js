 $(document).ready(function(){
      var d = new Date();
      d.setDate(d.getDate() - 1);

      var birthdateOptions={
        format: 'YYYY-MM-DD',
        viewMode: 'years',
        maxDate: d,
        useCurrent: false
      };
      var sessionOptions={
        format: 'YYYY-MM-DD HH:mm',
        stepping: 5,
        minDate: d,
        disabledDates: [d],
        sideBySide:true
      };
      $('#date').datetimepicker(birthdateOptions);
      $('#date1').datetimepicker(sessionOptions);
 
      $(document).on('click', '#pagination-button a', function () {
          var thisHref = $(this).attr('href');
          if (!thisHref) {
              return false;
          }
          $.get(thisHref, function(data) {
              var users = $(data).find('#items');
              var paging = $(data).find('div.paging');
              $('#items').append(users);
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

      $('#accept').click(function(){
        var param1 = $(this).attr("name"); 
        $.ajax({
          url:"approveSession",       
          type:"POST",
          dataType : 'json',
          async: true,             
          data:{session:param1},               
          success:function(data)
          {
            $(this).closest(".row").replaceWith('<div class="alert alert-success"><strong>The session was scheduled.</div>');
          }
        });
      });

      $('#start').click(function(){
        var sessionId = $(this).attr("name"); 
        var url = "/sessions/updateStartTime/"+ sessionId;
        $.ajax({
          url: url,
          type:"GET",
          dataType : 'json',
          async: true,
        });
      });

      $('.rate-input').rating({displayOnly: true, step: 0.5});

      $( "#topic-selector" ).change(function() {
        var coachId = $(this).attr("coach-id");
        var topicId= this.options[this.selectedIndex].value
        var url = "/sessions/add/" + coachId + "/" + topicId;
        console.log(url);
        window.location.replace(url);
      });
 });