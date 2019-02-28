<?php

// RDR2
// ADMIN
// MERCHANT
// VIEW

global $rdr2, $order, $orderby, $message, $report;

$players_export_link = $_SERVER["PHP_SELF"] . "?page=" . $_REQUEST["page"] . "&action=export-spreadsheet&data=merchant-players&id=" . $merchant["ID"] . "&orderby=" . $orderby . "&order=" . $order;
$transactions_export_link = $_SERVER["PHP_SELF"] . "?page=" . $_REQUEST["page"] . "&action=export-spreadsheet&data=merchant-transactions&id=" . $merchant["ID"] . "&orderby=" . $orderby . "&order=" . $order;

if ((!isset($report) || empty($report)) && isset($_REQUEST["report"])) 
	$report = $_REQUEST["report"];

if (empty($report)) 
	$report = "comparative_movement_report";

?>
<?php if (isset($merchant) && $merchant !== FALSE) : ?>
<div id="merchant-tabs" class="tabs-container luckytabs toptabs">
	<ul class="pill merchant-selector luckyswitcher nav-tab-wrapper">
		<li class="nav-tab"><a href="#merchant-info" class=""><span class="dashicons dashicons-info"></span> Maintenance</a></li>
		<li class="nav-tab"><a href="#merchant-players" class=""><span class="dashicons dashicons-admin-users"></span> Players</a></li>
		<li class="nav-tab"><a href="#merchant-transactions" class=""><span class="dashicons dashicons-cart"></span> Transactions</a></li>
		<li class="nav-tab"><a href="#merchant-stores" class=""><span class="dashicons dashicons-location"></span> Stores</a></li>		
		<li class="nav-tab"><a href="#merchant-reports" class=""><span class="dashicons dashicons-clipboard"></span> Reports</a></li>
		<li class="nav-tab"><a href="#merchant-statistics" class=""><span class="dashicons dashicons-chart-area"></span> Statistics</a></li>
	</ul>
	
	<div class="merchant-tab luckytab" id="merchant-info">
		<h2 class="screen-reader-text">Merchant Maintenance</h2>
		
		<h3>Overview</h3>
		<ul class="nolist horizontal">
			<li><strong>Status:</strong> <?php echo $merchant["Status"]; ?></li>
			
			<li><strong>Inception:</strong> <?php echo $merchant["InceptionDate"]; ?></li>
			<li><strong>Last Changed By:</strong> <?php echo $merchant["LastChangedBy"]; ?></li>
		</ul>
		<form name="player-info-form" id="player-info-form">
			<div class="admin-info-grid">
				<div class="merchant-basics">
					<h3>Basics</h3>
					<table>
						<tbody>
							<tr><th scope="row"><label>ID</label></th><td><?php echo $merchant["ID"]; ?></td></tr>
							<tr><th scope="row"><label>Name </label></th><td><?php echo $merchant["Name"]; ?></td></tr>
							<tr><th scope="row"><label>Proprietor</label></th><td><?php echo $merchant["Proprietor"]; ?></td></tr>
							<tr><th scope="row"><label>ABN</label></th><td><?php echo $merchant["ABN"]; ?></td></tr>
							<tr><th scope="row"><label>Website</label></th><td><a href="<?php echo $merchant["Website"]; ?>" target="_blank"><?php echo $merchant["Website"]; ?></a></td></tr>
							<tr><th scope="row"><label>Nature of Business</label></th><td><?php echo $merchant["NatureOfBusiness"]; ?></td></tr>
							<tr><th scope="row"><label>Locality</label></th><td><?php echo $merchant["Locality"]; ?></td></tr>
							<tr><th scope="row"><label>Cluster</label></th><td><?php echo $merchant["Cluster"]; ?></td></tr>
							<tr><th scope="row"><label>Group</label></th><td><?php echo $merchant["Group"]; ?></td></tr>
						</tbody>
					</table>
					
				</div>
				<div class="merchant-contacts">
					<h3>Contacts</h3>
					<h4>Primary Contact</h4>
					<table>
						<tbody>
							<tr><th scope="row"><label>Name</label></th><td><?php echo $merchant["PrimaryContact"]; ?></td></tr>
							<tr><th scope="row"><label>Email Address</label></th><td><a href="mailto:<?php echo $merchant["PrimaryEmail"]; ?>"><?php echo $merchant["PrimaryEmail"]; ?></a></td></tr>
							<tr><th scope="row"><label>Phone Number</label></th><td><a href="tel:<?php echo str_replace(" ", "", $merchant["PrimaryPhone"]); ?>"><?php echo $merchant["PrimaryPhone"]; ?></a></td></tr>
							<tr><th scope="row"><label>Fax Number</label></th><td><a href="tel:<?php echo str_replace(" ", "", $merchant["PrimaryFax"]); ?>"><?php echo $merchant["PrimaryFax"]; ?></a></td></tr>
						</tbody>
					</table>
					<h4>Agents</h4>
					<table>
						<tbody>
							<tr><th scope="row"><label>Managing Agent</label></th><td><?php echo $merchant["ManagingAgent"]; ?></td></tr>
							<tr><th scope="row"><label>Sign On Agent</label></th><td><?php echo $merchant["SignOnAgent"]; ?></td></tr>
							<tr><th scope="row"><label>Player Service Agent</label></th><td><?php echo $merchant["PlayerServiceAgent"]; ?></td></tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="admin-info-grid">
				<div class="merchant-location">
					<h3>Location</h3>
					<table>
						<tbody>
							<tr><th scope="row"><label>Address</label></th><td><?php echo $merchant["Address"]; ?></td></tr>
							<tr><th scope="row"><label>Suburb</label></th><td><?php echo $merchant["Suburb"]; ?></td></tr>
							<tr><th scope="row"><label>State</label></th><td><?php echo $merchant["State"]; ?></td></tr>
							<tr><th scope="row"><label>Postcode</label></th><td><?php echo $merchant["Postcode"]; ?></td></tr>
						</tbody>
					</table>
				</div>
				<div class="merchant-map admin-map">
					Merchant Location Map
				</div>
			</div>
		</form>
		
		<div class="merchant-portal">
			<h3>Merchant Portal Account</h3>
			<?php if (!isset($merchant_account) || empty($merchant_account)) : ?>
				<p>No Portal account found for this Merchant.</p>
				<?php if (isset($merchant_portal_info)) : ?>
				<p>The following information is available for creating an account:</p>
				<?php echo $merchant_portal_info; ?>		
				<p><a href="?page=rdr2-merchants&id=<?php echo $merchant["ID"]; ?>&action=create-merchant-account" class="button button-primary button-large"><span class="dashicons dashicons-admin-network"></span> Create Merchant Portal Account</a></p>				
				<?php endif; ?>
			<?php else : ?>
				<p>Merchant Portal Account is active! <?php if (isset($merchant_account->lastlogin)) : ?>Last login: <?php echo $merchant_account->lastlogin; ?>.<?php endif; ?></p>
				<p><a href="?page=rdr2-merchants&id=<?php echo $merchant["ID"]; ?>&action=delete-merchant-account" class="button button-primary button-large"><span class="dashicons dashicons-admin-network"></span> Delete Merchant Portal Account</a></p>
				<?php //print_r($merchant_account); ?>
			<?php endif; ?>
		</div>
	</div>


	
	<div class="merchant-tab luckytab" id="merchant-players">
		<h2>Merchant Players</h2>
		<form id="merchant-players-filter" method="get">
			<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_players" />
			<input type="hidden" name="page" value="<?php echo $rdr2->page; ?>" />
			<input type="hidden" name="id" value="<?php echo $merchant["ID"]; ?>" />
			<?php if (isset($merchant_players_filters) && !empty($merchant_players_filters)) : ?>
				<?php echo $merchant_players_filters; ?><input type="submit" class="button button-primary" value="Update Filter">
			<?php endif; ?>				
			
			<p class="alignright"><a href="<?php echo $players_export_link; ?>" class="button"><span class="dashicons dashicons-media-spreadsheet"></span> Export Merchant Players</a></p>
			<?php if (isset($merchant_players_table)) $merchant_players_table->display(); ?>
			
		</form>
	</div>
	

	<div class="merchant-tab luckytab" id="merchant-transactions">
		<h2>Merchant Transactions</h2>
		<form id="merchant-transactions-filter" method="get">
			<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_transactions" />
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<input type="hidden" name="id" value="<?php echo $merchant["ID"]; ?>" />
			<?php if (isset($merchant_transactions_filters) && !empty($merchant_transactions_filters)) : ?>
				<?php echo $merchant_transactions_filters; ?>
				<input type="submit" class="button button-primary" value="Update Filter">
			<?php endif; ?>
			<p class="alignright"><a href="<?php echo $transactions_export_link; ?>" class="button"><span class="dashicons dashicons-media-spreadsheet"></span> Export Merchant transactions</a></p>
			<?php if (isset($merchant_transactions_table)) $merchant_transactions_table->display(); ?>
		</form>
	</div>

	<div class="merchant-tab luckytab" id="merchant-stores">
		<h2>Merchant Stores</h2>
		<form id="merchant-stores-filter" method="get">
			<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_stores" />
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<input type="hidden" name="id" value="<?php echo $merchant["ID"]; ?>" />
			<input type="hidden" name="hide_res" value="0" />
			<?php if (isset($merchant_stores_table)) $merchant_stores_table->display(); ?>
		</form>
	</div>
	

	<div class="merchant-tab luckytab" id="merchant-reports">
		<h2>Merchant Reports</h2>
		<form id="merchant-reports-filter" method="get">
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<input type="hidden" name="id" value="<?php echo $merchant["ID"]; ?>" />
			<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_report" />
			<?php wp_nonce_field( 'ajax-report-nonce', '_ajax_report_nonce' ); ?>
			<?php if (isset($merchant_reports['filters']) && !empty($merchant_reports['filters'])) echo $merchant_reports['filters']; ?>
			
		</form>
		
		<?php if (isset($merchant_reports['data'])) : ?>
			<div class="report" id="<?php echo $report; ?>"><?php $rdr2->print_admin_merchant_report($report, $merchant["ID"], $merchant_reports['period'], $merchant_reports['data']); ?></div>
		<?php endif; ?>
	</div>
	
	
	<div class="merchant-tab luckytab" id="merchant-statistics">
		<h2>Merchant Statistics</h2>
		
	</div>
</div>

<script>

(function($) {
	
	$(document).ready(function() {
		
		$(".lb-toggle-show").on("change", function() {
			$("#merchants-filter").submit();
		});
		
		$("#merchant-stores-filter").listy();
		
		$("#merchant-players-filter").listy();
		
		$("#merchant-transactions-filter").listy();
		
		$("#merchant-reports-filter").reporty();
		
	});

} )( jQuery );





</script>
<?php endif; ?>
	


