<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-wrapper">
	<div class="top-bg gradient-45deg-indigo-purple"></div>
	<div id="main-content">
		<div class="container edit-container">
			<div class="page-title"><h3 class="white-text"><?=$page_heading?></h3></div>
			<div class="card">
				<?php echo form_open();?>
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
			          	<?=form_input($old)?>
			            <label for="old">Old Password</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s12">
			          	<?=form_input($new)?>
			            <label for="new">New Password</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s12">
			          	<?=form_input($new_confirm)?>
			            <label for="new">Confirm Password</label>
			            <span class="helper-text" data-error="Required field"></span>
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
<script type="text/javaScript">
	$(document).on("keypress", "input.natural-no-validate", function(event){
		var val = $(this).val();
		if ( isNaN( String.fromCharCode(event.keyCode) ) && String.fromCharCode(event.keyCode) != '.') {
			return false
		};
	});
	$(document).ready(function() {
    	$('textarea#description').characterCounter();
  	});
</script>