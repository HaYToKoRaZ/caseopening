<h1 class="title">Earn Credits</h1>
<div class="box getOffers">
  <div class="row">
   <!--  <div class="col-lg-3 col-md-4 col-sm-12">
      <button class="btn btn-primary" data-offers="1"><img class="img-responsive" src="/img/logobg.png" style="background-image:url(/img/logos/adscend.svg);" alt="Adscend Media" /></button>
    </div> -->
    <div class="col-lg-3 col-md-4 col-sm-12">
      <button class="btn btn-primary green" data-offers="2"><img class="img-responsive"  src="/img/logobg.png" style="background-image:url(/img/logos/adgate.png);" alt="AdGate Rewards" /></button>
    </div>
    <div class="col-lg-3 col-md-4 col-sm-12">
      <button class="btn btn-primary green" data-offers="3"><img class="img-responsive"  src="/img/logobg.png" style="background-image:url(/img/logos/offertoro.png);" alt="OfferToro" /></button>
    </div>
    <!-- <div class="col-lg-3 col-md-4 col-sm-12">
      <button class="btn btn-primary green" data-offers="4"><img class="img-responsive"  src="/img/logobg.png" style="background-image:url(/img/logos/superrewards.png);" alt="SuperRewards" /></button>
    </div> -->
  </div>
</div>
<div class="box offers">
  <div class="se-pre-con"><?= getLoader() ?></div>
 <!--  <div class="embed-responsive embed-responsive-16by9" id="adscend">
    <iframe src="//asmwall.com/adwall/publisher/111103/profile/9615?subid1=<?= getUser('steamid') ?>" frameborder="0" class="embed-responsive-item"></iframe>
  </div> -->
  <div class="embed-responsive embed-responsive-16by9" id="adgate">
    <iframe data-src="//wall.adgaterewards.com/pKaW/<?= getUser('steamid') ?>" frameborder="0" class="embed-responsive-item"></iframe>
  </div>
  <div class="embed-responsive embed-responsive-16by9" id="offertoro">
    <iframe data-src="//offertoro.com/ifr/show/4772/<?= getUser('steamid') ?>/2654" frameborder="0" class="embed-responsive-item"></iframe>
  </div>
  <!-- <div class="embed-responsive embed-responsive-16by9" id="superrewards">
    <iframe data-src="//wall.superrewards.com/super/offers?h=omflqztunnj.802869724485&uid=<?= getUser('steamid') ?>" frameborder="0" class="embed-responsive-item"></iframe>
  </div> -->
</div>
