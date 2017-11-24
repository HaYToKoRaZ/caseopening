<nav class="navbar">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#main-nav" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand logo" href=""><img src="/img/csgodisease.png" class="img-responsive" /></a>
    </div>
    <div class="header-social">
      <div class="left">
        <a href="http://steamcommunity.com/groups/tuz1k.com" target="_blank"><i class="fa fa-steam" ria-hidden="true"></i></a>
        <a href="https://twitter.com/" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a>
        <a id="toggleMute">
        <?php
          $mute = isset($_COOKIE['mute']) && $_COOKIE['mute'] == 1 ? 'off' : 'up';
          echo '<i class="fa fa-volume-'.$mute.'" aria-hidden="true"></i>';
        ?>
        </a>
      </div>
      <div class="right">
        <h5>Currently Online:</h5><h6><span id="onlineUsers"><?= isset($_COOKIE['online']) ? $_COOKIE['online'] : null ?></span> Players</h6>
      </div>
    </div>
    <div class="collapse navbar-collapse" id="main-nav">
      <ul class="nav navbar-nav navbar-right">
        <div class="header-nav"><?php include('nav.php'); ?></div>
        <?php if(isUser()) { ?>
          <li><a href="?logout" style="margin-right:15px;" title="Logout"><i class="fa fa-sign-out" aria-hidden="true"></i></a></li>
          <li><a href="inventory" title="Inventory"><i class="fa fa-user" aria-hidden="true"></i></a></li>
          <li><a href="affiliates" title="Free Credits"><i class="fa fa-star" aria-hidden="true"></i></a></li>
          <li><a class="btn btn-primary green" data-toggle="modal" data-target="#addFunds"><i class="fa fa-plus" aria-hidden="true"></i> Add Funds</a></li>
          <li><a class="btn btn-primary green" href="/affiliates"><i class="fa fa-star" aria-hidden="true"></i> FREE COINS</a></li>
        <?php } else { ?>
          <li class="login"><a class="btn btn-primary green" href="?login">Login</a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
</nav>