<?php
$usersCount = Query('SELECT count(`id`) AS "count" FROM `users`')[0]['count'];
$withdrawCount = prettyBalance(Query('SELECT SUM(`price`) AS "count" FROM `users_items` INNER JOIN `items` ON `itemid` = `items`.`id`', array('status' => 3))[0]['count']);
$pendingCount = prettyBalance(Query('SELECT SUM(`price`) AS "count" FROM `users_items` INNER JOIN `items` ON `itemid` = `items`.`id`', array('status' => 2))[0]['count']);
$casesCount = Query('SELECT count(`id`) AS "count" FROM `open_cases`')[0]['count'];
$tradesCount = Query('SELECT count(`id`) AS "count" FROM `users_trades`')[0]['count'];
$skfCount = prettyBalance(Query('SELECT SUM(`amount`) AS "count" FROM `skf`')[0]['count']);
$paypalCount = prettyBalance(Query('SELECT SUM(`mc_gross`) AS "count" FROM `paypal`', array('payment_status' => 'Completed'))[0]['count']);
$btcCount = prettyBalance(Query('SELECT SUM(`amountUSD`) AS "count" FROM `crypto_payments`', array('txConfirmed' => 1))[0]['count']);
$earnCount = prettyBalance(Query('SELECT SUM(`usd_value`) AS "count" FROM `earn`')[0]['count']);
?>
<h1 class="title">Admin</h1>
<div class="row">
  <div class="col-xs-6">
    <h2 style="margin-top:0;">Admin Pages</h2>
    <ul>
      <?php if(isSuperAdmin()) { ?>
      <li><a href="/admin/deposits/paypal">PayPal Deposits ($<?= $paypalCount ?>)</a></li>
      <li><a href="/admin/deposits/skf">SKF Deposits ($<?= $skfCount ?>)</a></li>
      <li><a href="/admin/deposits/btc">BTC Deposits ($<?= $btcCount ?>)</a></li>
      <li><a href="/admin/deposits/earn">Offers/Surveys ($<?= $earnCount ?>)</a></li>
      <br />
      <?php } ?>
      <li><a href="/admin/withdraws/completed">Completed Withdraws (Total: $<?= $withdrawCount ?>)</a></li>
      <li><a href="/admin/withdraws/pending">Pending Withdraws (Total: $<?= $pendingCount ?>)</a></li>
      <?php if(isSuperAdmin()) { ?>
      <br />
      <li><a href="/admin/coupon">Coupons</a></li>
      <?php } ?>
    </ul>
  </div>
  <div class="col-xs-6">
    <h2 style="margin-top:0;">Site Statistics</h2>
    <ul>
      <li><a href="/admin/referrers">Top Referrers</a></li>
      <li><a href="/admin/users"><?= $usersCount ?> Users</a></li>
      <li><?= $casesCount ?> Cases Opened</li>
      <li><?= $tradesCount ?> Trade Ups</li>
    </ul>
  </div>
</div>
