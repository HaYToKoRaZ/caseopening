<?php

function getCases($official=true, $search=null, $min=0, $max=10000) {
  $where['case`.`visible'] = 1;
  $order = '`price`';
  $limit = 20;
  if($official) {
    $where['steamid'] = 1;
  } else {
    $where['usercases'] = 1;
    $where['minprice'] = $min;
    $where['maxprice'] = $max;
    $order = '`open_count`';
    $limit = 12;
    if($search) {
      foreach($search as $k => $v) {
        if($k == 'steamid') {
          $limit = null;
          $where['steamid'] = getUser('steamid');
        } else if($k == 'new') {
          $limit = 20;
          $order = '`created`';
        } else if($k == 'top') {
          $order = '`weekly_open_count`';
        } else if($k == 'search') {
          $where['search'] = $v;
        }
      }
    }
  }
  return Query('SELECT * FROM `case`', $where, $order, 'DESC', $limit);
}

function getCase($_params) {
  $slug = isset($_params[0]) ? $_params[0] : '';
  $cases = Query('SELECT `case`.*, `users`.`name` AS "username" FROM `case` LEFT JOIN `users` ON `users`.`steamid` = `case`.`steamid`', array('slug' => $slug, 'visible' => 1));
  $case = isset($cases[0]) ? $cases[0] : null;
  if($case) {
    return array('case' => $case, 'items' => Query('SELECT * FROM `cases_items` INNER JOIN `items` ON `items`.`id` = `item`', array('case' => $case['id']), '`items`.`price`', 'DESC'));
  }
  return null;
}

function getCaseSlug($name) {
  return strtolower(str_replace(" ", "-", trim(preg_replace("/[^ \w]+/", "", $name))));
}

function validCaseSlug($slug) {
  global $seo;
  if($slug && !isset($seo[$slug]) && strlen($slug) <= 32) {
    $results = Query('SELECT COUNT(id) AS "count" FROM `case`', array('slug' => $slug));
    if($results[0]['count'] == 0) {
      return 1;
    }
  }
  return 0;
}

function maxCases() {
  global $maxCases;
  return $maxCases;
}

function canCreateCase() {
  $results = Query('SELECT COUNT(id) AS "count" FROM `case`', array('today' => 1, 'steamid' => getUser('steamid')));
  return $results[0]['count'] < maxCases() || isAdmin();
}

function validItems($itemids) {
  return Query('SELECT * FROM `items`', array('validitems' => $itemids, 'visible' => 1));
}

function createCase($name, $image, $items) {
  $slug = getCaseSlug($name);
  $response['success'] = false;
  if(isUser()) {
    if(getUser('ban') == 0) {
      if(canCreateCase()) {
        if($image > 0 && $image < 13) {
          if(validCaseSlug($slug)) {
            if(count($items) >= 2 && count($items) <= 20) {
              $valid = true;
              $itemids = '';
              $percent = 0;
              foreach($items as $item) {
                $itemPercent = number_format($item['value'], 3);
                $itemids .= $item['name'].',';
                $percent += $itemPercent;
                if($itemPercent > 50 || $itemPercent <= 0) {
                  $valid = false;
                }
              }
              if($valid) {
                if($percent == 100) {
                  $validItems = validItems(rtrim($itemids, ','));
                  if(count($validItems) == count($items)) {
                    $caseId = Insert('case', array('name' => $name, 'slug' => $slug, 'image' => $image, 'steamid' => getUser('steamid')));
                    if($caseId) {
                      $success = true;
                      foreach($items as $item) {
                        $itemId = $item['name'];
                        $itemPercent = number_format($item['value'], 3);
                        $caseItemId = Insert('cases_items', array('case' => $caseId, 'item' => $itemId, 'percent' => $itemPercent));
                        if(!$caseItemId) {
                          $success = false;
                        }
                      }
                      if($success) {
                        $results = Query('SELECT SUM(`percent`) AS "count", SUM(`price` * `percent` * 0.01) * 1.2 AS "price" FROM `cases_items` INNER JOIN `items` ON `items`.`id` = `item`', array('case' => $caseId));
                        if($results[0]['count'] == 100) {
                          $price = $results[0]['price'];
                          if($price < 0.04) $price = 0.04;
                          $price = ceil($price * 100) / 100;
                          $itemimg = Query('SELECT `image` FROM `items` INNER JOIN `cases_items` ON `items`.`id` = `item`', array('case' => $caseId), 'price', 'DESC', 1)[0]['image'];
                          if(Update('case', array('id' => $caseId), array('visible' => 1, 'itemimg' => $itemimg, 'price' => $price))) {
                            $response['success'] = true;
                            $response['message'] = 'Case has successfully been created!';
                            $response['slug'] = $slug;
                          } else {
                            deleteCase($caseId);
                            $response['message'] = 'Failed to create case [failed making case live]';
                          }
                        } else {
                          deleteCase($caseId);
                          $response['message'] = 'Failed to create case [item percents do not equal 100%]';
                        }
                      } else {
                        deleteCase($caseId);
                        $response['message'] = 'Failed to create case [failed to add items to case]';
                      }
                    } else {
                      $response['message'] = 'Failed to create case [case name has already been taken]';
                    }
                  } else {
                    $response['message'] = 'Failed to create case [invalid item id]';
                  }
                } else {
                  $response['message'] = 'Failed to create case [the total odds should be 100%]';
                }
              } else {
                $response['message'] = 'Failed to create case [the odds percentage for one item should be between 0.001% and 50%]';
              }
            } else {
              if(count($items) < 2) {
                $response['message'] = 'Failed to create case [the case must contain at least 2 items]';
              } else {
                $response['message'] = 'Failed to create case [the case cannot have more than 20 items]';
              }
            }
          } else {
            $response['message'] = 'Failed to create case [case name has already been taken]';
          }
        } else {
          $response['message'] = 'Failed to create case [selected case image is invalid]';
        }
      } else {
        $response['message'] = 'Failed to create case [user cannot create more than '.maxCases().' cases per day]';
      }
    } else {
      $response['message'] = 'Failed to create case [user account is banned]';
    }
  } else {
    $response['message'] = 'Failed to create case [user must be signed in]';
  }
  return json_encode($response);
}

function deleteCase($caseId, $notification=false) {
  if($caseId && intval($caseId) > 0 && Update('case', array('id' => $caseId, 'visible' => 1, 'steamid' => getUser('steamid')), array('visible' => 0))) {
    if($notification) addMessage(getUser('steamid'), 'error', 'Case has successfully been deleted');
    return 1;
  }
  return null;
}

function openCase($count, $case, $type) {
  $response['success'] = false;
  if($count != 2 && $count != 3 && $count != 4 && $count != 5 && $count != 10) $count = 1;
  if($case) {
    $items = $case['items'];
    $case = $case['case'];
    $success = false;
    $real = $type == 'Test Spin' ? false : true;
    if($real) {
      $price = $case['price'] * $count;
      if(isUser() && removeBalance($price)) {
        $success = true;
      }
    } else {
      $success = true;
    }
    if($success) {
      $slider = '';
      $inside = '';
      $ids = '';
      $sellprice = 0;
      for($i=0; $i<$count; $i++) {
        $percent = mt_rand(0,99).'.'.mt_rand(0,100000000);
        $secret = generateToken(6);
        $hash = md5($percent.':'.$secret);
        $item = getWinningItem($items, $percent);
        $slider .= getSlider($items, $item);
        if($real) {
          if(isAdmin()) {
            $openid = 0;
          } else {
            $openid = Insert('open_cases', array('steamid' => getUser('steamid'), 'case' => $case['id'], 'percent' => $percent, 'secret' => $secret, 'hash' => $hash, 'item' => $item['id']));
            Update('case', array('id' => $case['id']), array('open_count' => '`open_count` + 1', 'weekly_open_count' => '`weekly_open_count` + 1'), false);
          }
          $locked = isPromo() || isAdmin() ? '1' : '0';
          $id = Insert('users_items', array('steamid' => getUser('steamid'), 'itemid' => $item['id'], 'locked' => $locked, 'openid' => $openid));
          $inside .= itemModal($item, $percent, $secret, $hash, $id);
          $ids = $ids.$id.',';
          $sellprice += $item['price'];
        }
      }
      $modal = $real ? createWinningsModal($inside, $ids, $sellprice) : null;
      $response = array('success' => true, 'modal' => $modal, 'slider' => $slider, 'balance' => getBalance());
    } else {
      $response['message'] = 'Failed to open case [insufficent balance]';
    }
  } else {
    $response['message'] = 'Failed to open case [invalid case slug]';
  }
  return json_encode($response);
}

function getWinningItem($items, $percent) {
  $items = array_reverse($items);
  $currentPercent = 0;
  $currItem = null;
  foreach($items as $item) {
    $currItem = $item;
    $currentPercent += $item['percent'];
    if($percent <= $currentPercent) {
      return $item;
    }
  }
  return $currItem;
}

function getItem($item, $winner='') {
  return '<div class="item" '.$winner.'><h4><img class="img-responsive" src="/img/itembg.png" style="background-image:url('.$item['image'].');" title="'.$item['name'].'"/></h4><h3 style="border-bottom-color:#'.$item['quality_color'].';">'.$item['name'].'</h3></div>';
}

function getSlider($items, $item=null) {
  $slider = '<div class="slider">';
  for($i=0;$i<94;$i++) {
    $slider .= getItem($items[array_rand($items)]);
  }
  if($item) $slider .= getItem($item, 'id="winner"');
  $count = $item ? 5 : 6;
  for($i=0;$i<$count;$i++) {
    $slider .= getItem($items[array_rand($items)]);
  }
  $slider .= '</div>';
  return $slider;
}

function createWinningsModal($body, $ids, $price) {
  return createModal('winningsModal', $body, '<button id="sellItem" class="btn btn-primary green sellAll" data-id="'.$ids.'"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Sell All For $'.prettyBalance($price).'</button><button id="openCase" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-refresh" aria-hidden="true"></i> Try Again</button>');
}

function itemModal($item, $percent, $secret, $hash, $id) {
  return '<div class="row">
    <div class="col-sm-3 box">
      <img src="'.$item['image'].'" class="img-responsive" />
      <p>'.$item['name'].'</p>
    </div>
    <div class="col-sm-5 box">
      <p><strong>Percent:</strong> '.$percent.'%<br />
      <strong>Secret:</strong> '.$secret.'<br />
      <strong>Hash:</strong> '.$hash.'</p>
    </div>
    <div class="col-sm-4 box">
      <button id="sellItem" class="btn btn-primary green sellItem" data-id="'.$id.'" data-price="'.$item['price'].'"><i class="fa fa-chevron-circle-down" aria-hidden="true"></i> Sell Item $'.prettyBalance($item['price']).'</button>
      <a href="/tradeup" class="btn btn-primary"><i class="fa fa-check-square-o" aria-hidden="true"></i> To Contract</a>
    </div>
  </div>';
}

function getLiveDrops() {
  $html = '';
  $items = Query('SELECT `items`.*, `users_items`.`id` AS "openid", `case`.`slug`, `case`.`name` AS "casename", `case`.`image` AS "caseimage", `users`.`name` AS "username", `users`.`avatar`
                  FROM `users_items`
                  INNER JOIN `items` ON `items`.`id` = `itemid`
                  INNER JOIN `users` ON `users`.`steamid` = `users_items`.`steamid`
                  LEFT JOIN `open_cases` ON `openid` = `open_cases`.`id`
                  LEFT JOIN `case` ON `open_cases`.`case` = `case`.`id`
                  WHERE (TIMESTAMPDIFF(SECOND, `users_items`.`created`, CURRENT_TIMESTAMP()) > 9 AND `openid` != 0) OR (TIMESTAMPDIFF(SECOND, `users_items`.`created`, CURRENT_TIMESTAMP()) > 3 AND `tradeid` != 0)
                  ORDER BY `users_items`.`created` DESC LIMIT 15');
  foreach($items as $item) {
    $slug = $item['slug'] ? $item['slug'] : 'tradeup';
    $casename = $item['casename'] ? $item['casename'] : 'tradeup';
    $caseimage = $item['caseimage'] ? $item['caseimage'] : 'tradeup';
    $html .= '<li data-id="'.$item['openid'].'"><a href="/'.$slug.'" title="'.$casename.'">
      <img class="img-responsive" src="'.$item['image'].'" />
      <h5 style="color:#'.$item['quality_color'].';">'.$item['name'].'</h5>
      <img src="'.$item['avatar'].'" class="img-responsive avatar" />
      <h6>'.noHTML($item['username']).'</h6>';
      $html .= '<img src="/img/cases/'.$caseimage.'.png" />';
      if(file_exists('/var/www/html/img/cases/'.$item['slug'].'.png')) $html .= '<img src="/img/cases/'.$item['slug'].'.png" />';
    $html .= '</a></li>';
  }
  return $html;
}
