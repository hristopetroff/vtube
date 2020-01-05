<?php


require 'assets/import/PayPal/vendor/autoload.php';
$paypal = new \PayPal\Rest\ApiContext(
  new \PayPal\Auth\OAuthTokenCredential(
    $pt->config->paypal_id,
    $pt->config->paypal_secret
  )
);

$paypal->setConfig(
    array(
      'mode' => $pt->config->paypal_mode
    )
);