<?php
ob_start();
session_start();

if(!isUser() && isset($_COOKIE['STEAMID']) && isset($_COOKIE['SESSID']) && $_COOKIE['STEAMID'] && $_COOKIE['SESSID']) {
	$_SESSION['steamid'] = $_COOKIE['STEAMID'];
	$_SESSION['shash'] = $_COOKIE['SESSID'];
}

if(isset($_GET['login'])) {
	require 'openid.php';
	try {

		$openid = new LightOpenID(BASE_URL);

		if(!$openid->mode) {
			$openid->identity = 'http://steamcommunity.com/openid';
			header('Location: ' . $openid->authUrl());
		} elseif ($openid->mode == 'cancel') {
			echo 'User has canceled authentication!';
		} else {
			if($openid->validate()) {
				$id = $openid->identity;
				$ptn = "/^http:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($ptn, $id, $matches);

				$_SESSION['steamid'] = $matches[1];
				$_SESSION['shash'] = generateToken(64);

				$results = Query('SELECT id FROM users', array('steamid' => $matches[1]));
				if(!$results) {
					Insert('users', array('steamid' => $matches[1]));
				}
				if(!headers_sent()) {
					header('Location: '.BASE_URL);
					exit;
				} else {
					?>
					<script type="text/javascript">
						window.location.href="<?= BASE_URL ?>";
					</script>
					<noscript>
						<meta http-equiv="refresh" content="0;url=<?= BASE_URL ?>" />
					</noscript>
					<?php
					exit;
				}
			} else {
				echo "User is not logged in.\n";
			}
		}
	} catch(ErrorException $e) {
		echo $e->getMessage();
	}
}

if(isset($_GET['logout'])){
	Update('users', array('steamid' => $_SESSION['steamid']), array('shash' => ''));
	session_unset();
	session_destroy();
	setcookie('SESSID', '');
	setcookie('STEAMID', '');
	header('Location: /');
	exit;
}

if(isset($_GET['update']) || !empty($_SESSION['steam_uptodate']) && $_SESSION['steam_uptodate']+(24*60*60) < time()){
	unset($_SESSION['steam_uptodate']);
	setMessage('alert', 'Your steam information has been refreshed.');
	header('Location: /');
	exit;
}
