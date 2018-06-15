<?php
require_once('vendor/autoload.php');
require_once('secret-keys.php');

// Get the credit card details submitted by the form
$queries = array();
parse_str($_SERVER['QUERY_STRING'], $queries);

// Set your secret key: remember to change this to your live secret key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey($test_key);

// Create a charge: this will charge the user's card
try {
  $charge = \Stripe\Charge::create(array(
    "amount" => $queries['amount'] * 100, // Amount in cents
    "currency" => 'usd',
    "source" => $queries['token'],
    "description" => $queries['description'],
    "receipt_email" => $queries['email'],
  ));

  $response = array(
    'id' => $charge['id'],
    'created' => $charge['created'],
    'outcome' => $charge['outcome']['type'],
  );

  echo json_encode($response);
} catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];

  echo json_encode($err);
} catch (\Stripe\Error\RateLimit $e) {
  // Too many requests made to the API too quickly
  echo 'Too many requests were made too quickly!';
} catch (\Stripe\Error\InvalidRequest $e) {
  // Invalid parameters were supplied to Stripe's API
  echo 'Invalid request.';
} catch (\Stripe\Error\Authentication $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
  echo 'Authentication failed.';
} catch (\Stripe\Error\ApiConnection $e) {
  // Network communication with Stripe failed
  echo 'Network communication failed.';
} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
  echo 'An unknown payment error occurred.';
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
  echo 'Exception: An unknown error occurred.';
}
?>
