<html>

<head>
    <title>CloverServer Usage for payment secure</title>
    <script src="https://cdn.polyfill.io/v3/polyfill.min.js"></script>
    <script src="https://checkout.sandbox.dev.clover.com/sdk.js"></script>
</head>


<body>

      <?php echo $_POST["amount"]; ?><br>

      <?php echo $_POST["cloverToken"]; ?><br>
      
      <?php 
        function guidv4($data = null) {
          // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
          $data = $data ?? random_bytes(16);
          assert(strlen($data) == 16);
      
          // Set version to 0100
          $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
          // Set bits 6-7 to 10
          $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
      
          // Output the 36 character UUID.
          return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
        }
        

        function getAuthorizationHeader(){
                $headers = null;
                if (isset($_SERVER['Authorization'])) {
                    $headers = trim($_SERVER["Authorization"]);
                }
                else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
                    $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
                } elseif (function_exists('apache_request_headers')) {
                    $requestHeaders = apache_request_headers();
                    // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
                    $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
                    //print_r($requestHeaders);
                    if (isset($requestHeaders['Authorization'])) {
                        $headers = trim($requestHeaders['Authorization']);
                    }
                }
                return $headers;
            }
        /**
         * get access token from header
         * */
        function getBearerToken() {
            $headers = getAuthorizationHeader();
            // HEADER: Get the access token from the header
            if (!empty($headers)) {
                if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                    return $matches[1];
                }
            }
            return null;
        }
        $myuuid = guidv4();
        $bearer_token = getBearerToken();

        echo $myuuid+"<br>";
        echo $bearer_token;
        $s = curl_init();
        $headers = array("accept: application/json",
                      "authorization: Bearer "+strval($bearer_token),
                      "idempotency-key "+strval($myuuid),
                      "content-type: application/json");
        $params = array (
          'amount' => $_POST['amount'],
          'currency' => 'usd',
          'source' => $_POST['cloverToken'],
        );
        curl_setopt($s, CURLOPT_URL, 'https://scl-sandbox.dev.clover.com/v1/charges');
        curl_setopt($s, CURLOPT_POST, 1);
        curl_setopt($s, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($s, CURLOPT_POSTFIELDS, $params);
        $response = curl_exec($s);
        echo $response;
        curl_close($s);
      ?>
  </body>

</html>