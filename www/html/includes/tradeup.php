<?php
function tradeUp($itemids) {
  $response['success'] = false;
  if(isUser()) {
    if($itemids) {
      $itemids = rtrim($itemids, ',');
      $items = explode(',', $itemids);
      $count = count($items);
      if($count >= 3 && $count <= 10) {
        $locked = isPromo() || isAdmin() ? '1' : '0';
        $results = Query('SELECT SUM(`price`) AS "price", COUNT(`itemid`) AS "count" FROM `users_items` INNER JOIN `items` ON `items`.`id` = `itemid`', array('validuseritems' => $itemids, 'status' => 1, 'locked' => $locked, 'steamid' => getUser('steamid')));
        $tradeCount = $results[0]['count'];
        $tradePrice = $results[0]['price'];
        if($tradePrice <= 500) {
          $tradeMin = $tradePrice * 0.6;
          $tradeMax = $tradePrice;
          if($tradeCount == $count) {
            $rand1 = mt_rand(0,99);
            $rand2 = mt_rand(0,99);
            $rand3 = mt_rand(0,99);
            $rand4 = mt_rand(0,99);
            $rand5 = mt_rand(0,99);
            $rand6 = mt_rand(0,99);
            $rand7 = mt_rand(0,99);
            $rand8 = mt_rand(0,99);
            $rand9 = mt_rand(0,99);
            if($rand1 > 70) {
              $tradeMin = $tradePrice;
              $tradeMax = $tradePrice * 1.2;
              if($rand2 > 70) {
                $tradeMax = $tradePrice * 1.5;
                if($rand3 > 70) {
                  $tradeMax = $tradePrice * 2;
                  if($rand4 > 70) {
                    $tradeMax = $tradePrice * 2.5;
                    if($rand5 > 70) {
                      $tradeMax = $tradePrice * 3;
                      if($rand6 > 70) {
                        $tradeMax = $tradePrice * 3.5;
                        if($rand7 > 70) {
                          $tradeMax = $tradePrice * 4;
                          if($rand8 > 70) {
                            $tradeMax = $tradePrice * 4.5;
                            if($rand9 > 70) {
                              $tradeMax = $tradePrice * 5;
                            }
                          }
                        }
                      }
                    }
                  }
                }
              }
            }
            $results = Query('SELECT * FROM `items`', array('visible' => 1, 'minprice' => $tradeMin, 'maxprice' => $tradeMax), 'price');
            if(!$results) {
              $tradeMin = $tradePrice * 0.6;
              $tradeMax = $tradePrice;
              $results = Query('SELECT * FROM `items`', array('visible' => 1, 'minprice' => $tradeMin, 'maxprice' => $tradeMax), 'price');
            }
            if($results) {
              $item = $results[array_rand($results, 1)];
              $updated = 0;
              foreach($items as $itemid) {
                if(Update('users_items', array('steamid' => getUser('steamid'), 'id' => $itemid, 'status' => 1), array('status' => 9))) {
                  $updated++;
                }
              }
              $addid = $item['id'];
              $item['id'] = 0;
              if($updated == $count) {
                if(isAdmin()) {
                  $tradeid = 0;
                } else {
                  $tradeid = Insert('users_trades', array('steamid' => getUser('steamid'), 'trade_value' => $tradePrice, 'given_value' => $item['price'], 'itemid' => $addid, 'itemids' => $itemids, 'percent1' => $rand1, 'percent2' => $rand2, 'percent3' => $rand3, 'percent4' => $rand4, 'percent5' => $rand5, 'percent6' => $rand6, 'percent7' => $rand7, 'percent8' => $rand8, 'percent9' => $rand9));
                }
                $item['id'] = Insert('users_items', array('steamid' => getUser('steamid'), 'itemid' => $addid, 'locked' => $locked, 'tradeid' => $tradeid));
                $item['locked'] = $locked;
                if($item['id']) {
                  $item['status'] = 1;
                }
              }
              if($item['id']) {
                $response['success'] = true;
                $response['modal'] = createTradeUpModal($item);
                $response['item'] = displayItems(array($item), 'trade');
              } else {
                foreach($items as $item) {
                  Update('users_items', array('steamid' => getUser('steamid'), 'id' => $item, 'status' => 9), array('status' => 1));
                }
                $response['message'] = 'Failed to trade up [invalid item status]';
              }
            } else {
              $response['message'] = 'Failed to trade up [could not find upgrade item]';
            }
          } else {
            $response['message'] = 'Failed to trade up [invalid item id]';
          }
        } else {
          $response['message'] = 'Failed to trade up [trade up value cannot exceed $500]';
        }
      } else {
        $response['message'] = 'Failed to trade up [trade up requires 3 to 10 items]';
      }
    } else {
      $response['message'] = 'Failed to trade up [no items selected]';
    }
  } else {
    $response['message'] = 'Failed to trade up [user must be signed in]';
  }
  return json_encode($response);
}
function createTradeUpModal($item) {
  $body = '<div class="box">
            <img class="img-responsive" src="'.$item['image'].'" />
            <h4>'.$item['name'].'</h4>
           </div>';
  $button = '<button id="sellItem" class="btn btn-primary green sellAll" data-id="'.$item['id'].'"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Sell Item $'.prettyBalance($item['price']).'</button>';
  return createModal('tradeUpModal', $body, $button, 'sm');
}
