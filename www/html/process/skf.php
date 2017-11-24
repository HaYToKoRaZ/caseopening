<?php
if(!isset($_SESSION)) session_start();
foreach(glob('../includes/*.php') as $filepath) include $filepath;
if($offline && !isAdmin()) return;
if(empty($_GET['paymentId'])) return;
if(isUser()) {
  require_once '../includes/skf/skfpayLib.php';
  $payment_id = $_GET['paymentId'];
  $skfpay = new CSkfpay("3439363537363a313a7b32303061373137352d316237622d346632322d396233392d3132653432626439623062367d");
  $payment = $skfpay->getPayment($payment_id);
  if($payment) {
    if($payment->getStatus() == PaymentStatus::AWAITING_AUTH) {
      setMessage('error', 'Payment is still awaiting auth.');
      header('Location: /');
      exit;
    } else if($payment->getStatus() == PaymentStatus::AUTHED) {
      if($payment->confirm()) {
        $rawArr = $payment->toRawArr();
        $rawArr['amount'] = $rawArr['amount'] * 1.2;
        $rawArr['steamid'] = getUser('steamid');
        if(Insert('skf', $rawArr)) {
          addBalance($rawArr['amount'], getUser('steamid'), true);
          setMessage('success', '$'.prettyBalance($rawArr['amount']).' has been added to your balance.');
        } else {
          setMessage('error', 'Credits have aleady been added.');
        }
      } else {
        setMessage('error', 'Error confirming payment.');
      }
    } else if($payment->getStatus() == PaymentStatus::CHARGED) {
      setMessage('error', 'Payment has already been charged!');
    }
  }
} else {
  setMessage('error', 'User must be signed in to deposit.');
}
header('Location: /');
exit();
