<?php

function isUser() {
  return getUser('steamid');
}

function isSuperAdmin() {
  return getUser('role') > 2;
}

function isAdmin() {
  return getUser('role') > 1;
}

function isPromo() {
  return getUser('role') == 1;
}

function getUser($index) {
  if(is_string($index) && isset($_SESSION[$index])) {
    return htmlEscape($_SESSION[$index]);
  }
  return null;
}

function addBalance($amount, $steamid = null, $deposit = false, $force = true) {
  $steamid = $steamid ? $steamid : getUser('steamid');
  $vars = array('balance' => '`balance` + '.$amount);
  if($deposit) $vars['deposit'] = '`deposit` + '.$amount;
  if($force) $vars['balance2'] = '`balance2` + '.$amount;
  return Update('users', array('steamid' => $steamid), $vars, false);
}

function removeBalance($amount, $steamid = null) {
  $steamid = $steamid ? $steamid : getUser('steamid');
  return Update('users', array('steamid' => $steamid), array('balance' => '`balance` - '.$amount), false, $amount);
}

function getBalance() {
  if(isUser()) {
    $balance = Query('SELECT `balance` FROM `users`', array('steamid' => getUser('steamid')))[0]['balance'];
    $balance = floor($balance * 100) / 100;
    return prettyBalance($balance);
  } else {
    return 0;
  }
}

function requiredUser() {
  if(!isUser()) {
    setMessage('error', 'You must be logged in to access this feature.');
    header('Location:'.BASE_URL);
    exit();
  }
}

function requiredAdmin() {
  if(!isAdmin()) {
    setMessage('error', 'You must be an admin in to access this feature.');
    header('Location:'.BASE_URL);
    exit();
  }
}

function addMessage($steamid, $type, $message) {
  Insert('users_messages', array('steamid' => $steamid, 'type' => $type, 'message' => $message));
}

function setMessage($type, $message) {
  if(!isset($_SESSION['messages'])) {
    $_SESSION['messages'] = array();
  }
  return array_push($_SESSION['messages'], array('type' => $type, 'text' => $message));
}

function getMessages() {
  $html = '';
  if(isset($_SESSION['messages'])) {
    $html .= '<script>';
    foreach ($_SESSION['messages'] as $message) {
      $html .= '$(document).ready(function() {
        noty({
          text: "'.$message['text'].'",
          type: "'.$message['type'].'",
          layout: "bottomRight",
          timeout: 3000,
          progressBar: true,
          animation: {
            open: {height: "toggle"},
            close: {height: "toggle"},
            easing: "swing",
            speed: 500,
          },
        });
      });';
    }
    $html .= '</script>';
  }
  unset($_SESSION['messages']);
  return $html;
}

function updateTradeurl($tradeurl) {
  if($tradeurl == getUser('tlink')) {
    setMessage('info', 'Steam trade URL has not been updated (no change detected)');
  } else if(Update('users', array('steamid' => getUser('steamid')), array('tlink' => $tradeurl))) {
    $_SESSION['tlink'] = $tradeurl;
    setMessage('success', 'Updated Steam trade URL');
  } else {
    setMessage('error', 'Failed to update Steam trade URL');
  }
  header('Location: '.$_SERVER['REQUEST_URI']);
  exit();
}

function updateRefby($refcode) {
  if(getUser('ownCSGO')) {
    $ref = Query('SELECT `steamid` FROM `users`', array('refcode' => strtolower($refcode)));
    if($ref) {
      $refby = $ref[0]['steamid'];
      if($refby != getUser('steamid')) {
        if(Update('users', array('steamid' => getUser('steamid'), 'refby' => ''), array('refby' => $refby))) {
          addBalance(1);
          addBalance(0.02, $refby);
          addMessage($refby, 'success', 'affiliate');
          setMessage('success', 'Successfully redeemed affiliate code');
        } else {
          setMessage('error', 'Already redeemed affiliate code');
        }
      } else {
        setMessage('error', 'You cannot use your own affiliate code');
      }
    } else {
      setMessage('error', 'Invalid affiliate code');
    }
  } else {
    setMessage('error', 'You must own CSGO to use affiliates');
  }
  header('Location: '.$_SERVER['REQUEST_URI']);
  exit();
}

function updateRefcode($refcode) {
  if(getUser('ownCSGO')) {
    $ref = Query('SELECT `steamid` FROM `users`', array('refcode' => strtolower($refcode)));
    if($ref) {
      setMessage('error', 'Affiliate code already taken');
    } else {
      if(Update('users', array('steamid' => getUser('steamid')), array('refcode' => strtolower($refcode)))) {
        setMessage('success', 'Successfully created affiliate code');
      } else {
        setMessage('error', 'Already created affiliate code');
      }
    }
  } else {
    setMessage('error', 'You must own CSGO to use affiliates');
  }
  header('Location: '.$_SERVER['REQUEST_URI']);
  exit();
}

function createCoupon($value, $uses) {
  if(isSuperAdmin()) {
    $code = strtoupper(generateToken(5).'-'.generateToken(5).'-'.generateToken(5).'-'.generateToken(5));
    if(Insert('coupon', array('code' => $code, 'value' => $value, 'uses' => $uses, 'steamid' => getUser('steamid')))) {
      setMessage('success', 'Successfully created code');
      header('Location: /admin/coupon/');
      exit();
    }
  }
  setMessage('error', 'Failed to create code');
  header('Location: /admin/coupon/new');
  exit();
}

function redeemCoupon($code) {
  $results = Query('SELECT `coupon`.*, COUNT(`users_coupons`.`id`) AS "count" FROM `coupon` LEFT JOIN `users_coupons` ON `coupon`.`id` = `couponid`', array('code' => $code));
  $couponid = $results[0]['id'];
  if($couponid) {
    if($results[0]['uses'] - $results[0]['count'] > 0) {
      $count = Query('SELECT COUNT(`id`) AS "count" FROM `users_coupons`', array('steamid' => getUser('steamid'), 'couponid' => $couponid))[0]['count'];
      if($count == 0) {
        $value = $results[0]['value'];
        if(Insert('users_coupons', array('steamid' => getUser('steamid'), 'couponid' => $couponid)) && addBalance($value, getUser('steamid'), false, true)) {
          setMessage('success', 'Successfully redeemed code and $'.prettyBalance($value).' has been added to your balance');
        } else {
          setMessage('error', 'Failed to redeem this code [could not update database]');
        }
      } else {
        setMessage('error', 'You have already redeemed this code');
      }
    } else {
      setMessage('error', 'Code has been used the maximum amount of times');
    }
  } else {
    setMessage('error', 'Code is invalid');
  }
  header('Location: '.$_SERVER['REQUEST_URI']);
  exit();
}
