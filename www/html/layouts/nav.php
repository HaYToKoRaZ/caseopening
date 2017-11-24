<?php
$other = '<li><a href="" target="_blank"><i class="fa fa-support" aria-hidden="true"></i> Support</a></li>';
$games = menuItem('cases', 'home','Cases');
$games .= menuItem('casebrowser', 'list', 'Case Browser');
if(isUser()) {
  $games .= menuItem('casecreator', 'pencil-square-o', 'Case Creator');
  $games .= menuItem('tradeup', 'check-square-o', 'Trade Up Contracts');
  $account = menuItem('inventory', 'archive', 'Inventory');
  $account .= menuItem('affiliates', 'star', 'Affiliates');
  $account .= '<li><a data-toggle="modal" data-target="#addFunds"><i class="fa fa-usd" aria-hidden="true"></i> Deposit</a></li>';
  // $account .= menuItem('earn', 'money', 'Earn Credits');
  if(isAdmin()) {
    $games .= menuItem('coinflip', 'adjust', 'Coinflip');
    $other .= menuItem('admin', 'lock', 'Admin');
  }
} else {
  $account = menuItem('?login', 'sign-in', 'Login');
}
?>
<li class="category">Games</li>
<?= $games ?>
<li class="category">Account</li>
<?= $account ?>
<li class="category">Other</li>
<?= $other ?>
