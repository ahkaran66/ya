<?php
include 'include/head.php';

// Diğer sayfa içeriği...

?>

<title><?php echo $metakeyprint['meta_title'] ?></title>
<meta name="description" content="<?php echo $metakeyprint['meta_descr'] ?>">
<meta name="keywords" content="<?php echo $metakeyprint['meta_keyword'] ?>">
</head>
<?php echo $motorprint['motor_yonay']; ?>
<section id="page-title" class="page-title-classic" style="background: url(trex/assets/img/genel/pattern10.png)">
  <div class="container">
    <div class="text-center">
      <h1>Zamówienie potwierdzone</h1>
    </div>
  </div>
</section>
<section style="background-color: #ffffff;">
  <div class="container text-center">
    <div class="row">
      <div class="accordion">
        <img src="upload/okey.jpg" alt="Szczegóły Zamówienia Potwierdzone">
        <h4>Twoje zamówienie zostało pomyślnie zrealizowane! <br> Nasi specjaliści ds. obsługi klienta skontaktują się z Państwem tak szybko, jak to możliwe.</h4>
      </div>
<!-- Meta Pixel Code -->
<script>
!function(f,b,e,v,n,t,s)
{if(f.fbq)return;n=f.fbq=function(){n.callMethod?
n.callMethod.apply(n,arguments):n.queue.push(arguments)};
if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
n.queue=[];t=b.createElement(e);t.async=!0;
t.src=v;s=b.getElementsByTagName(e)[0];
s.parentNode.insertBefore(t,s)}(window, document,'script',
'https://connect.facebook.net/en_US/fbevents.js');
fbq('init', '704847584792368');
fbq('track', 'PageView');
</script>
<noscript><img height="1" width="1" style="display:none"
src="https://www.facebook.com/tr?id=704847584792368&ev=PageView&noscript=1"
fbq('track', 'Purchase', {
value: 9.99,
currency: 'PLN'
});

{
 {
    "data": [
        {
            "event_name": "Purchase",
            "event_time": 1706308042,
            "action_source": "website",
            "user_data": {
                "em": [
                    "7b17fb0bd173f625b58636fb796407c22b3d16fc78302d79f0fd30c2fc2fc068"
                ],
                "ph": [
                    null
                ]
            },
            "custom_data": {
                "currency": "PLN"
            }
        }
    ]
}
<!-- End Meta Pixel Code -->


/></noscript>


<!-- End Meta Pixel Code -->
    <!-- Sipariş bilgilerini gosterme Emrinolur :D -->
      <?php
      $siparisQuery = $db->prepare("SELECT * FROM siparis ORDER BY siparis_id DESC LIMIT 1");
      $siparisQuery->execute();
      $sonSiparis = $siparisQuery->fetch(PDO::FETCH_ASSOC);

      if ($sonSiparis) {
      ?>
        <h4>Numer zamówienia: <?php echo $sonSiparis['siparis_id']; ?></h4>
        <h4>Nazwa produktu: <?php echo $sonSiparis['siparis_urun']; ?></h4>
        <h4>Cena zamówienia: <?php echo $sonSiparis['siparis_fiyat']; ?> ZLOTY</h4>
      <?php } else { ?>
        <h4>Henüz sipariş bulunmamaktadır.</h4>
      <?php } ?>

      <?php if ($settingsprint['ayar_firmaadi'] == 1) { ?>
        <!-- Banka Hesapları -->
        <h3>BANKA HESAPLARIMIZ</h3>
        <div class="accordion">
          <?php
          $hesapsor = $db->prepare("SELECT * FROM hesap");
          $hesapsor->execute();
          ?>
          <?php while ($hesapcek = $hesapsor->fetch(PDO::FETCH_ASSOC)) { ?>
            <div class="ac-item">
              <h5 class="ac-title"><i class="fa fa-try"></i><?php echo $hesapcek['hesap_banka']; ?></h5>
              <div class="ac-content">
                <p>
                  <h5>Ünvan: <span><b><?php echo $hesapcek['hesap_isim']; ?></b></span></h5>
                  <h5>Şube/Şube no: <span><b><?php echo $hesapcek['hesap_sube']; ?></b></span></h5>
                  <h5>Hesap no: <span><b><?php echo $hesapcek['hesap_no']; ?></b></span></h5>
                  <h5>İban: <span><b><?php echo $hesapcek['hesap_iban']; ?></b></span></h5>
                </p>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } ?>
      <br>
      <a href="<?php echo $settingsprint['ayar_siteurl']; ?>" class="btn btn-xl">Wróć na stronę!</a>
    </div>
  </div>
</section>
<?php include 'include/footer.php'; ?>
