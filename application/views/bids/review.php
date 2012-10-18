<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', "Review Bids") ?>
<?php Section::inject('active_subnav', 'review_bids') ?>
<?php Section::inject('no_page_header', true) ?>
<?php Section::start('additional_scripts'); ?>
<?php echo Jade\Dumper::_html(HTML::script('js/bid-review-hotkeys.js')); ?>
<?php Section::stop(); ?>
<?php echo Jade\Dumper::_html(View::make('projects.partials.toolbar')->with('project', $project)); ?>
<?php echo Jade\Dumper::_html(View::make('bids.partials.dismiss_modal')); ?>
<?php echo Jade\Dumper::_html(View::make('bids.partials.award_modal')->with('project', $project)); ?>
<div class="winning-bid-table-wrapper <?php echo Jade\Dumper::_text($project->winning_bid() ? '' : 'hide'); ?>">
  <h3>Winning Bid</h3>
  <table class="table bids-table winning-bid">
    <thead>
      <tr>
        <th class="unread-and-star" colspan="2"></th>
        <th class="vendor-name">Vendor Name</th>
        <th class="price">Price</th>
        <th class="actions">Actions</th>
      </tr>
    </thead>
    <?php if ($project->winning_bid()): ?>
      <?php echo Jade\Dumper::_html(View::make('bids.partials.bid_for_review')->with('bid', $project->winning_bid())); ?>
    <?php endif; ?>
  </table>
</div>
<h3>Bids awaiting review</h3>
<table class="table bids-table open-bids">
  <thead>
    <tr>
      <th class="unread-and-star" colspan="2"></th>
      <th class="vendor-name">Vendor Name</th>
      <th class="price">Price</th>
      <th class="actions">Actions</th>
    </tr>
  </thead>
  <?php foreach($open_bids as $bid): ?>
    <?php echo Jade\Dumper::_html(View::make('bids.partials.bid_for_review')->with('bid', $bid)); ?>
  <?php endforeach; ?>
</table>
<h3>Dismissed bids</h3>
<table class="table bids-table dismissed-bids">
  <thead>
    <tr>
      <th class="unread-and-star" colspan="2"></th>
      <th class="vendor-name">Vendor Name</th>
      <th class="price">Price</th>
      <th class="actions">Actions</th>
    </tr>
  </thead>
  <?php foreach($dismissed_bids as $bid): ?>
    <?php echo Jade\Dumper::_html(View::make('bids.partials.bid_for_review')->with('bid', $bid)); ?>
  <?php endforeach; ?>
</table>