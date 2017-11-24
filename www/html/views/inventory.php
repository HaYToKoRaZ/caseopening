<?php
$items = getInventory();
$sellAll = 0;
$ids = '';
foreach($items as $item) {
  if($item['status'] == 1) {
  	$ids .= $item['id'] . ',';
  	$sellAll += $item['price'];
  }
}
?>
<h1 class="title">Withdraw Settings</h1>
<form method="post">
  <div class="box">
    <input name="tlink" placeholder="Steam Trade URL" value="<?= getUser('tlink') ?>" />
  </div>
  <a class="btn btn-primary" href="https://steamcommunity.com/id/me/tradeoffers/privacy#trade_offer_access_url" target="_blank">Get URL</a>
  <button type="submit" class="btn btn-primary green">Save Settings</button>
</form>
<h1 class="title">Inventory</h1>
<?php
echo $items ? displayItems($items) : null;
echo $sellAll > 0 ? '<button class="btn btn-primary green sellAll" id="sellItem" data-id="'.$ids.'">Sell All For $'.prettyBalance($sellAll).'</button>' : null;
?>
