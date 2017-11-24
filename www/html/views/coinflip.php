<?php
$flips = getFlips();
$displayFlips = displayFlips($flips);
$body = '<div id="inventory"></div>';
$footer = '<button id="submitFlip" class="btn btn-primary green" disabled="disabled">Create</button>';
$prefoot = '<p>Selected Items: <span id="selectedCount"></span> / 12<br />Selected Value: $<span id="selectedValue"></span> (Min: $<span id="minValue"></span> • Max: $<span id="maxValue"></span>)</p>';
$modal = createModal('createFlip', $body, $footer, 'lg', 'Create New Coinflip', $prefoot);
?>
<h1 class="title">Coinflip</h1>
<button id="newFlip" class="btn btn-primary green" data-toggle="modal" data-target="#createFlip">Create New</button>
<?= $modal ?>
<?= $displayFlips ?>
