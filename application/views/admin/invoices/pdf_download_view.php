<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<style>
		.tbl-right{
			text-align: right;
		}
		.tbl-center{
			text-align: center;
		}
		table.invoice-basic-info .to_address .name{
			font-weight: 600;
		}
		table.items thead th, 
		table.items tbody tr.sub-total td, 
		table.items tbody tr.grand-total td{
			background: #ccc6c6;
		}
		table.items tbody tr td {
		    border-bottom: 1px solid #ccc6c6;
		}
		table.items th, table.items td {
		    padding: 12px;
		}
		table.invoice-footer tbody tr td{
			border:1px solid #000;
			border-radius: 10px;
			padding: 12px;
		}
		table.invoice-footer{
			margin-top: 10px;
		}
		p{
			margin:0;
		}
	</style>
</head>
<body>
	<table class="invoice-basic-info"  width="100%">
		<tbody>
			<tr>
				<td width="50%">
					<h2>Invoice</h2>
					<div class="invoice-base">
						<p><span>Invoice No</span> : #<?=$invoice->invoice_no?></p>
						<p><span>Invoice Date</span> : <?=date("d F Y", strtotime($invoice->invoice_date))?></p>
					</div>
					<div class="to_address">
						<h3>To,</h3>
						<p class="name"><?=$invoice->name?></p>
						<p><?=$invoice->email?></p>
						<p><?=$invoice->phone?></p>
						<p><?=nl2br($invoice->address)?></p>
						<?php
						if($invoice->gstin_no){
							echo "<p>GSTIN NO : {$invoice->gstin_no}</p>";
						}
						?>
					</div>
				</td>
				<td width="50%" class="tbl-right">
					<img src="<?=$company_logo?>" alt="" />
					<div class="company-address">
						<?=nl2br($invoice_address)?>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<table class="items" cellpadding="0" cellspacing="0"  width="100%">
		<thead>
			<tr>
				<th class="tbl-center">Sr No.</th>
				<th class="tbl-center">Item Name</th>
				<th class="tbl-center">Unit</th>
				<th class="tbl-right">Price</th>
				<th class="tbl-right">Actual Amt.</th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$total_amount = 0;
			$grand_total  = 0;
			$inc          = 0;
			$not_found    = true;
			if(isset($invoice) && isset($invoice->items) && count(json_decode($invoice->items)) > 0){
				$items = json_decode($invoice->items);
				foreach ($items as $key => $value) {
					$not_found    = false;
					$amount = $value->price * $value->unit;
					$total_amount += $amount;
					?>
					<tr>
						<td class="tbl-center"><?=$inc+1?></td>
						<td class="tbl-center">
							<p><?=$value->name?></p>
							<p><?=$value->description?></p>
						</td>
						<td class="tbl-center"><?=$value->unit?>/<?=ucfirst($value->unit_type)?></td>
						<td class="tbl-right"><?=$invoice->currency_symbol?><?=number_format(($value->price), 2)?></td>
						<td class="tbl-right"><?=$invoice->currency_symbol?><?=number_format(($amount), 2)?></td>
					</tr>
					<?php
					$inc++;
				}
				$grand_total = $total_amount;
			}
			if($inc > 0){
				?>
				<tr class="sub-total">
					<td colspan="4" class="tbl-right">Sub Total </td>
					<td class="total-amount tbl-right"><?=$invoice->currency_symbol?><?=number_format($total_amount, 2)?></td>
				</tr>
				<?php
			}
			if(isset($invoice) && isset($invoice->tax_details) && count(json_decode($invoice->tax_details)) > 0  && isset($invoice->items) && count(json_decode($invoice->items)) > 0){
				$tax_details = json_decode($invoice->tax_details);
				$taxes = json_decode($tax_details->taxes);
				foreach ($taxes as $key => $tax_row) {
					$tax_amount = $tax_row->type=="percentage" ? (($total_amount / 100) * $tax_row->value) : ($tax_row->value);
					$grand_total += $tax_amount;
					?>
					<tr class="taxes">
						<td colspan="4" class="tbl-right"> <?=$tax_row->name?>(<?=$tax_row->type=="percentage"?"{$tax_row->value}%":$tax_row->value?>)</th>
						<td  class="tbl-right"><?=$invoice->currency_symbol?><?=number_format($tax_amount, 2)?></td>
					</tr>	
					<?php
				}
				
			}
    		$inc = 0;
    		if(isset($invoice) && isset($invoice->extra_items) && count(json_decode($invoice->extra_items)) > 0){
				$extra_items = json_decode($invoice->extra_items);
				foreach ($extra_items as $key => $extra_row) {
					$not_found    = false;
					$grand_total += $extra_row->amount;
					?>
					<tr>
						<td colspan="4" class="tbl-right">
							<?=$extra_row->name?>
						</td>
						<td class="tbl-right">
							<?=$invoice->currency_symbol?><?=number_format($extra_row->amount, 2)?>
						</td>
					</tr>
				<?php
					$inc++;
				}
			}
			if($not_found){
				?>
				<tr class="no-invoice">
    				<td colspan="5" class="tbl-center">No items added.</td>
    			</tr>
				<?php
			}
			?>
			<tr class="grand-total">
				<td colspan="4" class="tbl-right">Grand Total </td>
				<td class="total-amount tbl-right"><?=isset($invoice) && isset($invoice->currency_symbol)? $invoice->currency_symbol : $default_currency?><?=number_format($grand_total, 2)?></td>
			</tr>
		</tbody>
	</table>
	<table class="invoice-footer" width="100%">
		<tbody>
			<tr>
				<td width="50%">
					<p>Payment Options</p>
					<div class="account">
						<?=nl2br($invoice_bank_account)?>	
					</div>
				</td>
				<td width="50%">
					Dear <?=$invoice->name?>,
					<p><?=nl2br($invoice_thanks_msg)?></p>
					<?php 
					if($invoice->note){
						?>
						<div class="note">
							<div class="caption">Please Note: </div>
							<?=nl2br($invoice->note)?>								
						</div>
						<?php
					}
					?>
					<p>* - All Amounts above are Inclusive of all taxes</p>
				</td>
			</tr>
		</tbody>
	</table>
	
</body>
</html>