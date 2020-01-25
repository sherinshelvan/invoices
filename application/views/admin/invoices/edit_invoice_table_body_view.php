<?php defined('BASEPATH') OR exit('No direct script access allowed'); 
function get_extra_items(){

}
$inc = 0;
$total_amount = 0;
if(isset($items) && count($items) > 0){
	foreach ($items as $key => $value) {
		$value['price'] = is_numeric($value['price'])?$value['price']:0;
		$value['unit'] = is_numeric($value['unit'])?$value['unit']:0;
		$amount = $value['price'] * $value['unit'];
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
				<input type="text" required name="items[<?=$inc?>][name]" value="<?=$value['name']?>"  />
			</td>
			<td>
				<textarea class="materialize-textarea description" data-length="200" name="items[<?=$inc?>][description]"  ><?=$value['description']?></textarea>
			</td>
			<td>
				<select name="items[<?=$inc?>][unit_type]" id="">
					<option <?=$value['unit_type']=='quantity'?"selected":""?> value="quantity">Quantity</option>
					<option <?=$value['unit_type']=='hour'?"selected":""?> value="hour">Hour</option>
					<option <?=$value['unit_type']=='minutes'?"selected":""?> value="minutes">Minutes</option>
					<option <?=$value['unit_type']=='month'?"selected":""?> value="month">Month</option>
					<option <?=$value['unit_type']=='week'?"selected":""?> value="week">Week</option>
					<option <?=$value['unit_type']=='day'?"selected":""?> value="day">Day</option>
				</select>
			</td>
			<td>
				<input type="text" required name="items[<?=$inc?>][unit]" class="natural-no-validate"  value="<?=$value['unit']?>"/>
			</td>
			<td>
				<input type="text" required name="items[<?=$inc?>][price]" class="natural-no-validate" value="<?=$value['price']?>"  />
			</td>
			<td><?=$currency?><?=number_format(($amount), 2)?></td>
		</tr>
		<?php
		$inc++;
	}
}
if(isset($action) && $action == 'add-item'){	
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
			<input type="text" required name="items[<?=$inc?>][name]"  />
		</td>
		<td>
			<textarea class="materialize-textarea description" data-length="200" name="items[<?=$inc?>][description]"  ></textarea>
		</td>
		<td>
			<select name="items[<?=$inc?>][unit_type]" id="">
				<option value="quantity">Quantity</option>
				<option value="hour">Hour</option>
				<option value="minutes">Minutes</option>
				<option value="month">Month</option>
				<option value="week">Week</option>
				<option value="day">Day</option>
			</select>
		</td>
		<td>
			<input type="text" required name="items[<?=$inc?>][unit]" class="natural-no-validate" value="0" />
		</td>
		<td>
			<input type="text" required name="items[<?=$inc?>][price]" class="natural-no-validate" value="0" />
		</td>
		<td><?=$currency?>0.00</td>
	</tr>
	<?php
}
?>
<tr class="no-result" style=" display: none;">
	<td colspan="8" style="text-align: center;">No items added.</td>
</tr>
<?php 
$grand_total = $total_amount;
if(isset($tax_id) && $tax_id  && $tax_details && $tax_details->taxes && count(json_encode($tax_details->taxes)) > 0 && ((isset($items) && count($items) > 0))
 || (isset($action) && $action == 'add-item' && isset($tax_id) && $tax_id)
 || (isset($items) && isset($extra_items) )
 || (isset($action) && $action == 'add-extra' && isset($items))
 || (isset($action) && $action == 'add-item' && isset($extra_items))
){
	?>
	<tr class="total">
		<th colspan="7" style="text-align: right;">Sub Total </th>
		<th class="total-amount"><?=$currency?><?=number_format($total_amount, 2)?></th>
	</tr>
	<?php
}
if(isset($tax_id) && $tax_id && $tax_details && $tax_details->taxes && count(json_encode($tax_details->taxes)) > 0 && ((isset($items) && count($items) > 0)) || (isset($action) && $action == 'add-item')){
	$taxes = json_decode($tax_details->taxes);
	foreach ($taxes as $key => $tax_row) {
		$tax_amount = $tax_row->type=="percentage" ? (($total_amount / 100) * $tax_row->value) : ($tax_row->value);
		$grand_total += $tax_amount;
		?>
		<tr class="taxes">
			<td colspan="7" style="text-align: right;"> <?=$tax_row->name?>(<?=$tax_row->type=="percentage"?"{$tax_row->value}%":$tax_row->value?>)</th>
			<td><?=$currency?><?=number_format($tax_amount, 2)?></td>
		</tr>
	
	<?php
	}
}
$inc = 0;
if(isset($extra_items) && count($extra_items) > 0){
	foreach ($extra_items as $key => $extra_row) {
		$grand_total += $extra_row['amount'];
		?>
		<tr>
			<td>
				<label>
			        <input type="checkbox" class="multi-checkbox item" />
			        <span></span>
		      </label>
			</td>
			<td colspan="6">
				<input type="text" required value="<?=$extra_row['name']?>" name="extra_items[<?=$inc?>][name]" placeholder="Expense Title">
			</td>
			<td>
				<input type="text" value="<?=$extra_row['amount']?>" name="extra_items[<?=$inc?>][amount]" class="number-validate negative" placeholder="Amount">
			</td>
		</tr>
		<?php
		$inc++;
	}

}
if(isset($action) && $action == 'add-extra'){
	?>
	<tr>
		<td>
			<label>
		        <input type="checkbox" class="multi-checkbox item" />
		        <span></span>
	      </label>
		</td>
		<td colspan="6">
			<input type="text" name="extra_items[<?=$inc?>][name]" placeholder="Expense Title">
		</td>
		<td>
			<input type="text" name="extra_items[<?=$inc?>][amount]" class="number-validate negative" placeholder="Amount">
		</td>
	</tr>
	<?php
}
?>
<tr class="grand-total">
	<th colspan="7" style="text-align: right;">Grand Total </th>
	<th class="total-amount"><?=$currency?><?=number_format($grand_total, 2)?></th>
</tr>
