<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="side-navigation">
	<div id="fixed-header" class="">
		<div class="inner">
			<div class="logo"><a href="<?=base_url()?>" class="logo-container"><img src="<?=base_url('application/assets/images/logo.png')?>" /></a> </div>
			<a href="javascript:void(0);" data-target="main-menu" class="sidenav-trigger"> <i></i><i></i><i></i></a>

			<ul class="inline top-menu user-dropdown">
				<li><a class='dropdown-trigger' href='#' data-target='dropdown1'><span>Admin</span> <i class="material-icons">account_circle</i></a></li>
			</ul>
		</div>
	</div>
	<div id="header" class="dark">
		<div class="nav-wrapper">
			<ul id="main-menu" class="sidenav">
				<li>
					<a class="<?=$this->uri->segment(1) == 'dashboard' ? 'active' : ''?>" title="Dashboard" href="<?=base_url('dashboard')?>"><i class="material-icons">dashboard</i>Dashboard</a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'invoices' && $this->uri->segment(2) != 'settings'? 'active' : ''?>" title="Invoices" href="<?=base_url('invoices')?>"><i class="material-icons">receipt</i>Invoices</a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'clients' ? 'active' : ''?>" title="Clients" href="<?=base_url('clients')?>"><i class="material-icons">supervisor_account</i>Clients</a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'taxes' ? 'active' : ''?>" title="Taxes" href="<?=base_url('taxes')?>"><i class="material-icons">iso</i>Taxes</a>
				</li>
				<li>
					<a class="<?=$this->uri->segment(1) == 'invoices' && $this->uri->segment(2) == 'settings' ? 'active' : ''?>" title="Settings" href="<?=base_url('invoices/settings')?>"><i class="material-icons">settings</i>Invoice Settings</a>
				</li>

			</ul>
		</div>
	</div>
</div>

<!-- ============== Dropdown ====== -->

<!-- Dropdown Structure -->
<ul id='dropdown1' class='dropdown-content'>
	<li><a href="<?=base_url('change-password')?>"><i class="material-icons">security</i>Change Password</a></li>
	<li class="divider" tabindex="-1"></li>
	<li><a href="<?=base_url('logout')?>"><i class="material-icons">exit_to_app</i>Logout</a></li>
	
</ul>