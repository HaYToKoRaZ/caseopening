<?php
$table = '';
if($_params && $_params[0] == 'completed') {
	$title = 'Past Withdraws <span><a href="/admin/withdraws/pending">View Pending Withdraws</a></span>';
	$results = Query('SELECT `tlink`, `users`.`steamid`, `users_items`.`id` AS "itemid", `updatedby`, `items`.* FROM `users_items` INNER JOIN `items` ON `items`.`id` = `itemid` INNER JOIN `users` ON `users`.`steamid` = `users_items`.`steamid`', array('status' => 3), 'price', 'DESC');
	foreach($results as $result) {
		$table .= '<tr>
			<td>'.$result['name'].'</td>
			<td><a href="/admin/users/'.$result['steamid'].'" target="_blank">'.$result['steamid'].'</a></td>
			<td>$'.prettyBalance($result['price']).'</td>
			<td><a href="'.noHTML($result['tlink']).'" target="_blank">Link</a></td>
			<td>Sent by '.$result['updatedby'].'</td>
		</tr>';
	}
} else {
	if(getGet('itemid') && getGet('steamid')) {
		if(getGet('invalid') && getGet('price')) {
			$id1 = Update('users_items', array('id' => getGet('itemid'), 'status' => 2), array('status' => 1));
			$id2 = Update('users', array('steamid' => getGet('steamid')), array('withdraw' => '`withdraw` - '.getGet('price')), false);
			addMessage(getGet('steamid'), 'error', 'Your trade URL is invalid or not public. Please <a href=\"/inventory\">update it</a> to receive your winnings.');
			if($id1 && $id2) {
				setMessage('success', 'Updated item and sent failure notification');
			} else {
				setMessage('error', 'Failed to update item...');
			}
		} else {
			if(Update('users_items', array('id' => getGet('itemid'), 'status' => 2), array('status' => 3, 'updatedby' => getUser('steamid')))) {
				setMessage('success', 'Item succesfully updated');
				addMessage(getGet('steamid'), 'success', 'trade');
			} else {
				setMessage('error', 'Failed to update item...');
			}
		}
		header('Location: /admin/withdraws');
		exit();
	}
	$title = 'Pending Withdraws <span><a href="/admin/withdraws/completed">View Completed Withdraws</a></span>';
	$results = Query('SELECT `tlink`, `users`.`steamid`, `users_items`.`id` AS "itemid", `items`.* FROM `users_items` INNER JOIN `items` ON `items`.`id` = `itemid` INNER JOIN `users` ON `users`.`steamid` = `users_items`.`steamid`', array('status' => 2), 'price', 'DESC');
	foreach($results as $result) {
		$table .= '<tr>
			<td>'.$result['name'].'</td>
			<td><a href="/admin/users/'.$result['steamid'].'" target="_blank">'.$result['steamid'].'</a></td>
			<td>$'.prettyBalance($result['price']).'</td>
			<td><a href="'.noHTML($result['tlink']).'" target="_blank">Link</a></td>
			<td><a href="/admin/withdraws?itemid='.$result['itemid'].'&steamid='.$result['steamid'].'" onclick="return confirm(\'Are you sure this trade is done?\')">Send</a> • <a href="/admin/withdraws?itemid='.$result['itemid'].'&steamid='.$result['steamid'].'&invalid='.$result['itemid'].'&price='.$result['price'].'">Invalid URL</a></td>
		</tr>';
	}
}
?>
<h1 class="title"><?= $title ?></h1>
<table class="table">
  <thead>
    <tr>
      <th>Skin</th>
      <th>User ID</th>
      <th>Skin Price</th>
      <th>Trade URL</th>
      <th>Send</th>
    </tr>
  </thead>
  <tbody>
		<?= $table ?>
  </tbody>
</table>
