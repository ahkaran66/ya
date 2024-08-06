<?php 
include 'trex/controller/config.php';

if($_POST){
  $body = json_decode(file_get_contents("php://input"));

  $urunSecenekGetir=$db->prepare("SELECT * from urunler WHERE urun_id = '{$body->id}'");
  $urunSecenekGetir->execute(array(0));
  $urunSecenek=$urunSecenekGetir->fetch(PDO::FETCH_ASSOC);

  if($urunSecenek && $urunSecenek['secenekler'] != ''){

    $secenekHtml = '';

    foreach(json_decode($urunSecenek['secenekler']) as $secenek){
      $secenekHtml .= " <div class='col-12 col-sm-12 col-lg-12' style='background: #f1f1f1; margin-bottom: 40px; padding: 356px 1032px 5px; border-radius: 5px;border: 1px solid #000;box-shadow: 1px 2px 9px 3px #888888;''><label>".$secenek->title . ' ' . ($secenek->is_required == true ? '<span style=color:red>(*Zorunlu)</span>' : '') ."</label><select name='secenekler[".$secenek->title."]' ". ($secenek->is_required ? 'required' : '') .">";
      if(!$secenek->is_required){
        $secenekHtml .= '<option selected>-Seçiniz-</option>';
      } else {
        $secenekHtml .= '<option value="" selected>-Seçiniz-</option>';
      }
      foreach($secenek->sub as $sub){
        $secenekHtml .= "<option value='". $sub->title . ($sub->value != '' ? '|' . $sub->value . '' : '') ."'>". $sub->title . ($sub->value != '' ? ' +' . $sub->value . ' ₺' : '') ."</option>";
      }
      $secenekHtml .= "</select></div>";
    }

    echo $secenekHtml;
  }
}
