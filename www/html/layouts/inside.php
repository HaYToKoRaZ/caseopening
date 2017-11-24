<!DOCTYPE html>
<html lang="en">
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="robots" content="robots.txt">
		<meta name="author" content="humans.txt">
    <title><?= $_title ?></title>
    <meta name="description" content="Nanoflip.us is a CSGO case opening website. Our cases give great odds on the best items from the sought after Dragon Lore, Medusa and much, much more.">
		<base href="<?= BASE_URL ?>">
    <link rel="icon" href="/img/favicon.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@csgodisease">
    <meta name="twitter:title" content="Nanoflip.us | Cure The Disease (Case Opening Site)">
    <meta name="twitter:description" content="Nanoflip.us is a CSGO case opening website. Our cases give great odds on the best items from the sought after Dragon Lore, Medusa and much, much more.">
    <meta name="twitter:image" content="<?= BASE_URL ?>/img/site.png">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/<?= $css.$currentVersion ?>">
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
  </head>
	<body class="<?= $_page_class ?>" id="<?= $_page_class ?>">
    <div class="se-pre-con"><?= getLoader() ?></div>
    <div id="modal"></div>
    <?php include 'addFunds.php'; ?>
    <?php include 'header.php'; ?>
    <section class="container-fluid">
      <div class="row">
        <div id="message"><?= getMessages() ?></div>
        <div class="col-sm-8">
	      <!-- <div class="alert alert-success" role="alert">
          <strong>20% Bonus Active!</strong> Deposit or earn credits from <a href="/earn">watching videos, filling out surveys, or completing offers</a> and receive an extra 20% instantly!
		    </div> -->
		  <?php include VIEW_PATH.$_view_path.'.php'; ?>
        </div>
        <div class="col-sm-2">
          <?php include 'leftSide.php'; ?>
        </div>
        <div class="col-sm-2">
          <?php include 'rightSide.php'; ?>
        </div>
      </div>
    </section>
    <?php include 'footer.php'; ?>
    <script src="//cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="/js/<?= $js.$currentVersion ?>"></script>
    <script>
      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
      ga('create', 'UA-93932475-1', 'auto');
      ga('send', 'pageview');
    </script>
  </body>
</html>
