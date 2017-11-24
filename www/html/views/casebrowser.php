<?php
$activePopular = null;
$activeNew = $activeTop = $activeMy = 'green';
$disabled = isUser() ? '' : 'disabled';
if($_params) {
  if($_params[0] == 'new') {
    $_POST['new'] = 1;
    $activePopular = 'green';
    $activeNew = null;
  } else if($_params[0] == 'top') {
    $_POST['top'] = 1;
    $activePopular = 'green';
    $activeTop = null;
  } else if($_params[0] == 'my') {
    $_POST['steamid'] = 1;
    $activePopular = 'green';
    $activeMy = null;
  }
}
?>
<h1 class="title">Case Browser</h1>
<div class="box getCases">
  <div class="row">
    <div class="col-lg-2 col-md-3 col-sm-6">
      <button class="btn btn-primary <?= $activePopular ?>">Most Popular</button>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6">
      <button class="btn btn-primary <?= $activeNew ?>">New Cases</button>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6">
      <button class="btn btn-primary <?= $activeTop ?>">Top 7 Days</button>
    </div>
    <div class="col-lg-2 col-md-3 col-sm-6">
      <button class="btn btn-primary <?= $activeMy ?>" <?= $disabled ?>>My Cases</button>
    </div>
    <div class="col-lg-4 col-md-12 col-sm-12">
      <input type="text" id="searchCaseName" placeholder="Case Name" />
      <button class="btn btn-primary green">Search</button>
    </div>
  </div>
</div>
<div id="browseCases">
  <?php include('process/getCases.php'); ?>
</div>
