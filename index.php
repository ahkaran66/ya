<?php
include 'include/head.php';
$pageedit = $db->prepare("SELECT * from sozlesme where id=:sayfaid");
$pageedit->execute(
  array(
    'sayfaid' => 1
  )
);
$pagewrite = $pageedit->fetch(PDO::FETCH_ASSOC);
$demoCont = $db->prepare("SELECT * from demo where id=1");

$demoCont->execute(array());

$demoControl = $demoCont->fetch(PDO::FETCH_ASSOC);


$DemCont = $demoControl['durum'];
if (isset($_POST['siparisver'])) {
    
    $output = shell_exec('php mail.php'); 

  if ($DemCont == 1) {
    header('Location:?demo=ok');
    exit;
  }

  $siparissor = $db->prepare("SELECT * from siparis where siparis_ip=:ip and DAY(siparis_tarih) = DAY(CURDATE())");
  $siparissor->execute(array('ip' => GetIP()));

  $varmi = $siparissor->rowCount();
  if ($settingsprint['ayar_il'] == 0) {
    if ($varmi >= 1) {
      Header("Location:index.php?status=no");
      exit;
    }
  }

  $ad = htmlspecialchars(trim($_POST['siparis_ad']));
  $tel = htmlspecialchars(trim($_POST['siparis_tel']));
  $mail = htmlspecialchars(trim($_POST['siparis_mail']));
  $siparis_il = htmlspecialchars(trim($_POST['siparis_il']));
  $siparis_odeme = htmlspecialchars(trim($_POST['odeme']));
  $urun = htmlspecialchars(trim($_POST['urun']));
  $ilce = htmlspecialchars(trim($_POST['siparis_ilce']));
  $adres = htmlspecialchars(trim($_POST['siparis_adres']));
  $secenekler = $_POST['secenekler'];

  $seceneklerText = '';

  $odeme = explode("-", $siparis_odeme);
  $urun = explode("|", $urun);

  $urunFiyat = intval($urun[1]);

  foreach ($secenekler as $key => $val) {
    $urunFiyat = intval($urunFiyat) + intval(explode('|', $val)[1]);
    $seceneklerText .= "| {$key}: " . $val;
  }

  $urunAdi = $urun[0] . $seceneklerText;




  $kaydet = $db->prepare(
    "INSERT INTO siparis SET
    siparis_ad=:ad,
    siparis_tel=:tel,
    siparis_mail=:mail,
    siparis_ip=:ip,
    siparis_urun=:urun,
    siparis_odemeid=:odemeid,
    siparis_odeme=:odeme,
    siparis_fiyat=:fiyat,
    siparis_il=:il,
    siparis_ilce=:ilce,
    siparis_adres=:adres"
  );
  $insert = $kaydet->execute(
    array(
      'ad' => $ad,
      'tel' => $tel,
      'mail' => $mail,
      'ip' => GetIP(),
      'urun' => $urunAdi,
      'odeme' => $odeme[0],
      'odemeid' => $odeme[1],
      'fiyat' => $urunFiyat,
      'il' => $siparis_il,
      'ilce' => $ilce,
      'adres' => $adres
    )
  );

  $smssor = $db->prepare("SELECT * from sms where sms_id=0");
  $smssor->execute(array(0));
  $smscek = $smssor->fetch(PDO::FETCH_ASSOC);

  $settings = $db->prepare("SELECT * from ayar where ayar_id=?");
  $settings->execute(array(0));
  $settingsprint = $settings->fetch(PDO::FETCH_ASSOC);

  $link = $settingsprint['ayar_siteurl'];

  if ($insert) {


    $siparissor = $db->prepare("SELECT * from siparis order by siparis_id DESC Limit 1");
    $siparissor->execute(array(0));
    $sipprint = $siparissor->fetch(PDO::FETCH_ASSOC);

    unset($_SESSION['urunler']);

    $sip = $sipprint['siparis_id'];
    if ($odeme[1] == 4) {
      Header("Location:guvenli-odeme?status=$sip");
      exit;
    } else {
      Header("Location:phpmail/siparis.php?iletisimform=ok");
    }

  } else {
    Header("Location:index.php?status=no");

  }
}
?>

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
/></noscript>
<!-- End Meta Pixel Code -->

<title>
  <?php echo $settingsprint['ayar_title'] ?>
</title>
<meta name="description" content="<?php echo $settingsprint['ayar_description'] ?>">
<meta name="keywords" content="<?php echo $settingsprint['ayar_keywords'] ?>">
</head>
<?php echo $motorprint['motor_yonay']; ?>
<div>
  <section style="align-items: center;padding: 0 0;">
    <div class="">
      <div class="row">
        <center>
          <?php
          $picsor = $db->prepare("SELECT * from resimgaleri order by sira ASC");
          $picsor->execute(array(0));
          while ($picprint = $picsor->fetch(PDO::FETCH_ASSOC)) {
            if ($picprint['video'] == 1) { ?>
              <div
                style="max-width: <?php echo $settingsprint['ayar_harita']; ?>px;width: 92%;<?php if ($settingsprint['ayar_adres'] == 1) { ?>box-shadow: 0 0 8px 0px var(--renk2);<?php } ?>">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo $picprint['resim_link']; ?>"
                  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                  allowfullscreen></iframe>
              </div>
            <?php } else { ?>
              <div><a href="#siparis"><img
                    style="max-width: <?php echo $settingsprint['ayar_harita']; ?>px;width: 92%;<?php if ($settingsprint['ayar_adres'] == 1) { ?>box-shadow: 0 0 8px 0px var(--renk2);<?php } ?>"
                    src="trex/<?php echo $picprint['resim_link']; ?>" class="img-responsive" /></a></div>
            <?php }
          } ?>
        </center>
      </div>
    </div>
  </section>
  <?php if ($settingsprint['ayar_yorum'] == 1) { ?>
    <section style="padding: 0;">
      <div class="container"
        style="max-width: <?php echo $settingsprint['ayar_harita']; ?>px;background: #ffffff; margin-bottom: 11px; padding: 5px 18px 5px;border: 1px solid #000; <?php if ($settingsprint['ayar_adres'] == 1) { ?>box-shadow: 0 0 8px 0px var(--renk2);<?php } ?>">
        <div class="col-12 col-sm-12 col-lg-12" style="padding-left: 0; padding-right: 0;">
          <h4>Yorumlar</h4>
          <?php
          $hesapsor = $db->prepare("SELECT * from yorumlar order by tarih DESC");
          $hesapsor->execute(array(0));
          while ($hesapcek = $hesapsor->fetch(PDO::FETCH_ASSOC)) { ?>
            <div
              style="background: #f1f1f1; margin-bottom: -11px; padding: 18px; border-radius: 5px;border: 1px solid #000;box-shadow: 0 0 8px 0px #888888;">
              <div class="row">
                <div class="col-md-6" style="font-size: 20px;font-weight: 700;">
                  <img style="max-height: 50px;max-width: 50px;" src="trex/<?php echo $hesapcek['gorsel']; ?>"> <?php echo $hesapcek['ad']; ?>
                </div>
                <div class="col-md-6" style="text-align: right;">
                  <b style="font-size: 10px;">
                    <?php echo $hesapcek['tarih']; ?>
                  </b> <b style="font-size: 24px;">
                    <?php echo str_repeat('<i style="color:#efce4a;" class="fa fa-star"></i>', $hesapcek['puan']) . str_repeat('<i class="fa fa-star-o"></i>', 5 - $hesapcek['puan']); ?>
                  </b>
                </div>
                <hr style="border-top: 1px solid #c7c7c7;margin-top: 65px;">
                <div class="col-md-12" style="margin-bottom: 10px;">
                  <?php echo $hesapcek['detay']; ?>
                </div>
                <div class="col-md-12" style="">
                  <div class="carousel" data-arrows="false" data-dots="false" data-lightbox="gallery" data-margin="20">
                    <?php
                    $picsor = $db->prepare("SELECT * from yorum_gorsel where yorum=:ID order by id ASC");
                    $picsor->execute(array('ID' => $hesapcek['id']));
                    while ($picprint = $picsor->fetch(PDO::FETCH_ASSOC)) { ?>
                      <div class="portfolio-item pf-illustrations pf-media pf-icons pf-Media">
                        <div class="portfolio-item-wrap">
                          <div class="portfolio-image">
                            <a href="#"><img src="trex/<?php echo $picprint['gorsel'] ?>" alt=""></a>
                          </div>
                          <div class="portfolio-description">
                            <a style="cursor: pointer;" class="image-hover-zoom" href="trex/<?php echo $picprint['gorsel'] ?>"
                              data-lightbox="gallery-item"><i class="fa fa-expand"></i></a>

                          </div>
                        </div>
                      </div>
                    <?php } ?>
                  </div>
                </div>

              </div>
            </div>
            <br>
          <?php } ?>
          <form action="" method="POST">
            <h4>Yorum Gönder</h4>



        </form>
        <!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sipariş Formu</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h2>Sipariş Formu</h2>
        <form action="process.php" method="post">
            <label for="ad">Adınız:</label>
            <input type="text" id="ad" name="ad" required>

            <label for="email">E-posta:</label>
            <input type="email" id="email" name="email" required>

            <label for="telefon">Telefon:</label>
            <input type="tel" id="telefon" name="telefon" required>

            <label for="urun">Ürün:</label>
            <select id="urun" name="urun" required>
                <option value="Ürün 1">Ürün 1</option>
                <option value="Ürün 2">Ürün 2</option>
                <option value="Ürün 3">Ürün 3</option>
            </select>

            <label for="adet">Adet:</label>
            <input type="number" id="adet" name="adet" min="1" required>

            <label for="adres">Adres:</label>
            <textarea id="adres" name="adres" required></textarea>

            <input type="submit" value="Sipariş Ver">
        </form>
    </div>
</body>
</html>
      <div class="col-12 col-sm-12 col-lg-12" style="padding: 10px 0;">
        <?php
        $odeme = $db->prepare("SELECT * from odeme where odeme_durum=1 order by odeme_id ASC");
        $odeme->execute(); ?>
        <label>Wybierz rodzaj płatności:</label><br>
        <?php $first = true;
        foreach ($odeme as $key => $odemecek) { ?>
          <div
            style="background: #ffffff; margin-bottom: -11px; padding: 18px; border-radius: 5px;border: 1px solid #888888;">
            <input type="radio" <?php if ($first) {
              echo 'checked';
              $first = false;
            } ?>
              id="odeme<?php echo $odemecek['odeme_id']; ?>" name="odeme"
              value="<?php echo $odemecek['odeme_adi']; ?>-<?php echo $odemecek['odeme_id']; ?>">
            <label for="odeme<?php echo $odemecek['odeme_id']; ?>" style="margin-bottom: 0;"><?php echo $odemecek['odeme_adi']; ?> - <?php echo $odemecek['odeme_not']; ?></label>
          </div>
          <br>
        <?php } ?>
      </div>
      <div style="word-wrap:break-word;">
        <button id="ButonGizle" type="submit" class="btn btn-gfort" name="siparisver"
          style="width: 100%;height: 50px;word-wrap:break-word; background: #428e22;border: none;">Zakoncz Zamowienie</button>
        <button id="ButonGoster" class="btn btn-gfort"
          style="display: none; width: 100%;height: 50px;word-wrap:break-word; background: #428e22;border: none;">
          proszę czekać.. </button>
      </div>
    </form>
  </div>
</section>
<section style="align-items: center;padding: 0 0;">
  <div class="">
    <!--Embeds -->
    <div class="row">
      <center>
        <div><a href="#siparis"><img
              style="max-width: <?php echo $settingsprint['ayar_harita']; ?>px;width: 100%; <?php if ($settingsprint['ayar_adres'] == 1) { ?> var(--renk2);<?php } ?>"
              src="trex/<?php echo $settingsprint['ayar_logo']; ?>" class="img-responsive" /></a></div>
      </center>
    </div>
  </div>
</section>

<?php include 'include/footer.php'; ?>
<script>
  $("#myform").submit(function () {
    $("#ButonGizle").hide();
    $("#ButonGoster").show();
  });