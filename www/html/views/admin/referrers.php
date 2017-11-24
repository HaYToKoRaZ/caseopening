<?php
$table = '';
$results = Query('SELECT `users`.`refby`, COUNT(`users`.`refby`) AS "count", `users2`.`name`, `users2`.`refcode` FROM `users` LEFT JOIN `users` AS users2 ON `users`.`refby` = `users2`.`steamid` GROUP BY `users`.`refby`', null, 'count', 'DESC', 100);
foreach($results as $result) {
  $table .= $result['refby'] ? '<tr><td><a href="/admin/users/'.$result['refby'].'" target="_blank">'.$result['refby'].'</a></td><td>'.$result['name'].'</td><td>'.$result['refcode'].'</td><td>'.$result['count'].'</td></tr>' : null;
}
?>
<h1 class="title">Top Referrals</h1>
<table class="table">
  <thead>
    <tr>
      <th>Steam ID</th>
      <th>Name</th>
      <th>Affilate Code</th>
      <th>Count</th>
    </tr>
  </thead>
  <tbody>
    <?= $table ?>
  </tbody>
</table>
