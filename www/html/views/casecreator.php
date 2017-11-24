<h1 class="title">Case Creator</h1>
<div id="steps">
  <div class="step step1 active" data-desc="Case Name"><p>1</p><p>✔</p></div>
  <div class="step step2" data-desc="Case Picture"><p>2</p><p>✔</p></div>
  <div class="step step3" data-desc="Case Items"><p>3</p><p>✔</p></div>
  <div class="step step4" data-desc="Confirm Case"><p>4</p><p>✔</p></div>
</div>

<div id="step1">
  <h1 class="title">#1 Enter Case Name</h1>
  <div class="box">
    <input type="text" id="caseName" placeholder="Case Name" />
  </div>
  <div class="row">
    <div class="col-xs-6">
      <p>Characters remaining: <span id="caseNameSize">32</span></p>
    </div>
    <div class="col-xs-6">
      <button class="btn btn-primary green caseStep2">Next Step</button>
    </div>
  </div>
</div>

<div id="step2">
  <h1 class="title">#2 Choose Case Picture</h1>
  <div class="box">
    <div class="row">
    <?php for($i=1; $i<13; $i++) {
      echo '<label class="col-sm-2">
              <input type="radio" name="caseImage" value="'.$i.'" />
              <img src="/img/cases/'.$i.'.png" class="img-responsive" />
            </label>';
    } ?>
    </div>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <button class="btn btn-primary green caseStep1">Previous Step</button>
    </div>
    <div class="col-xs-6">
      <button class="btn btn-primary green caseStep3">Next Step</button>
    </div>
  </div>
</div>

<div id="step3">
  <h1 class="title">#3 Add Your Skins</h1>
  <div class="box">
    <input type="text" id="searchItems" placeholder="Search Items" />
    <div id="viewItems"></div>
    <form id="caseItems"></form>
    <h4>Total Odds <span id="casePercent">0.000</span>%</h4>
    <h4>Case Price <span id="casePrice">N/A</span></h4>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <button class="btn btn-primary green caseStep2">Previous Step</button>
    </div>
    <div class="col-xs-6">
      <button class="btn btn-primary green caseStep4">Next Step</button>
    </div>
  </div>
</div>

<div id="step4">
  <h1 class="title">#4 Confirm Case</h1>
  <div class="box">
    <p>By creating a case, you automatically agree to our ToS found <a href="/tos">here</a>.</p>
    <h4>Case Name: <span id="confirmName">N/A</span></h4>
    <h4>Price: <span id="confirmPrice">N/A</span></h4>
  </div>
  <div class="row">
    <div class="col-xs-6">
      <button class="btn btn-primary green caseStep3">Previous Step</button>
    </div>
    <div class="col-xs-6">
      <button id="caseCreate" class="btn btn-primary green">Create Case</button>
    </div>
  </div>
</div>
