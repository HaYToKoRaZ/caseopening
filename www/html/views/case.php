<?php
if($case) {
  $username = $case['username'] ? ' by <a href="//steamcommunity.com/profiles/'.$case['steamid'].'" target="_blank">'.$case['username'].'</a>' : null;
  $button = getUser('steamid') == $case['steamid'] ? '<button id="deleteCase" class="btn btn-primary red" data-id="'.$case['id'].'">Delete Case</button>' : null;
  $title = '<strong>'.$case['name'].'</strong>'.$username.' <em>('.$case['open_count'].' unboxed)</em><span>Price: $'.prettyBalance($case['price']).'</span>';
  $recentOpen = Query('SELECT `items`.*, `open_cases`.*, `users`.`name` AS "username", `users`.`avatar` FROM `open_cases` INNER JOIN `items` ON `item` = `items`.`id` INNER JOIN `users` ON `users`.`steamid` = `open_cases`.`steamid`', array('case' => $case['id']), 'opened', 'DESC', 30);
?>
  <h1 class="title"><?= $title ?></h1>
  <div id="caseOpener">
    <div class="cont">
      <div class="inner">
        <?= getSlider($items) ?>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4">
      <select class="form-control" id="caseCount">
        <option selected="selected">1</option>
        <option>2</option>
        <option>3</option>
        <option>4</option>
        <option>5</option>
        <option>10</option>
      </select>
    </div>
    <div class="col-sm-8">
      <button id="openCase" class="btn btn-primary">Open Case</button>
      <button id="testSpin" class="btn btn-primary green">Test Spin</button>
      <?= $button ?>
    </div>
  </div>
  <h1 class="title">Items</h1>
  <?= displayItems($items) ?>
  <h1 class="title">Recent Winnings</h1>
  <div class="prevopen">
    <?php foreach($recentOpen as $open) { ?>
      <div class="previtem">
        <img class="img-responsive" src="/img/itembg.png" style="background-image:url(<?= $open['image'] ?>);" />
        <h4>Winner: <a href="//steamcommunity.com/profiles/<?= $open['steamid'] ?>" target="_blank"><?= noHTML($open['username']) ?></a>
          <br />Percent: <?= $open['percent'] ?>%
          <br />Content: <?= $open['name'] ?>
        </h4>
      </div>
    <?php } ?>
  </div>
  <audio id="audio-roll">
    <source src="/js/roll.mp3" type="audio/mpeg">
  </audio>
  <audio id="audio-open">
    <source src="/js/crate_open.mp3" type="audio/mpeg">
  </audio>
<?php } else { ?>
  <h1 class="title">Invalid Case</h1>
<?php } ?>
