<?php 
include 'include/head.php'; 
$pageedit=$db->prepare("SELECT * from sozlesme where id=:sayfaid");
$pageedit->execute(array(
  'sayfaid' => 1
));
$pagewrite=$pageedit->fetch(PDO::FETCH_ASSOC);
?>
<title><?php echo $settingsprint['ayar_title'] ?></title>
<meta name="description" content="<?php echo $settingsprint['ayar_description'] ?>">
<meta name="keywords" content="<?php echo $settingsprint['ayar_keywords'] ?>">
</head>
<section id="page-title" class="page-title-classic" style="background: url(trex/assets/img/genel/pattern10.png)">
  <div class="container">
    <div class="text-center">
      <h1>SÖZLEŞMELER</h1>
    </div>
  </div>
</section>
<section style="background-color: #fff;">
  <div class="container">
    <div class="row">
      <div class="accordion">
        <h4 ><?php echo $pagewrite['ad']; ?></h4>
      </div>
      <p><?php echo $pagewrite['icerik']; ?></p>
      <a href="<?php echo $settingsprint['ayar_siteurl']; ?>" class="btn btn-xl">SİTEYE DÖN!</a>
    </div>
  </div>
</section>
<?php include 'include/footer.php'; ?>
