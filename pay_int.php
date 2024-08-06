<?php

include 'trex/controller/config.php';
require 'library/autoload.php';

$paytr = $db->prepare("SELECT * from paytr where paytr_id=?");
$paytr->execute(array(1));
$paytrprint = $paytr->fetch(PDO::FETCH_ASSOC);

class StripeHelper
{
  /**
   * @var \Stripe\StripeClient
   */
  public $stripeClient;
  public function __construct($secret_key)
  {
    $this->stripeClient = new \Stripe\StripeClient($secret_key);
  }
  /**
   * Create price
   * @param $product
   * @param $productPrice
   * @return \Stripe\Price
   * @throws \Stripe\Exception\ApiErrorException
   */
  public function createProductPrice($product, $productPrice)
  {
    return $this->stripeClient->prices->create(
      array(
        'unit_amount' => $productPrice * 100,
        'currency' => 'TRY',
        'product' => $product->id,
      )
    );
  }
  /**
   * Get session detail
   * @param $sessionId
   * @return \Stripe\Checkout\Session
   * @throws \Stripe\Exception\ApiErrorException
   */
  public function getSession($sessionId)
  {
    return $this->stripeClient->checkout->sessions->retrieve($sessionId);
  }
}
date_default_timezone_set('Europe/Istanbul');
$settings = $db->prepare("SELECT * from ayar where ayar_id=?");
$settings->execute(array(0));
$settingsprint = $settings->fetch(PDO::FETCH_ASSOC);

$social = $db->prepare("SELECT * from sosyal");
$social->execute();

$motor = $db->prepare("SELECT * from motor where motor_id=1");
$motor->execute(array(0));
$motorprint = $motor->fetch(PDO::FETCH_ASSOC);

$whatsapp = $db->prepare("SELECT * from whatsapp where whats_id=0");
$whatsapp->execute(array(0));
$whatsappprint = $whatsapp->fetch(PDO::FETCH_ASSOC);


$paytr = $db->prepare("SELECT * from paytr where paytr_id=?");
$paytr->execute(array(1));
$paytrprint = $paytr->fetch(PDO::FETCH_ASSOC);

$post = $_POST;
if (isset($_GET['stripe_session_id'])) {
  $stripeHelper = new StripeHelper($paytrprint['paytr_key']);
  $checkoutSession = $stripeHelper->getSession($_GET["stripe_session_id"]);
  if ($checkoutSession->payment_status == "paid") {
    $inovance = $db->prepare("SELECT * from siparis where siparis_id=:siparis_id");
    $inovance->execute(
      array(
        'siparis_id' => $checkoutSession->client_reference_id
      )
    );
    $inovanceprint = $inovance->fetch(PDO::FETCH_ASSOC);

    $duzenle = $db->prepare(
      "UPDATE siparis SET
			siparis_durum=:durum,
			siparis_durumpay=:durumpay,
			WHERE siparis_id={$inovanceprint['siparis_id']}"
    );
    $update = $duzenle->execute(
      array(
        'durum' => 1,
        'durumpay' => 1,
      )
    );

  }
  header("Location: " . $_GET['ok_url']);
} else {
  header("Location: " . $_GET['fail_url']);
}
?>