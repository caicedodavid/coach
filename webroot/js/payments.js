$(document).ready(function(){
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