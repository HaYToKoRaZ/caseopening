<?php
$user = Query('SELECT `users`.`refcode`, `users2`.`name` FROM `users` LEFT JOIN `users` AS users2 ON `users`.`refby` = `users2`.`steamid`', array('users`.`steamid' => getUser('steamid')));
$refby = $user[0]['name'] ? 'You were referred by \''.noHTML($user[0]['name']).'\'': null;
$refcode = $user[0]['refcode'];
if($refcode) $count = Query('SELECT COUNT(`id`) AS count FROM `users`', array('refby' => getUser('steamid')))[0]['count'];
?>
<h1 class="title">Affiliates</h1>
<div class="row">
  <div class="col-sm-6 box">
    <p>Claim Code To Receive $1!</p>
    <?php if($refby) { ?>
      <input value="<?= $refby; ?>" style="width:100%; display:block; margin-bottom:10px;" disabled />
    <?php } else { ?>
      <form method="post">
        <input name="refby" style="width:100%; display:block; margin-bottom:10px;" />
        <input type="submit" class="btn btn-primary green" value="Claim" />
      </form>
    <?php } ?>
  </div>
  <div class="col-sm-6 box">
    <p>Affiliate Code <?php if($refcode) echo '<span>Used '.$count.' times ($'.prettyBalance($count * 0.02).')</span>'; ?></p>
    <form method="post">
      <input name="refcode" value="<?= $refcode; ?>" style="width:100%; display:block; margin-bottom:10px;" />
      <input type="submit" class="btn btn-primary green" value="<?= $refcode ? 'Change' : 'Create' ?>" />
    </form>
  </div>
</div>
