<?php
$table = $body = $title = '';
if($_params) {
  $results = Query('SELECT * FROM `users`', array('steamid' => $_params[0]));
  if($results) {
    $user = $results[0];
    $title = 'User: '.$user['name'];
    $refCount = Query('SELECT COUNT(`id`) AS "count" FROM `users`', array('refby' => $user['steamid']))[0]['count'];
    $openCount = Query('SELECT COUNT(`id`) AS "count" FROM `open_cases`', array('steamid' => $user['steamid']))[0]['count'];
    $body .= '<h4>'.$results[0]['steamid'].' - <a href="//steamcommunity.com/profiles/'.$user['steamid'].'" target="_blank">View Steam Profile</a></h4>';
    $body .= '<h4>Cases Opened: '.$openCount.'</h4>';
    $body .= '<h4>Referred: '.$refCount.' Users</h4>';
    $body .= '<h4>Balance: $'.prettyBalance($user['balance']).'</h4>';
    $body .= '<h4>Deposit: $'.prettyBalance($user['deposit']).'</h4>';
    $body .= '<h4>Withdraw: $'.prettyBalance($user['withdraw']).'</h4>';
    $body .= '<h1 class="title">Users Items</h1>';
    $items = getInventory($user['steamid']);
    $body .= $items ? displayItems($items) : null;
  }
}
if($title == '') {
  $tile = 'All Users';
  $results = Query('SELECT * FROM `users`', array('ban' => 0), 'balance', 'DESC', 200);
  foreach($results as $user) {
    $table .= '<tr>
      <td><a href="/admin/users/'.$user['steamid'].'">'.$user['steamid'].'</a></td>
      <td>'.$user['name'].'</td>
      <td>$'.prettyBalance($user['balance']).'</td>
      <td>'.$user['role'].'</td>
    </tr>';
  }
  $body = '<table class="table">
    <thead>
      <tr>
        <th>Steam ID</th>
        <th>Name</th>
        <th>Balance</th>
        <th>Role</th>
      </tr>
    </thead>
    <tbody>
      '.$table.'
    </tbody>
  </table>';
}
?>
<h1 class="title"><?= $title ?></h1>
<?= $body ?>
