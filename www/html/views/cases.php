<?php
$cases = getCases();
$current = 20;
?>
<h1 class="title">Chronic Cases</h1>
<div class="row cases-gallery">
<?php
  foreach($cases as $case) {
    $itemimg = null;
    if($current == 20 && $case['price'] >= 4.99 && $case['price'] < 19.99) {
      $current = 5;
      echo '</div><h1 class="title">Subacute Cases</h1>';
      echo '<div class="row cases-gallery">';
    } else if($current == 5 && $case['price'] >= 0.01 && $case['price'] < 4.99) {
      $current = 0;
      echo '</div><h1 class="title">Acute Cases</h1>';
      echo '<div class="row cases-gallery">';
    }
    if($case['item']) {
      $imgs = explode(',', $case['item']);
      $itemimg .= '<div class="citem">';
        if(in_array(7, $imgs)) {
          $itemimg .= '<img title="Pistol" class="img-responsive" src="/img/pistol.png" />';
        }
        if(in_array(6, $imgs)) {
          $itemimg .= '<img title="Shotgun" class="img-responsive" src="/img/shotgun.png" />';
        }
        if(in_array(5, $imgs)) {
          $itemimg .= '<img title="SMG" class="img-responsive" src="/img/smg.png" />';
        }
        if(in_array(4, $imgs)) {
          $itemimg .= '<img title="M4A4" class="img-responsive" src="/img/m4a4.png" />';
        }
        if(in_array(3, $imgs)) {
          $itemimg .= '<img title="Rifle" class="img-responsive" src="/img/rifle.png" />';
        }
        if(in_array(2, $imgs)) {
          $itemimg .= '<img title="AWP" class="img-responsive" src="/img/awp.png" />';
        }
        if(in_array(1, $imgs)) {
          $itemimg .= '<img title="Knife" class="img-responsive" src="/img/knife.png" />';
        }
      $itemimg .= '</div>';
    }
    echo '<div class="col-lg-3 col-md-4 col-sm-12 col-xs-6">
        <a href="/'.$case['slug'].'">
          <img class="img-responsive" src="/img/cases/'.$case['image'].'.png" />
          <img class="img-responsive caseimage" src="/img/cases/'.$case['slug'].'.png" />
          '.$itemimg.'
          <h5>'.$case['name'].'</h5>
          <h6>$'.prettyBalance($case['price']).'</h6>
        </a>
    </div>';
  }
?>
</div>
