<?php
$items = getInventory();
?>
<h1 class="title">Trade Up Contracts</h1>
<div class="row sameheight">
	<div class="col-md-3 leftbox">
		<div class="box">
			<h4>Total Value: <br /><span id="tradeValue">$0</span></h4>
			<h4>Amount of Items: <br /><span id="tradeCount">0</span> / 10</h4>
			<h4>Potential Outcome: <br /><span id="tradeMin">$0</span> to <span id="tradeMax">$0</span></h4>
			<h4><button class="btn btn-primary green tradeUp" disabled><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Sign Contract</button></h4>
			<h4 class="progress"><div class="progress-bar"></h4>
			<small>Max. Value: $500 | Min. Items: 3</small>
		</div>
	</div>
	<div class="col-md-9">
		<div class="items" id="tradeItems">
			<?php for($i=1; $i<=10; $i++) {
			  echo '<div class="col-lg-2 col-md-3 col-sm-6 col-xs-4 noitem">
			          <i class="fa fa-times-circle" aria-hidden="true" title="Remove from Contact"></i>
			          <h4><img class="img-responsive" src="/img/itembg.png" /></h4>
			          <h5><br />Slot #'.$i.'</h5>
			        </div>';
			} ?>
		</div>
	</div>
</div>
<h1 class="title">Inventory</h1>
<div id="tradeinventory">
	<?= $items ? displayItems($items, 'trade') : null ?>
</div>
<audio id="audio-loading">
	<source src="/js/loading.mp3" type="audio/mpeg">
</audio>
<audio id="audio-open">
	<source src="/js/crate_open.mp3" type="audio/mpeg">
</audio>
