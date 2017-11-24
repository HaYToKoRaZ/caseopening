<?php
if($_SERVER['REQUEST_METHOD'] != "POST") die("No Post Variables");
$req = 'cmd=_notify-validate';
foreach ($_POST as $key => $value) {
  $value = urlencode(stripslashes($value));
  $req .= "&$key=$value";
}
$curl_result = $curl_err = '';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "https://www.paypal.com/cgi-bin/webscr");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $req);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_VERBOSE, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
$curl_result = @curl_exec($ch);
$curl_err = curl_error($ch);
curl_close($ch);
if(strpos($curl_result, "VERIFIED") !== false) {
	$ip = isset($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : null;
	if(gethostbyaddr($ip) == 'notify.paypal.com') {
		foreach(glob('../includes/*.php') as $filepath) include $filepath;
		$invoice = array(
			'steamid' => getPost('custom'),
			'first_name' => getPost('first_name'),
			'last_name' => getPost('last_name'),
			'payer_email' => getPost('payer_email'),
			'payer_id' => getPost('payer_id'),
			'mc_gross' => getPost('mc_gross'),
			'mc_fee' => getPost('mc_fee'),
			'mc_currency' => getPost('mc_currency'),
			'payment_status' => getPost('payment_status'),
			'payment_date' => getPost('payment_date'),
			'receiver_id' => getPost('receiver_id'),
			'txn_id' => getPost('txn_id'),
			'ip' => $ip,
		);
		if(getPost('mc_currency') == 'USD') {
			Insert('paypal', $invoice);
			if(getPost('payment_status') == 'Completed' && getPost('receiver_id') == 'WMCK6LATSDHS8') {
				addMessage(getPost('custom'), 'success', 'Payment completed and $'.prettyBalance(getPost('mc_gross')).' has been added to your balance');
				//addBalance(getPost('mc_gross'), getPost('custom'), true);
			} else {
				addMessage(getPost('custom'), 'info', 'Payment pending verification');
			}
		}
  }
}
