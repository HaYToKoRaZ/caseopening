<?php
if(!isset($_SESSION)) session_start();
foreach(glob('../includes/*.php') as $filepath) include $filepath;
if($offline && !isAdmin()) return;
if(getPost('new')) {
  $categories = array(
    'New Cases' => getCases(false, $_POST, 0, 10000),
  );
} else if(getPost('steamid')) {
  $categories = array(
    'My Cases' => getCases(false, $_POST, 0, 10000),
  );
} else {
  $categories = array(
    'Best $100+ Cases' => getCases(false, $_POST, 100, 10000),
    'Best $50 - $100 Cases' => getCases(false, $_POST, 50, 100),
    'Best $20 - $50 Cases' => getCases(false, $_POST, 20, 50),
    'Best $5 - $20 Cases' => getCases(false, $_POST, 5, 20),
    'Best $2 - $5 Cases' => getCases(false, $_POST, 2, 5),
    'Best $1 - $2 Cases' => getCases(false, $_POST, 1, 2),
    'Best < $1 Cases' => getCases(false, $_POST, 0, 1),
  );
}
$html = '';
foreach($categories as $title => $cases) {
  $multiple = count($categories) > 2 && count($cases) > 4;
  $html .= '<h1 class="title">'.$title.'</h1><div class="row cases-gallery">';
  $html .= $multiple ? '<span id="scrollLeft" class="scroll">◀</span><span id="scrollRight" class="scroll">▶</span><div class="large-row">' : null;
  if($cases) {
    foreach($cases as $case) {
      $html .= $multiple ? '<div class="col-md-1 col-sm-12 col-xs-6">' : '<div class="col-lg-3 col-md-4 col col-sm-12 col-xs-6">';
      $html .= '<a href="/'.$case['slug'].'">
                <img class="img-responsive" src="/img/cases/'.$case['image'].'.png" />
                <img class="img-responsive caseimage" src="'.$case['itemimg'].'" />
                <h5>'.$case['name'].'</h5>
                <h6>$'.prettyBalance($case['price']).'</h6>
              </a>
            </div>';
    }
  } else {
    $html .= '<div class="col-lg-12"><h1>There are no cases that match this criteria.</h1></div>';
  }
  $html .= $multiple ? '</div>' : null;
  $html .= '</div>';
}
echo $html;
