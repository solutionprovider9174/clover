<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>CloverServer Usage for payment secure</title>
    <script src="https://cdn.polyfill.io/v3/polyfill.min.js"></script>
    <script src="https://checkout.sandbox.dev.clover.com/sdk.js"></script>
</head>


<body>

    <form action="charge.php" method="post" id="payment-form">
        
        <div class="form-row top-row" style="height:20px; width:180px;">
          <div id="amount" class="field card-number">
            <input name="amount" placeholder="Amount">
          </div>
        </div>
      
        <div class="form-row top-row" style="height:20px;width:180px;">
          <div id="card-number" class="field card-number">
            <!-- <input name="card-number" placeholder="Card Number"> -->
          </div>
          <div class="input-errors" id="card-number-errors" role="alert"></div>
        </div>
      
        <div class="form-row" style="height:20px;width:180px;">
          <div id="card-date" class="field third-width">
            <!-- <input name="card-date" placeholder="Expiration (MM/YY)"> -->
          </div>
          <div class="input-errors" id="card-date-errors" role="alert"></div>
        </div>
        
        <div class="form-row" style="height:20px;width:180px;">
          <div id="card-cvv" class="field third-width">
            <!-- <input name="card-cvv" placeholder="CVV"> -->
          </div>
          <div class="input-errors" id="card-cvv-errors" role="alert"></div>
        </div>
          
        <div class="form-row" style="height:20px;width:180px;">
          <div id="card-postal-code" class="field third-width">
            <!-- <input name="card-postal-code" placeholder="Billing Zip/Postal Code"> -->
          </div>
          <div class="input-errors" id="card-postal-code-errors" role="alert"></div>
        </div>
          
        <div id="card-response" role="alert"></div>
        <div class="button-container">
            <!-- <button type="submit">Pay Now</button> -->
            <input type="submit" name="Pay Invoice"/>
        </div>
      </form>
      <script type="text/javascript">
          
          const styles = {
            'card-number input': {
                // 'width': '20em',
                // 'font-size': '20px',
                // 'border': '1px gray dotted',
                // 'padding': '3px',
                // 'margin': '3px',
                // 'font-weight': 'bold'
            },
            'card-number input': {
                'background-color': '#BBBBBB'
            },
            'card-date input': {
                'background-color': '#CCCCCC'
            },
            'card-cvv input': {
                'background-color': '#DDDDDD'
            },
            'card-postal-code input': {
                'background-color': '#EEEEEE'
            }
            };
            const form = document.getElementById('payment-form');
            const clover = new Clover('9c2a27267d88d2c44bea1fe77746c770');
            const elements = clover.elements();
            const cardNumber = elements.create('CARD_NUMBER', styles);
            const cardDate = elements.create('CARD_DATE', styles);
            const cardCvv = elements.create('CARD_CVV', styles);
            const cardPostalCode = elements.create('CARD_POSTAL_CODE', styles);
            
            cardNumber.mount('#card-number');
            cardDate.mount('#card-date');
            cardCvv.mount('#card-cvv');
            cardPostalCode.mount('#card-postal-code');

            const cardResponse = document.getElementById('card-response');
            const displayCardNumberError = document.getElementById('card-number-errors');
            const displayCardDateError = document.getElementById('card-date-errors');
            const displayCardCvvError = document.getElementById('card-cvv-errors');
            const displayCardPostalCodeError = document.getElementById('card-postal-code-errors');

            // Handle real-time validation errors from the card element
            cardNumber.addEventListener('change', function(event) {
                console.log(`cardNumber changed ${JSON.stringify(event)}`);
            });

            cardNumber.addEventListener('blur', function(event) {
                console.log(`cardNumber blur ${JSON.stringify(event)}`);
            });

            cardDate.addEventListener('change', function(event) {
                console.log(`cardDate changed ${JSON.stringify(event)}`);
            });

            cardDate.addEventListener('blur', function(event) {
                console.log(`cardDate blur ${JSON.stringify(event)}`);
            });

            cardCvv.addEventListener('change', function(event) {
                console.log(`cardCvv changed ${JSON.stringify(event)}`);
            });

            cardCvv.addEventListener('blur', function(event) {
                console.log(`cardCvv blur ${JSON.stringify(event)}`);
            });

            cardPostalCode.addEventListener('change', function(event) {
                console.log(`cardPostalCode changed ${JSON.stringify(event)}`);
            });

            cardPostalCode.addEventListener('blur', function(event) {
                console.log(`cardPostalCode blur ${JSON.stringify(event)}`);
            });
            
            function cloverTokenHandler(token) {

              console.log(token)
                // Insert the token ID into the form so it gets submitted to the server
                var form = document.getElementById('payment-form');
                var hiddenInput = document.createElement('input');
                hiddenInput.setAttribute('type', 'hidden');
                hiddenInput.setAttribute('name', 'cloverToken');
                hiddenInput.setAttribute('value', token);
                form.appendChild(hiddenInput);
                form.submit();
            }
            // Listen for form submission
            // import { v4 as uuidv4 } from 'uuid';
            // const uuid4_key = uuidv4();
            // console.log(uuid4_key)
            form.addEventListener('submit', functSubmit);
            function functSubmit(event) {
                console.log("here");
                event.preventDefault();
                // Use the iframe's tokenization method with the user-entered card details
                clover.createToken()
                    .then(function(result) {
                    if (result.errors) {
                    Object.values(result.errors).forEach(function (value) {
                        displayError.textContent = value;
                    });
                    } else {
                    cloverTokenHandler(result.token);
                    }
                });
            };


      </script>
  </body>

</html>