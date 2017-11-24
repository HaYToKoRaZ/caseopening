<?php
$status = 0;
$adgate = array('104.130.7.162');
$adscend = array('204.232.224.18', '204.232.224.19', '104.130.46.116', '104.130.60.109', '104.239.224.178', '104.130.60.108');
$offertoro = array('54.175.173.245');
$superrewards = array('54.85.0.76', '54.84.205.80', '54.84.27.163');
$whitelist = array_merge($adgate, $adscend, $offertoro, $superrewards);
$ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
if(in_array($ip, $whitelist)) {
  foreach(glob('../includes/*.php') as $filepath) include $filepath;
  $site = $validHash = $hash = null;
  if(in_array($ip, $adgate)) {
    $site = 'AdGate';
    $validHash = true;
  } else if(in_array($ip, $adscend)) {
    $site = 'AdScend';
    $key = 'a2cyv9a3va84l0wuep4szbu9mvdqei44';
    $hash = hash_hmac('md5', 'offer_id='.getGet('offer_id').'&point_value='.getGet('point_value').'&user_id='.getGet('user_id').'&ip='.getGet('ip'), $key);
    $validHash = $hash == getGet('hash');
  } else if(in_array($ip, $offertoro)) {
    $site = 'OfferToro';
    $key = '272ce03a843c36a369735ada173626c4';
    $_GET['offer_status'] = 1;
    $hash = md5(getGet('offer_id') . '-' . getGet('user_id') . '-' . $key);
    $validHash = $hash == getGet('hash');
  } else if(in_array($ip, $superrewards)) {
    $site = 'SuperRewards';
    $key = '1545875c82e4d5bfa79d1fba90163e05';
    $_GET['tx_id'] = $_REQUEST['id'];
    $_GET['user_id'] = $_REQUEST['uid'];
    $_GET['point_value'] = $_REQUEST['new'];
    $_GET['usd_value'] = $_REQUEST['new'] / 60;
    $_GET['offer_title'] = 'Total Earned: '.$_REQUEST['total'];
    $_GET['offer_id'] = $_REQUEST['oid'];
    $_GET['hash'] = $_REQUEST['sig'];
    $_GET['offer_status'] = 1;
    $hash = md5(getGet('tx_id').':'.getGet('point_value').':'.getGet('user_id').':'.$key);
    $validHash = $hash == getGet('hash');
  }
  if($site) {
    $value = (getGet('point_value') / 100) * 1.2;
    $insert = array(
      'tx_id' => getGet('tx_id'),
      'steamid' => getGet('user_id'),
      'point_value' => $value,
      'usd_value' => getGet('usd_value'),
      'offer_title' => getGet('offer_title'),
      'offer_id' => getGet('offer_id'),
      'offer_status' => getGet('offer_status'),
      'ip' => getGet('ip'),
      'site' => $site,
      'hash' => getGet('hash'),
      'myhash' => $hash,
    );
    if(Insert('earn', $insert)) {
      if($site == 'AdGate' || $site == 'AdScend') {
        if(getGet('offer_status') == 1 && $validHash) {
          addMessage(getGet('user_id'), 'success', 'Offer completed and $'.prettyBalance($value).' has been added to your balance');
          addBalance($value, getGet('user_id'), true);
        }
      }
      $status = 1;
    }
  }
}
echo $status;
