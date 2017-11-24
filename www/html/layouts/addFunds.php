<?php
$body = '<div class="row paypal">
    <div class="col-sm-12 box">
      <div class="row">
        <div class="col-sm-6">
          <img class="img-responsive" src="/img/logos/skfpay.png" />
        </div>
        <div class="col-sm-6">
          <button class="btn btn-primary green addFunds">Deposit Skins</button>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12 box">
      <div class="row">
        <div class="col-sm-6">
          <img class="img-responsive" src="/img/logos/bitcoin.png" />
        </div>
        <div class="col-sm-6">
          <button class="btn btn-primary green" id="btcDeposit">Deposit Bitcoin</button>
          <div id="btcStep2">
            <input type="number" name="amount" value="20" step="any" min="5" id="btcValue" />
            <input type="submit" name="submit" class="btn btn-primary green" value="Deposit" id="btcCreate" />
          </div>
        </div>
      </div>
    </div>
  </div>
  <form method="post" class="row">
    <div class="col-sm-12 box">
      <div class="row">
        <div class="col-sm-8">
          <input type="text" name="code" placeholder="XXXXX-XXXXX-XXXXX-XXXXX" size="30" maxlength="30" />
        </div>
        <div class="col-sm-4">
          <input type="submit" class="btn btn-primary green" value="Redeem Code" />
        </div>
      </div>
    </div>
  </form>';
echo createModal('addFunds', $body, null, 'md', 'Add Funds to Your Balance', '<p>By depositing, you automatically agree to our ToS found <a href="/tos">here</a>.</p>');
