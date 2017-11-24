<?php
if(!isset($_SESSION)) session_start();
foreach(glob('../includes/*.php') as $filepath) include $filepath;
include '../includes/btc/cryptobox.class.php';
$amount = getPost('value') > 0 ? getPost('value') : 5;
$box = new Cryptobox(array(
  'public_key'  => $cryptoPublic,
  'private_key' => $cryptoPrivate,
  'orderID'     => time().rand().getUser('steamid'),
  'userID'      => getUser('steamid'),
  'userFormat'  => 'COOKIE',
  'amount'   	  => 0,
  'amountUSD'   => $amount,
  'period'      => 'NOEXPIRY',
  'iframeID'    => '',
  'language'	  => 'en'
));
echo createModal('btcModal', $box->display_cryptobox(true, 560, 230), null, 'md', 'Add Funds to Your Balance (BTC)', '<p>By depositing, you automatically agree to our ToS found <a href="/tos">here</a>.</p>');
