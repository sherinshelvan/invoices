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
				<?php echo form_open($action_url, 'id="edit_invoice"');?>
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
					<div class="col s12"></div>
					<div class="col s6">
						<h4>From,</h4>
						<?=nl2br($invoice_address)?>
					</div>
					<div class="col s6">
						<h4>To,</h4>
						<div class="input-field">
							<?=form_dropdown($client_id)?>
						</div>
						<div class="client-details">
							<?php 
							if(isset($result)){
								?>
								<p><?=$result->name?></p>
								<p><?=$result->email?></p>
								<p><?=$result->phone?></p>
								<p><?=nl2br($result->address)?></p>
								<?php
								if($result->gstin_no){
									echo "<p>GSTIN NO : {$result->gstin_no}</p>";
								}
							}
							?>
						</div>
					</div>
					<div class="col s12"></div>
			        <div class="input-field col s6">
			          	<?=form_input($invoice_no)?>
			            <label for="invoice_no">Invoice Number</label>
			            <span class="helper-text" data-error="Required field"></span>
			        </div>
			        <div class="input-field col s6">
			        	<label for="invoice_date">Invoice Date</label>
			            <span class="helper-text" data-error="Required field"></span>
			        	<?=form_input($invoice_date)?>
			        </div>
			        <div class="input-field col s6">
			        	<label for="">Tax</label>
			        	<?=form_dropdown($tax_id)?>
			        </div>
			        <div class="input-field col s6">
			        	<label for="">Currency</label>
			        	<?=form_dropdown($currency)?>
			        </div>
			        <div class="col s12">
			        	<table class="invoice-table">
			        		<thead>
			        			<tr>
			        				<th>
								      <label>
								        <input type="checkbox" class="multi-checkbox all" />
								        <span></span>
								      </label>
			        				</th>
			        				<th>Item No</th>
			        				<th>Item Name</th>
			        				<th>Item Description</th>
			        				<th>Unit Type</th>
			        				<th>Unit</th>
			        				<th>Price</th>
			        				<th>Total</th>
			        			</tr>
			        		</thead>
			        		<tbody>
			        			<?php 
								$total_amount = 0;
								$grand_total  = 0;
								$inc          = 0;
								$not_found    = true;
			        			if(isset($result) && isset($result->items) && count(json_decode($result->items)) > 0){
			        				$items = json_decode($result->items);
			        				foreach ($items as $key => $value) {
			        					$not_found    = false;
			        					$amount = $value->price * $value->unit;
										$total_amount += $amount;
			        					?>
			        					<tr>
											<td>
												<label>
											        <input type="checkbox" class="multi-checkbox item" />
											        <span></span>
										      </label>
											</td>
											<td><?=$inc+1?></td>
											<td>
												<input type="text" required name="items[<?=$inc?>][name]" value="<?=$value->name?>"  />
											</td>
											<td>
												<textarea class="materialize-textarea description" data-length="200" name="items[<?=$inc?>][description]"  ><?=$value->description?></textarea>
											</td>
											<td>
												<select name="items[<?=$inc?>][unit_type]" id="">
													<option <?=$value->unit_type =='quantity'?"selected":""?> value="quantity">Quantity</option>
													<option <?=$value->unit_type =='hour'?"selected":""?> value="hour">Hour</option>
													<option <?=$value->unit_type =='minutes'?"selected":""?> value="minutes">Minutes</option>
													<option <?=$value->unit_type =='month'?"selected":""?> value="month">Month</option>
													<option <?=$value->unit_type =='week'?"selected":""?> value="week">Week</option>
													<option <?=$value->unit_type =='day'?"selected":""?> value="day">Day</option>
												</select>
											</td>
											<td>
												<input type="text" required name="items[<?=$inc?>][unit]" class="natural-no-validate"  value="<?=$value->unit?>"/>
											</td>
											<td>
												<input type="text" required name="items[<?=$inc?>][price]" class="natural-no-validate" value="<?=$value->price?>"  />
											</td>
											<td><?=$result->currency_symbol?><?=number_format(($amount), 2)?></td>
										</tr>
										<?php
			        					$inc++;
			        				}
			        				$grand_total = $total_amount;
			        			}
			        			if($inc > 0){
			        				?>
			        				<tr class="total">
										<th colspan="7" style="text-align: right;">Sub Total </th>
										<th class="total-amount"><?=$result->currency_symbol?><?=number_format($total_amount, 2)?></th>
									</tr>
			        				<?php
			        			}
			        			if(isset($result) && isset($result->tax_details) && count(json_decode($result->tax_details)) > 0  && isset($result->items) && count(json_decode($result->items)) > 0){
			        				$tax_details = json_decode($result->tax_details);
			        				$taxes = json_decode($tax_details->taxes);
			        				foreach ($taxes as $key => $tax_row) {
			        					$tax_amount = $tax_row->type=="percentage" ? (($total_amount / 100) * $tax_row->value) : ($tax_row->value);
										$grand_total += $tax_amount;
			        					?>
										<tr class="taxes">
											<td colspan="7" style="text-align: right;"> <?=$tax_row->name?>(<?=$tax_row->type=="percentage"?"{$tax_row->value}%":$tax_row->value?>)</th>
											<td><?=$result->currency_symbol?><?=number_format($tax_amount, 2)?></td>
										</tr>	
			        					<?php
			        				}
			        				
			        			}
			        			$inc = 0;
			        			if(isset($result) && isset($result->extra_items) && count(json_decode($result->extra_items)) > 0){
			        				$extra_items = json_decode($result->extra_items);
			        				foreach ($extra_items as $key => $extra_row) {
			        					$not_found    = false;
			        					$grand_total += $extra_row->amount;
			        					?>
										<tr>
											<td>
												<label>
											        <input type="checkbox" class="multi-checkbox item" />
											        <span></span>
										      </label>
											</td>
											<td colspan="6">
												<input type="text" required value="<?=$extra_row->name?>" name="extra_items[<?=$inc?>][name]" placeholder="Expense Title">
											</td>
											<td>
												<input type="text" value="<?=$extra_row->amount?>" name="extra_items[<?=$inc?>][amount]" class="number-validate negative" placeholder="Amount">
											</td>
										</tr>
									<?php
			        					$inc++;
			        				}
			        			}
			        			if($not_found){
			        				?>
			        				<tr class="no-result">
				        				<td colspan="8" style="text-align: center;">No items added.</td>
				        			</tr>
			        				<?php
			        			}
			        			?>
			        			
			        			<tr class="grand-total">
									<th colspan="7" style="text-align: right;">Grand Total </th>
									<th class="total-amount"><?=isset($result) && isset($result->currency_symbol)? $result->currency_symbol : $default_currency?><?=number_format($grand_total, 2)?></th>
								</tr>
			        		</tbody>
			        	</table>
			        </div>
			        <div class="col s12">
			        	<button type="button" class="add-item waves-effect waves-light green darken-2 btn-small">Add Item</button>
			        	<button type="button" class="add-extra waves-effect waves-light green darken-2 btn-small">Add Extra Detail</button>
			        	<button type="button" class="delete-item waves-effect waves-light red darken-2 btn-small">-</button>
			        </div>
			        <div class="col s12 input-field">
			        	<label for="">Notes</label>
			        	<?=form_textarea($note)?>
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

	$(document).ready(function(){
		$("#invoice_date").datepicker({autoClose:true,format:"dd-mm-yyyy",
			defaultDate	: new Date('<?=date("Y-m-d", strtotime($invoice_date["value"]))?>'), setDefaultDate : true});
	});
	var invoice_table = $("form#edit_invoice table.invoice-table");
	$(document).on("click", "form#edit_invoice button.add-item", function(event){
		alter_table('add-item');
	});
	$(document).on("click", "form#edit_invoice button.add-extra", function(event){
		alter_table('add-extra');
	});
	$(document).on("change", "form#edit_invoice select#tax_id", function(event){
		alter_table();
	});
	$(document).on("change", "form#edit_invoice select#currency", function(event){
		alter_table();
	});
	$(document).on("click", "form#edit_invoice button.delete-item", async function(event){
		await $("table input[type='checkbox'].multi-checkbox.item").each(function(ck){
			if($(this).prop('checked')){
				$(this).parents('tr').remove();
			}
		});
		alter_table();
	});
	function checkTableLength(){
		$("table input[type='checkbox'].multi-checkbox.all").prop('checked', false); 
		var rowCount = invoice_table.find('tbody > tr').length;
		
		if(rowCount > 2){
			invoice_table.find('tbody > tr.no-result').hide();
		}
		else{
			invoice_table.find('tbody > tr.no-result').show();
		}
	}
	function alter_table(action = ''){
		var formData = $("form#edit_invoice").serializeArray();
		formData.push({ name: "action", value: action });
		$.ajax({
			url : '<?=base_url("admin/".$this->page_url)?>/alter_table',
			method : "POST",
			data : formData
		}).done(function(response){
			invoice_table.find('tbody').html(response);
			checkTableLength();
			$('textarea.description').characterCounter();
			$('select').formSelect();
		});
	}
	$(document).on("change", "select.currency", function(event){
		alter_table();
	});
	$(document).on("change", "select.client_id", function(event){
		var formdata = $("form#edit_invoice").serialize();
		$.ajax({
			url : '<?=base_url("admin/".$this->page_url)?>/client_details',
			method : "POST",
			data : formdata
		}).done(function(response){
			$(".client-details").html(response);
		});
	});
	$(document).on("keypress", "input.number-validate", function(event){
		var val = $(this).val();
		if( isNaN( String.fromCharCode(event.keyCode) ) && String.fromCharCode(event.keyCode) != '-' && String.fromCharCode(event.keyCode) != '.') {
			return false;
		}
	});
	$(document).on("change", "input.number-validate", function(event){
		alter_table();
	});
	$(document).on("change", "input.natural-no-validate", function(event){
		alter_table();
	});
	$(document).on("keypress", "input.natural-no-validate", function(event){
		var val = $(this).val();
		if ( isNaN( String.fromCharCode(event.keyCode) ) && String.fromCharCode(event.keyCode) != '.' ) {
			return false
		};
	});
	$(document).ready(function() {
    	$('textarea.description, textarea#note').characterCounter();
  	});
</script>