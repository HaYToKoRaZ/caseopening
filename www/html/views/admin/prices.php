<?php
$items = json_decode(file_get_contents('https://api.skfpay.com/trade/loadServerInventory/'));
$items = $items->response->inventory;
$insertItems = array();
foreach($items as $item) {
  $name = trim($item->market_name);
  if(strpos($item->itemType, 'Contraband') !== false) {
    $color = 'FFC924';
  } else if(strpos($item->itemType, 'Covert') !== false) {
    $color = 'EB4B4B';
  } else if(strpos($item->itemType, 'Classified') !== false) {
    $color = 'D32CE6';
  } else if(strpos($item->itemType, 'Restricted') !== false) {
    $color = '8847FF';
  } else if(strpos($item->itemType, 'Mil-Spec') !== false) {
    $color = '4B69FF';
  } else {
    $color = '5E98D9';
  }
  $image = $item->icon_url;
  $price = prettyBalance($item->sa_price, false);
  if(isset($insertItems[$name])) {
    $insertItems[$name]['count']+=1;
  } else {
    $insertItems[$name] = array(
      'name' => $name,
      'quality_color' => $color,
      'image' => $image,
      'price' => $price,
      'count' => 1
    );
  }
}
foreach($insertItems as $item) {
  $results = Query('SELECT COUNT(`id`) AS "count" FROM `items`', array('name' => $item['name']));
  if($results[0]['count'] == 1) {
    Update('items', array('name' => $item['name']), $item);
  } else {
    Insert('items', $item);
  }
}
$results = Query('SELECT `case`.*,
                 (SELECT `items`.`image` FROM `items` INNER JOIN `cases_items` ON `items`.`id` = `cases_items`.`item` WHERE `cases_items`.`case` = `case`.`id` ORDER BY `items`.`price` DESC LIMIT 1) AS "itemimg",
                 SUM(`items`.`price` * `cases_items`.`percent` * 0.01) * 1.2 AS "price"
                 FROM `case`
                 INNER JOIN `cases_items` ON `cases_items`.`case` = `case`.`id`
                 INNER JOIN `items` ON `items`.`id` = `cases_items`.`item`
                 WHERE `steamid` != 1
                 GROUP BY `case`.`id`');
foreach($results as $case) {
  $price = $case['price'];
  if($price < 0.04) $price = 0.04;
  $price = ceil($price * 100) / 100;
  Update('case', array('id' => $case['id']), array('price' => $price, 'itemimg' => $case['itemimg']));
}
?>
