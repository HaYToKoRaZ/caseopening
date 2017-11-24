<?php
$table = '';
if($_params[0] == 'paypal') {
  $title = 'Paypal Deposits';
  $cols = array('ID', 'Steam ID', 'Name', 'Email', 'Amount', 'Fee', 'Status', 'Date');
  $results = Query('SELECT * FROM `paypal`', null, 'id', 'DESC');
  foreach($results as $result) {
    $table .= '<tr><td>'.$result['id'].'</td><td><a href="/admin/users/'.$result['steamid'].'" target="_blank">'.$result['steamid'].'</a></td><td>'.$result['first_name'].' '.$result['last_name'].'</td><td>'.$result['payer_email'].'</td><td>$'.prettyBalance($result['mc_gross']).'</td><td>$'.prettyBalance($result['mc_fee']).'</td><td>'.$result['payment_status'].'</td><td>'.$result['created'].'</td></tr>';
  }
} else if($_params[0] == 'skf') {
  $title = 'SKF Deposits';
  $cols = array('ID', 'Steam ID', 'Amount', 'Description', 'Date');
  $results = Query('SELECT * FROM `skf`', null, 'id', 'DESC');
  foreach($results as $result) {
  	$table .= '<tr><td>'.$result['id'].'</td><td><a href="/admin/users/'.$result['steamid'].'" target="_blank">'.$result['steamid'].'</a></td><td>$'.prettyBalance($result['amount']).'</td><td>'.noHTML($result['description']).'</td><td>'.$result['created'].'</td></tr>';
  }
} else if($_params[0] == 'earn') {
  $title = 'Offers/Surveys Deposits';
  $cols = array('ID', 'Steam ID', 'Amount', 'Site', 'Description', 'Date');
  $results = Query('SELECT * FROM `earn`', null, 'id', 'DESC');
  foreach($results as $result) {
  	$table .= '<tr><td>'.$result['id'].'</td><td><a href="/admin/users/'.$result['steamid'].'" target="_blank">'.$result['steamid'].'</a></td><td>$'.prettyBalance($result['usd_value']).'</td><td>'.$result['site'].'</td><td>'.$result['offer_title'].'</td><td>'.$result['created'].'</td></tr>';
  }
} else if($_params[0] == 'btc') {
  $title = 'BTC Deposits';
  $cols = array('ID', 'Steam ID', 'Amount', 'Address', 'Transaction', 'Confirmed?', 'Date');
  $results = Query('SELECT * FROM `crypto_payments`', null, 'paymentID', 'DESC');
  foreach($results as $result) {
  	$table .= '<tr><td>'.$result['paymentID'].'</td><td><a href="/admin/users/'.$result['userID'].'" target="_blank">'.$result['userID'].'</a></td><td>$'.prettyBalance($result['amountUSD']).'</td><td>'.$result['addr'].'</td><td><a href="https://blockchain.info/tx/'.$result['txID'].'" target="_blank">View</a></td><td>'.$result['txConfirmed'].'</td><td>'.$result['txCheckDate'].'</td></tr>';
  }
}
?>
<h1 class="title"><?= $title ?></h1>
<table class="table">
  <thead>
    <tr>
      <?php foreach($cols as $col) echo '<th>'.$col.'</th>'; ?>
    </tr>
  </thead>
  <tbody>
    <?= $table ?>
  </tbody>
</table>
