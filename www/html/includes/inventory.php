<?php
function displayItems($items, $tradeup=false) {
  $html = '<div class="row items">';
    foreach($items as $item) {
      $withdraw = isset($item['status']) && $item['status'] == 2 ? 'withdraw' : 'item';
      if(!$tradeup || ($tradeup && $item['status'] == 1)) {
        $info = getItemCondition($item['name']);
        $itemName = $info['name'];
        $itemCondition = $info['condition'];
        $html .= '<div class="col-lg-2 col-md-3 col-sm-6 col-xs-4 '.$withdraw.'" title="'.$item['name'].'" data-id="'.$item['id'].'" data-name="'.$item['name'].'" data-price="'.prettyBalance($item['price'], false).'">
          <h4><img class="img-responsive" src="/img/itembg.png" style="background-image:url('.$item['image'].');" /></h4>
          <h5 style="border-bottom-color:#'.$item['quality_color'].'">'.$itemName.'<br />'.$itemCondition.'</h5>';
          if(isset($item['percent'])) {
            $html .= '<h6>'.$item['percent'].'%<br />$'.prettyBalance($item['price']).'</h6>';
          } else if(isset($item['status'])) {
            if($tradeup) {
              if($tradeup == 'flip') {
                $html .= '<i class="fa fa-plus-circle" aria-hidden="true" title="Add to Flip"></i>';
                $html .= '<i class="fa fa-times-circle" aria-hidden="true" title="Remove from Flip"></i>';
                $html .= '<h6>Add to Flip<br />$'.prettyBalance($item['price']).'</h6>';
                $html .= '<h6>Remove from Flip<br />$'.prettyBalance($item['price']).'</h6>';
              } else {
                $html .= '<i class="fa fa-plus-circle" aria-hidden="true" title="Add to Contract"></i>';
                $html .= '<h6>Add to Contract<br />$'.prettyBalance($item['price']).'</h6>';
              }
            } else if($item['status'] == 1) {
              $html .= '<button id="sellItem" class="btn btn-primary green sellItem" data-id="'.$item['id'].'" data-price="'.$item['price'].'">Sell Item $'.prettyBalance($item['price']).'</button>';
              if($item['locked']) {
                $html .= '<button class="btn btn-primary" disabled>Locked</button>';
              } else {
                $html .= '<button id="withdrawItem" class="btn btn-primary" data-id="'.$item['id'].'">Withdraw</button>';
              }
            } else if($item['status'] == 2) {
              $html .= '<button class="btn btn-primary orange"">Pending Withdraw...</button>';
            }
          } else {
            $html .= '<h6>$'.prettyBalance($item['price']).'</h6>';
          }
        $html .= '</div>';
      }
    }
  $html .= '</div>';
  return $html;
}
function getItemCondition($name) {
  $condition = 'Mint Condition';
  if(strpos($name, 'Factory New')) {
    $condition = 'Factory New';
  } else if(strpos($name, 'Minimal Wear')) {
    $condition = 'Minimal Wear';
  } else if(strpos($name, 'Field-Tested')) {
    $condition = 'Field-Tested';
  } else if(strpos($name, 'Well-Worn')) {
    $condition = 'Well-Worn';
  } else if(strpos($name, 'Battle-Scarred')) {
    $condition = 'Battle-Scarred';
  }
  if($condition != 'Mint Condition') {
    $name = explode('('.$condition.')', $name)[0];
  }
  return array('name' => $name, 'condition' => $condition);
}
function getInventory($inventory=1) {
  if($inventory > 2) {
    return Query('SELECT *, `users_items`.`id` AS "id" FROM `users_items` INNER JOIN `items` ON `items`.`id` = `users_items`.`itemid`', array('steamid' => $inventory, 'inventory' => 2), 'price', 'DESC');
  }
  return Query('SELECT *, `users_items`.`id` AS "id" FROM `users_items` INNER JOIN `items` ON `items`.`id` = `users_items`.`itemid`', array('steamid' => getUser('steamid'), 'inventory' => $inventory), 'price', 'DESC');
}
function getItems($search='') {
  return Query('SELECT * FROM `items`', array('search' => $search, 'visible' => 1), 'price', 'DESC');
}
function withdrawItem($itemId) {
  $response['success'] = false;
  if(isUser() && $itemId) {
    if(getUser('ban') == 0) {
      if(getUser('tlink')) {
        if(getUser('deposit') >= 5) {
          $item = Query('SELECT * FROM `users_items` INNER JOIN `items` ON `items`.`id` = `users_items`.`itemid`', array('steamid' => getUser('steamid'), 'users_items`.`id' => $itemId, 'status' => 1));
          if($item && isset($item[0])) {
            if($item[0]['locked']) {
              $response['message'] = 'Failed to withdraw item [item is locked]';
            } else {
              Update('users_items', array('steamid' => getUser('steamid'), 'id' => $itemId, 'status' => 1), array('status' => 2));
              Update('users', array('steamid' => getUser('steamid')), array('withdraw' => '`withdraw` + '.$item[0]['price']), false);
              $response['success'] = true;
              $response['message'] = 'Item has been added to the withdraw queue [trades can take up to 24 hours to be sent]';
            }
          } else {
            $response['message'] = 'Failed to withdraw item [invalid item status]';
          }
        } else {
          $response['message'] = 'Failed to withdraw item [user must deposit at least $5 to withdraw]';
        }
      } else {
        $response['message'] = 'Failed to withdraw item [trade url must be set]';
      }
    } else {
      $response['message'] = 'Failed to withdraw item [user account is banned]';
    }
  } else {
    $response['message'] = 'Failed to withdraw item [user must be signed in]';
  }
  return json_encode($response);
}
function sellItems($itemids) {
  $response['success'] = false;
  if(isUser()) {
    if($itemids) {
      $itemids = explode(",", rtrim($itemids, ','));
      if($itemids) {
        foreach($itemids as $itemid) {
          $item = Query('SELECT * FROM `users_items` INNER JOIN `items` ON `items`.`id` = `users_items`.`itemid`', array('steamid' => getUser('steamid'), 'users_items`.`id' => $itemid, 'status' => 1));
          if($item && isset($item[0]) && Update('users_items', array('steamid' => getUser('steamid'), 'id' => $itemid, 'status' => 1), array('status' => 0))) {
            addBalance($item[0]['price'], getUser('steamid'), false, false);
            $response['success'] = true;
            $response['balance'] = getBalance();
            $response['message'] = 'Items successfully transferred to balance';
          }
        }
        if(!$response['success']) {
          $response['balance'] = getBalance();
          $response['message'] = 'Failed to sell items [invalid item status]';
        }
      } else {
        $response['message'] = 'Failed to sell items [invalid item id]';
      }
    } else {
      $response['message'] = 'Failed to sell items [no items selected]';
    }
  } else {
    $response['message'] = 'Failed to sell items [user must be signed in]';
  }
  return json_encode($response);
}
