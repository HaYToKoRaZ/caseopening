<?php if(isUser()) { ?>
  <div class="profile">
    <img src="<?= getUser('avatar') ?>" class="img-responsive" />
    <h5><?= getUser('name') ?></h5>
    <h6>Balance: $<span id="balance"><?= getBalance() ?></span></h6>
  </div>
<?php } ?>
<ul class="left-nav">
  <?php include('nav.php'); ?>
</ul>
<?php include('footer.php'); ?>
