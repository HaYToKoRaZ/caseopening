<?php
if (getPost('value') && getPost('uses') && getPost('value') > 0 && getPost('uses') > 0) {
  createCoupon(getPost('value'), getPost('uses'));
}
if (@$_params[0] == 'new') { ?>
  <h1 class="title">Create Code</h1>
  <form method="post">
    <input type="number" name="value" step="any" min="0" placeholder="Code Value" />
    <br />
    <input type="number" name="uses" min="1" placeholder="Amount of Uses" />
    <br />
    <input type="submit" class="btn btn-primary green" value="Create Code" />
  </form>
<?php
} else {
  $results = Query('SELECT `coupon`.*, COUNT(`users_coupons`.`id`) AS "count" FROM `coupon` LEFT JOIN `users_coupons` ON `couponid` = `coupon`.`id`', null, '`coupon`.`id`', 'DESC', null, '`coupon`.`id`');
  $table = '';
  foreach ($results as $result) {
    $rem = $result['uses'] - $result['count'];
    $table .= '<tr>
      <td>' . $result['id'] . '</td>
      <td>' . $result['code'] . '</td>
      <td>$' . prettyBalance($result['value']) . '</td>
      <td>' . $rem . '</td>
      <td>' . $result['uses'] . '</td>
    </tr>';
  } ?>
  <h1 class="title">View Codes</h1>
  <table class="table">
    <thead>
      <tr>
        <th>ID</th>
        <th>Code</th>
        <th>Value</th>
        <th>Uses Remaining</th>
        <th>Total Uses</th>
      </tr>
    </thead>
    <tbody><?= $table ?></tbody>
  </table>
  <a class="btn btn-primary green" href="/admin/coupon/new">Create Code</a>
<?php
} ?>
