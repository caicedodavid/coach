 $(document).ready(function(){
      var date_input=$('input[name="date"]'); //our date input has the name "date"
      var container=$('.bootstrap-iso form').length>0 ? $('.bootstrap-iso form').parent() : "body";
      var options={
        format: 'yyyy-mm-dd',
        container: container,
        todayHighlight: true,
        autoclose: true,
        startView: 'years',
      };
      date_input.datepicker(options);

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

 });
