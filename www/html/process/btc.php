<?php
foreach(glob('../includes/*.php') as $filepath) include $filepath;
include '../includes/btc/cryptobox.class.php';
if($_POST) foreach ($_POST as $k => $v) if (is_string($v)) $_POST[$k] = trim($v);
$valid_key = false;
if(isset($_POST["private_key_hash"]) && strlen($_POST["private_key_hash"]) == 128 && preg_replace('/[^A-Za-z0-9]/', '', $_POST["private_key_hash"]) == $_POST["private_key_hash"]) {
  $keyshash = array();
  $arr = explode("^", CRYPTOBOX_PRIVATE_KEYS);
  foreach ($arr as $v) $keyshash[] = strtolower(hash("sha512", $v));
  if (in_array(strtolower($_POST["private_key_hash"]), $keyshash)) $valid_key = true;
}
if(isset($_POST["status"]) && in_array($_POST["status"], array("payment_received", "payment_received_unrecognised")) && $_POST["box"] && is_numeric($_POST["box"]) && $_POST["box"] > 0 && $_POST["amount"] && is_numeric($_POST["amount"]) && $_POST["amount"] > 0 && $valid_key) {
  foreach($_POST as $k => $v) {
  	if($k == "datetime") 							$mask = '/[^0-9\ \-\:]/';
  	elseif(in_array($k, array("err", "date")))		$mask = '/[^A-Za-z0-9\.\_\-\@\ ]/';
  	else											$mask = '/[^A-Za-z0-9\.\_\-\@]/';
  	if($v && preg_replace($mask, '', $v) != $v) 	$_POST[$k] = "";
  }
  if(!$_POST["amountusd"] || !is_numeric($_POST["amountusd"]))	$_POST["amountusd"] = 0;
  if(!$_POST["confirmed"] || !is_numeric($_POST["confirmed"]))	$_POST["confirmed"] = 0;
  $dt = gmdate('Y-m-d H:i:s');
  $obj = run_sql("select paymentID, txConfirmed from crypto_payments where boxID = ".$_POST["box"]." && orderID = '".$_POST["order"]."' && userID = '".$_POST["user"]."' && txID = '".$_POST["tx"]."' && amount = ".$_POST["amount"]." && addr = '".$_POST["addr"]."' limit 1");
  $paymentID = ($obj) ? $obj->paymentID : 0;
  $txConfirmed = ($obj) ? $obj->txConfirmed : 0;
  if(!$paymentID) {
	$amount = $_POST["amountusd"] * 1.2;
  	$sql = "INSERT INTO crypto_payments (boxID, boxType, orderID, userID, countryID, coinLabel, amount, amountUSD, unrecognised, addr, txID, txDate, txConfirmed, txCheckDate, recordCreated)
  			VALUES (".$_POST["box"].", '".$_POST["boxtype"]."', '".$_POST["order"]."', '".$_POST["user"]."', '".$_POST["usercountry"]."', '".$_POST["coinlabel"]."', ".$_POST["amount"].", ".$amount.", ".($_POST["status"]=="payment_received_unrecognised"?1:0).", '".$_POST["addr"]."', '".$_POST["tx"]."', '".$_POST["datetime"]."', ".$_POST["confirmed"].", '$dt', '$dt')";
  	$paymentID = run_sql($sql);
    addBalance($_POST["user"], $amount, true);
    addMessage($_POST["user"], 'success', 'Payment completed and $'.prettyBalance($amount).' has been added to your balance');
  	$box_status = "cryptobox_newrecord";
  } elseif($_POST["confirmed"] && !$txConfirmed) {
  	$sql = "UPDATE crypto_payments SET txConfirmed = 1, txCheckDate = '$dt' WHERE paymentID = $paymentID LIMIT 1";
  	run_sql($sql);
  	$box_status = "cryptobox_updated";
  } else {
  	$box_status = "cryptobox_nochanges";
  }
  if(in_array($box_status, array("cryptobox_newrecord", "cryptobox_updated")) && function_exists('cryptobox_new_payment')) {
    cryptobox_new_payment($paymentID, $_POST, $box_status);
  }
} else {
  $box_status = "Only POST Data Allowed";
}
echo $box_status;
