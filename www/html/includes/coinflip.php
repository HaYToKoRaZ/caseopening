<?php
function getFlips($ended=0) {
  $results = Query("
    SELECT
      `coinflip`.*,
      `coinflip_items`.*,
      `items`.*,
      `items`.`name` AS 'sname',
      TIMESTAMPDIFF(SECOND, `coinflip`.`created`, CURRENT_TIMESTAMP()) AS 'updated',
      `users`.`name`, `users`.`avatar`,
      `users2`.`name` AS 'name2', `users2`.`avatar` AS 'avatar2'
    FROM `coinflip`
      INNER JOIN `coinflip_items` ON `coinflipid` = `coinflip`.`id`
      INNER JOIN `users_items` ON `users_items`.`id` = `coinflip_items`.`itemid`
      INNER JOIN `items` ON `items`.`id` = `users_items`.`itemid`
      INNER JOIN `users` ON `users`.`steamid` = `creator`
      LEFT JOIN `users` AS `users2` ON `joiner` = `users2`.`steamid`",
    array('ended' => $ended, 'valid' => 1), 'cvalue', 'DESC');
  return fixFlip($results);
}
function fixFlip($results) {
  $flips = array();
  foreach($results as $result) {
    if(!isset($flips[$result['coinflipid']])) {
      $flips[$result['coinflipid']] = array(
        'id' => $result['coinflipid'],
        'creator' => $result['creator'],
        'cvalue' => $result['cvalue'],
        'cskins' => array(),
        'hash' => $result['hash'],
        'secret' => $result['secret'],
        'percent' => $result['percent'],
        'joiner' => $result['joiner'],
        'jvalue' => $result['jvalue'],
        'jskins' => array(),
        'winner' => $result['winner'],
        'wflip' => $result['wflip'],
        'ended' => $result['ended'],
        'side' => $result['side'],
        'created' => $result['created'],
        'name' => noHTML($result['name']),
        'name2' => noHTML($result['name2']),
        'avatar' => $result['avatar'],
        'avatar2' => $result['avatar2'],
      );
    }
    if($result['creator'] == $result['steamid']) {
      array_push($flips[$result['coinflipid']]['cskins'], array(
        'name' => $result['sname'],
        'image' => $result['image'],
        'price' => $result['price'],
        'quality_color' => $result['quality_color'],
      ));
    } else {
      array_push($flips[$result['coinflipid']]['jskins'], array(
        'name' => $result['sname'],
        'image' => $result['image'],
        'price' => $result['price'],
        'quality_color' => $result['quality_color'],
      ));
    }
  }
  return $flips;
}
function createFlip($itemids) {
  $response['success'] = false;
  if(isUser()) {
    if($itemids) {
      $itemids = rtrim($itemids, ',');
      $items = explode(',', $itemids);
      $count = count($items);
      if($count <= maxItemsFlip()) {
        $results = Query('SELECT SUM(`price`) AS "price", COUNT(`itemid`) AS "count" FROM `users_items` INNER JOIN `items` ON `items`.`id` = `itemid`', array('validuseritems' => $itemids, 'status' => 1, 'locked' => 0, 'steamid' => getUser('steamid')));
        $itemsCount = $results[0]['count'];
        $itemsPrice = $results[0]['price'];
        if($itemsCount == $count) {
          if($itemsPrice >= minFlipValue()) {
            $percent = mt_rand(0,99).'.'.mt_rand(0,100000000);
            $secret = generateToken(6);
            $hash = md5($percent.':'.$secret);
            $flipid = Insert('coinflip', array('creator' => getUser('steamid'), 'cvalue' => $itemsPrice, 'percent' => $percent, 'secret' => $secret, 'hash' => $hash));
            $updated = 0;
            foreach($items as $itemid) {
              if(Update('users_items', array('steamid' => getUser('steamid'), 'id' => $itemid, 'status' => 1), array('status' => 8))
                && Insert('coinflip_items', array('coinflipid' => $flipid, 'steamid' => getUser('steamid'), 'itemid' => $itemid))) {
                $updated++;
              }
            }
            if($updated == $count) {
              Update('coinflip', array('id' => $flipid), array('valid' => 1));
              //print_r('items: '.$itemsCount);
              //print_r('price: '.$itemsPrice);
              $response['success'] = true;
              //$response['modal'] = createTradeUpModal($item);
              //$response['item'] = displayItems(array($item), true);
            } else {
              foreach($items as $item) {
                Update('users_items', array('steamid' => getUser('steamid'), 'id' => $item, 'status' => 8), array('status' => 1));
              }
              $response['message'] = 'Failed to create flip [invalid item status]';
            }
          } else {
            $response['message'] = 'Failed to create flip [flip value must be at least $'.minFlipValue().']';
          }
        } else {
          $response['message'] = 'Failed to create flip [invalid item id]';
        }
      } else {
        $response['message'] = 'Failed to create flip [cannot exceed '.maxItemsFlip().' items]';
      }
    } else {
      $response['message'] = 'Failed to create flip [no items selected]';
    }
  } else {
    $response['message'] = 'Failed to create flip [user must be signed in]';
  }
  return json_encode($response);
}

function displayFlips($flips) {
  $table = '';
  foreach($flips as $flip) {
    $table .= displayFlip($flip);
  }
  return '<input id="cid" type="hidden" />
  <table class="table-fill flips" id="coinflips">
    <thead>
      <tr>
        <th class="text-center">Side</th>
        <th class="text-center">Players</th>
        <th class="text-center">Items</th>
        <th class="text-center">Value/range</th>
        <th class="text-center">Actions</th>
      </tr>
    </thead>
    <tbody class="table-hover">
      '.$table.'
    </tbody>
  </table>';
}

function displayFlip($flip) {
  $allItems = array_merge($flip['cskins'], $flip['jskins']);
  usort($allItems, 'cmp');
  $size = sizeof($allItems);
  $afterItems = $items = '';
  if($size > 4) {
    $afterItems = ' + '.($size-4).' more';
    $size = 4;
  }
  for($i=0; $i<$size; $i++) {
    $items .= '<img class="img-responsive" src="/img/itembg.png" title="'.$allItems[$i]['name'].'" style="background-image:url('.$allItems[$i]['image'].');" />';
  }
  $side = $flip['side'] == 1 ? getCure() : getHazard();
  $min = ceil($flip['cvalue'] * 0.9 * 100) / 100;
  $max = ceil($flip['cvalue'] * 1.1 * 100) / 100;
  $joiner = $flip['joiner'] != '' ? '<a title="'.$flip['name2'].'" class="profile" href="//steamcommunity.com/profiles/'.$flip['joiner'].'" target="_blank"><img class="img-responsive" src="'.$flip['avatar2'].'" /></a>' : null;
  $buttons = $flip['joiner'] == '' && isUser() && getUser('steamid') != $flip['creator'] ? '<button type="button" class="btn btn-primary green join">Join</button>' : null;
  $html = '<tr data-id="'.$flip['id'].'" data-value="'.$flip['cvalue'].'" data-min="'.$min.'" data-max="'.$max.'" data-user="'.$flip['creator'].'">
    <td class="text-center">
      <div class="bottle">
        '.$side.'
      </div>
    </td>
    <td class="text-left">
      <a title="'.$flip['name'].'" class="profile" href="//steamcommunity.com/profiles/'.$flip['creator'].'" target="_blank"><img class="img-responsive" src="'.$flip['avatar'].'" /></a>
      '.$joiner.'
    </td>
    <td class="text-left">
      <p>'.$items.$afterItems.'</p>
    </td>
    <td class="text-center">
      <p>$'.prettyBalance($flip['cvalue']).'<small>'.prettyBalance($min).' - '.prettyBalance($max).'</small></p>
    </td>
    <td class="text-center">
      '.$buttons.'
      <button type="button" class="btn btn-primary red" data-toggle="modal" data-target="#watchFlip'.$flip['id'].'">Watch</button>
    </td>
    '.watchModal($flip, $side).'
  </tr>';
  return $html;
}

function watchModal($flip, $side) {
  $id = 'watchFlip'.$flip['id'];
  $buttons = $flip['joiner'] == '' && isUser() && getUser('steamid') != $flip['creator'] ? '<button type="button" class="btn btn-primary green join">Join</button>' : null;
  $name2 = $flip['name2'] ? $flip['name2'] : '???';
  $header = $flip['name'].' vs '.$name2;
  $body = '<div class="row watchFlip">
    <div class="col-sm-4">
      <a title="'.$flip['name'].'" class="profile" href="//steamcommunity.com/profiles/'.$flip['creator'].'" target="_blank">
        <img class="img-responsive" src="'.$flip['avatar'].'" />
        <h4>'.$flip['name'].'</h4>
      </a>
    </div>
    <div class="col-sm-4">
    '.$side.'
    </div>
    <div class="col-sm-4">
      <a title="'.$flip['name2'].'" class="profile" href="//steamcommunity.com/profiles/'.$flip['joiner'].'" target="_blank">
        <img class="img-responsive" src="'.$flip['avatar2'].'" />
        <h4>'.$flip['name2'].'</h4>
      </a>
    </div>
  </div>';
  return createModal($id, $body, $buttons, 'lg', $header);
}

function joinFlip($items, $flipid) {

}

function endFlip($flipid) {

}

function cmp($a, $b) {
  return $a['price'] < $b['price'];
}

function maxItemsFlip() {
  global $maxItemsFlip;
  return $maxItemsFlip;
}
function minFlipValue() {
  global $minFlipValue;
  return $minFlipValue;
}
