<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-wrapper">
	<div class="top-bg gradient-45deg-indigo-purple"></div>
	<div id="main-content">
		<div class="container edit-container">
			<div class="page-title"><h3 class="white-text"><?=$page_heading?></h3></div>
			<div class="card">
				<?php echo form_open($action_url, 'id="invoice_settings"');?>
				<?php 
				if($message){
					?>
					<div class="row">
						<div class="col s12 red-text text-darken-2">
							<?=$message?>
						</div>
					</div>
					<?php
				}
				?>
				<div class="row">
					<div class="input-field col s12">
						Default Currency
						<?=form_dropdown($invoice_currency)?>
					</div>
					<div class="input-field col s6">
						Invoice Address
						<?=form_textarea($invoice_address)?>
					</div>
					<div class="input-field col s6">
						Bank Account
						<?=form_textarea($invoice_bank_account)?>
					</div>
					<div class="input-field col s12">
						Invoice Thanks Message
						<?=form_textarea($invoice_thanks_msg)?>
					</div>
			        <div class="input-field col s12">
			        	<?=form_hidden($csrf)?>
	        			<?=form_button($submit)?>
			        </div>
			    </div>
				<?php echo form_close();?>
				
			</div>
			
		</div>
	</div>
</div>
<script type="text/javaScript" src="<?=base_url("application/assets/js/table_check.js")?>"></script>
<script type="text/javaScript">
	$(document).ready(function() {
    	$('textarea').characterCounter();
  	});
</script>