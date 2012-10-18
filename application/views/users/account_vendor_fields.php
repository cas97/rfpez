<?php if (!isset($vendor) && Auth::user() && Auth::user()->vendor) { ?>
  <?php $vendor = Input::old('vendor') ?: Auth::vendor()->to_array(); ?>
<?php } ?>
<?php if (!isset($services) && Auth::user() && Auth::user()->vendor) { ?>
  <?php $services_list = Auth::user()->vendor->services()->lists('id') ?>
  <?php $services = array(); ?>
  <?php foreach ($services_list as $key => $val) { ?>
    <?php $services[intval($val)] = true; ?>
  <?php } ?>
<?php } ?>
<?php $signup = isset($signup) && $signup; ?>
<div class="row">
  <fieldset class="span5">
    <h3>Work and Capabilities</h3>
    <div class="control-group">
      <label>
        <h5>Your company in 50 words or less</h5>
      </label>
      <textarea name="vendor[more_info]"><?php echo Jade\Dumper::_text($vendor['more_info']); ?></textarea>
    </div>
    <div class="control-group">
      <h5>What kind of work does your company do?</h5>
      <?php foreach(Service::all() as $service): ?>
        <label class="checkbox">
          <input type="checkbox" name="services[<?php echo Jade\Dumper::_text( $service->id ); ?>]" <?php echo Jade\Dumper::_text( (isset($services[$service->id])) ? "checked" : "" ); ?> />
          <?php echo Jade\Dumper::_text($service->name); ?>
        </label>
      <?php endforeach; ?>
    </div>
    <div class="control-group">
      <label>
        <h5>Ballpark average project price</h5>
      </label>
      <select type="text" name="vendor[ballpark_price]">
        <?php foreach(Vendor::$ballpark_prices as $id => $ballpark_price): ?>
          <option value="<?php echo Jade\Dumper::_text( $id ); ?>" <?php echo Jade\Dumper::_text( ($vendor['ballpark_price'] == $id) ? "selected" : "" ); ?>> <?php echo Jade\Dumper::_text( $ballpark_price ); ?> </option>
        <?php endforeach; ?>
      </select>
    </div>
    <h5>Company links</h5>
    <div class="control-group">
      <label>Home page</label>
      <input type="text" name="vendor[homepage_url]" value="<?php echo Jade\Dumper::_text( $vendor['homepage_url'] ); ?>" />
    </div>
    <div class="control-group">
      <label>Portfolio</label>
      <input type="text" name="vendor[portfolio_url]" value="<?php echo Jade\Dumper::_text( $vendor['portfolio_url'] ); ?>" />
    </div>
    <div class="control-group">
      <label>Public source (e.g. github)</label>
      <input type="text" name="vendor[sourcecode_url]" value="<?php echo Jade\Dumper::_text( $vendor['sourcecode_url'] ); ?>" />
    </div>
    <div class="vendor-image-url">
      <div class="control-group">
        <label>Link to an image of your best work (400 x 300px)</label>
        <div class="input-append">
          <input type="text" name="vendor[image_url]" value="<?php echo Jade\Dumper::_text( $vendor['image_url'] ); ?>" />
          <button id="prev-img-btn" class="btn btn-primary disabled" type="button">Preview</button>
        </div>
      </div>
      <label class="vendor-image-preview hide">(Preview)</label>
      <div class="vendor-image-preview vendor-image-preview-frame hide">
        <img />
      </div>
    </div>
  </fieldset>
  <fieldset class="span5">
    <?php if ($signup): ?>
      <h3>Contact Info</h3>
      <div class="control-group">
        <label>Email</label>
        <input type="text" name="user[email]" value="<?php echo Jade\Dumper::_text( isset($user) ? $user['email'] : '' ); ?>" />
      </div>
      <div class="control-group">
        <label>Choose a Password</label>
        <input type="password" name="user[password]" />
      </div>
    <?php else: ?>
      <h3>Credentials</h3>
      <label class="larger">
        <?php echo Jade\Dumper::_text(Auth::user()->email); ?>
        <a class="smaller" href="<?php echo Jade\Dumper::_text(route('change_email')); ?>">change email</a>
      </label>
      <label class="larger">
        (password hidden)
        <a class="smaller" href="<?php echo Jade\Dumper::_text(route('change_password')); ?>">change password</a>
      </label>
      <h3>Contact Info</h3>
    <?php endif; ?>
    <div class="control-group">
      <label>DUNS Number</label>
      <input type="text" name="vendor[duns]" value="<?php echo Jade\Dumper::_text( $vendor['duns'] ); ?>" />
    </div>
    <?php if (!$signup): ?>
      <div class="control-group">
        <label>Registered in SAM.gov?</label>
        <?php if ($vendor["sam_entity_name"]): ?>
          <div class="green">Yes, under "<?php echo Jade\Dumper::_text($vendor["sam_entity_name"]); ?>"</div>
        <?php else: ?>
          <div class="red">No</div>
        <?php endif; ?>
      </div>
      <div class="control-group">
        <label>Registered in DSBS?</label>
        <?php if ($vendor["dsbs_name"]): ?>
          <div class="green">Yes, under "<?php echo Jade\Dumper::_text($vendor["dsbs_name"]); ?>"
</div>
          <?php echo Jade\Dumper::_html(View::make('vendors.partials.dsbs_certifications')->with('user_id', $vendor["dsbs_user_id"])); ?>
        <?php else: ?>
          <div class="red">No</div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div class="control-group">
      <label>Company Name</label>
      <input type="text" name="vendor[company_name]" value="<?php echo Jade\Dumper::_text( $vendor['company_name'] ); ?>" />
    </div>
    <div class="control-group">
      <label>Person to Contact</label>
      <input type="text" name="vendor[contact_name]" value="<?php echo Jade\Dumper::_text( $vendor['contact_name'] ); ?>" />
    </div>
    <div class="control-group">
      <label>Address</label>
      <input type="text" name="vendor[address]" value="<?php echo Jade\Dumper::_text( $vendor['address'] ); ?>" />
    </div>
    <div class="control-group">
      <label>City</label>
      <input type="text" name="vendor[city]" value="<?php echo Jade\Dumper::_text( $vendor['city'] ); ?>" />
    </div>
    <div class="control-group">
      <label>State</label>
      <select name="vendor[state]">
        <?php foreach(Helper::all_us_states() as $code => $state): ?>
          <option value="<?php echo Jade\Dumper::_text( $code ); ?>" <?php echo Jade\Dumper::_text( ($vendor['state'] == $code) ? "selected" : "" ); ?>> <?php echo Jade\Dumper::_text($state); ?> </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="control-group">
      <label>Zip</label>
      <input type="text" name="vendor[zip]" value="<?php echo Jade\Dumper::_text( $vendor['zip'] ); ?>" />
    </div>
  </fieldset>
</div>