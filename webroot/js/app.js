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

      $('#stripe').click(function(){
        var handler = StripeCheckout.configure({
          key: 'pk_test_6vZZTdRK1MJDCYXl4KwgMmI8',
          image: 'https://stripe.com/img/documentation/checkout/marketplace.png',
          locale: 'auto',
          token: function(token) {
            console.log(token);
            var $tokenInput = $('#token-input');
            $tokenInput.replaceWith($('<input type="hidden" name="token_id" />').val(token['id']));
          }
        }); 
        handler.open({
          name: 'Stripe.com',
          description: '2 widgets',
          zipCode: true,
        });
        window.addEventListener('popstate', function() {
          handler.close();
        })
      })

    $(function() {
      var $form = $('#payment-form');
      $form.submit(function(event) {
        console.log('oño...')
        // Disable the submit button to prevent repeated clicks:
        $form.find('.submit').prop('disabled', true);
    
        // Request a token from Stripe:
        Stripe.card.createToken($form, stripeResponseHandler);
    
        // Prevent the form from being submitted:
        return false;
      });
    });

    function stripeResponseHandler(status, response) {
      // Grab the form:
      var $form = $('#payment-form');
    
      if (response.error) { // Problem!
    
        // Show the errors on the form:
        $form.find('.payment-errors').text(response.error.message);
        $form.find('.submit').prop('disabled', false); // Re-enable submission
    
      } else { // Token was created!
    
        // Get the token ID:
        var token = response.id;
        
        // Insert the token ID into the form so it gets submitted to the server:
        var $tokenInput = $('#token-input');
        $tokenInput.replaceWith($('<input type="hidden" name="token_id" />').val(token));
    
        // Submit the form:
        $form.get(0).submit();
      }
    };
      
 });