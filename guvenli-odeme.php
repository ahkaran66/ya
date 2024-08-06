<?php
include 'include/head.php';
$GuvenliGet = htmlspecialchars(trim($_GET['status']));
$siparissor = $db->prepare("SELECT * from siparis where siparis_id=:guvenli and siparis_durum=0");
$siparissor->execute(array('guvenli' => $GuvenliGet));
$sipprint = $siparissor->fetch(PDO::FETCH_ASSOC);
error_reporting(E_ALL);
ini_set("display_errors", 1);
$siparisson = $db->prepare("SELECT * from siparis order by siparis_id DESC Limit 1");
$siparisson->execute(array(0));
$sipsonprint = $siparisson->fetch(PDO::FETCH_ASSOC);
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
        'currency' => 'PLN',
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

$productPrice = $sipprint['siparis_fiyat'];


$stripeHelper = new StripeHelper($paytrprint["paytr_key"]);
$stripe = $stripeHelper->stripeClient;
Stripe\Stripe::setApiKey($paytrprint["paytr_key"]);
$stripe_product_id = null;
foreach (Stripe\Product::all() as $product) {
  if ($product->name == $sipprint['siparis_urun']) {
    $stripe_product_id = $product;
  }
}
if ($stripe_product_id == null) {
  $stripe_product_id = Stripe\Product::create(
    array(
      'name' => $sipprint['siparis_urun']
    )
  );
}
$stripe_price_id = null;
foreach (Stripe\Price::all() as $price) {
  if ($price->unit_amount == $productPrice) {
    $stripe_price_id = $price->id;
  }
}
if ($stripe_price_id == null) {
  $stripe_price_id = $stripeHelper->createProductPrice($stripe_product_id, $productPrice);
}
$merchant_ok_url = $settingsprint['ayar_siteurl'] . "phpmail/siparis.php?iletisimform=ok";
$merchant_fail_url = $settingsprint['ayar_siteurl'] . "?status=no";

$stripeSession = $stripe->checkout->sessions->create(
  array(
    'success_url' => $settingsprint['ayar_siteurl'] . 'pay_int.php?stripe_session_id={CHECKOUT_SESSION_ID}&ok_url=' . urlencode($merchant_ok_url) . '&fail_url=' . urlencode($merchant_fail_url),
    'cancel_url' => $settingsprint['ayar_siteurl'] . 'pay_int.php?stripe_session_id={CHECKOUT_SESSION_ID}&ok_url=' . urlencode($merchant_ok_url) . '&fail_url=' . urlencode($merchant_fail_url),
    'payment_method_types' => ['card', 'blik'],
    'mode' => 'payment',
    'client_reference_id' => $sipprint['siparis_id'],
    'line_items' => array(
      array(
        'price' => $stripe_price_id->id,
        'quantity' => 1,
      )
    )
  )
);
header("Location: " . $stripeSession->url);
exit();