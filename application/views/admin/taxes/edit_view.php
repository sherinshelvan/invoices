<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="main-wrapper">
	<div class="top-bg gradient-45deg-indigo-purple"></div>
	<div id="main-content">
		<div class="container edit-container">
			<div class="page-title"><h3 class="white-text"><?=$page_heading?></h3></div>
			<div class="card">
				<div class="add-new left">
					<a href="<?=base_url($this->page_url)?>" class="waves-effect waves-light btn deep-purple darken-2"><i class="material-icons left">arrow_back</i></a>
				</div>
				<?php echo form_open($action_url);?>
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
			          	<?=form_input($name)?>
			            <label for="name">Tax Name</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="col s12 ">
		        		<h4 class="deep-purple-text">Taxe Items
							<span class="waves-effect waves-light btn-small deep-purple darken-2 tax-add"><i class="material-icons">add</i></span>
		        		</h4>		        		
		        		<div class="tax-items">
		        		<?php 
		        		if(isset($exist_details) && isset($exist_details->taxes) && $exist_details->taxes){
		        			$taxes = json_decode($exist_details->taxes);
		        			foreach ($taxes as $key => $value) {
		        				?>
			        			<div class="row tax-item">
				        			<div class="col s6">
				        				<input type="text" required name="taxes[<?=$key?>][name]" value="<?=$value->name?>" placeholder="Tax" />
				        			</div>
				        			<div class="col s1">
				        				<select name="taxes[<?=$key?>][type]">
				        					<option  value="percentage">%</option>
				        					<option <?=$value->type == "flat" ? "selected" : ""?> value="flat">=</option>
				        				</select>
				        			</div>
				        			<div class="col s3">
				        				<input type="text" required class="natural-no-validate" name="taxes[<?=$key?>][value]"  value="<?=$value->value?>" placeholder="Value" />
				        			</div>
				        			<div class="col s2"><i class="material-icons remove">close</i></div>
				        		</div>
		        				<?php
		        			}
		        		}
		        		else{
		        			?>
		        			<div class="row tax-item">
			        			<div class="col s6">
			        				<input type="text" required name="taxes[0][name]" placeholder="Tax" />
			        			</div>
			        			<div class="col s1">
			        				<select name="taxes[0][type]">
			        					<option value="percentage">%</option>
			        					<option value="flat">=</option>
			        				</select>
			        			</div>
			        			<div class="col s3">
			        				<input type="text" required class="natural-no-validate" name="taxes[0][value]" placeholder="Value" />
			        			</div>
			        			<div class="col s2"><i class="material-icons remove">close</i></div>
			        		</div>
		        			<?php
		        		}
		        		?>
		        		</div>
		        		
		        	</div>
			        
			        <!-- Switch -->
					<div class="switch input-field col s12">
					    <label>
					      Inactive
					      <?=form_checkbox($active)?>
					      <span class="lever"></span>
					      Active
					    </label>
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
	$(document).on("click", "span.tax-add", function(event){
		var id = Date.now();
		var item = '<div class="row tax-item">\
        			<div class="col s6">\
        				<input type="text" required name="taxes['+id+'][name]" placeholder="Tax" />\
        			</div>\
        			<div class="col s1">\
        				<select name="taxes['+id+'][type]">\
        					<option value="percentage">%</option>\
        					<option value="flat">=</option>\
        				</select>\
        			</div>\
        			<div class="col s3">\
        				<input type="text" required class="natural-no-validate" name="taxes['+id+'][value]" placeholder="Value" />\
        			</div>\
        			<div class="col s2"><i class="material-icons remove">close</i></div>\
        		</div>';
		$("div.tax-items").append(item);
		$('select').formSelect();
	});
	$(document).on("click", ".tax-items .tax-item .remove", function(event){
		$(this).parents('.tax-item').remove();
	});
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