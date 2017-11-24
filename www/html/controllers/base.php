<?php

$requiredUser = array(
  'casecreator',
  'earn',
  'inventory',
  'tradeup',
  'affiliates',
);

if(in_array($_view_path, $requiredUser)) {
  requiredUser();
}

if(strpos($_view_path, 'admin') !== false || $_view_path == 'coinflip') {
  requiredAdmin();
}

if(isUser()) {
	include('includes/steamauth/userInfo.php');
	if($_POST) {
    if(getPost('tlink')) {
  		updateTradeurl(getPost('tlink'));
  	} else if(getPost('refby')) {
      updateRefby(getPost('refby'));
    } else if(getPost('refcode')) {
      updateRefcode(getPost('refcode'));
    } else if(getPost('custom') == getUser('steamid') && getPost('payment_status') == 'Completed') {
      setMessage('success', 'Payment successfully completed! $'.prettyBalance(getPost('mc_gross')). ' has or will shortly be added to your balance.');
      header('Location: /');
      exit();
    } else if(getPost('code')) {
      redeemCoupon(getPost('code'));
    }
  }
}

if($offline) {
  if(!isset($_GET['login']) && $_view_path != 'offline' && !isAdmin()) {
    header('Location: /offline');
    exit();
  }
} else {
  if($_view_path == 'offline') {
    header('Location: /');
    exit();
  }
}

if($_view_path == 'case') {
  $case = getCase($_route);
  if($case) {
    $items = $case['items'];
    $case = $case['case'];
    $_title = 'Nanoflip | '.$case['name'];
  }
}

if(in_array($_view_path, $jsfiles)) $js .= ','.$_view_path.'.js';

if($_view_path == 'coinflip') {
  $css .= ',table.css';
	$currentVersion = '?v='.time();
}

if(isAdmin()) $css .= ',beta.css';
