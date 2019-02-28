<?php

// RDR2
// ADMIN
// PLAYERS
// VIEW

global $rdr2, $order, $orderby, $message;

$name = (isset($player_name)) ? $player_name : $player["Name"];
$transactions_export_link = $_SERVER["PHP_SELF"] . "?page=" . $_REQUEST["page"] . "&action=export-spreadsheet&data=player-transactions&id=" . $_REQUEST["id"] . "&orderby=" . $orderby . "&order=" . $order;
$points_export_link = $_SERVER["PHP_SELF"] . "?page=" . $_REQUEST["page"] . "&action=export-spreadsheet&data=player-points&id=" . $_REQUEST["id"] . "&orderby=" . $orderby . "&order=" . $order;

?>
<?php if (isset($player) && $player !== FALSE) : ?>
<div id="portal-tabs" class="tabs-container luckytabs toptabs">
	<ul class="pill location-selector nav-tab-wrapper">
		<li class="nav-tab"><a href="#player-info" class=""><span class="dashicons dashicons-info"></span> Maintenance</a></li>
		<li class="nav-tab"><a href="#player-points" class=""><span class="dashicons dashicons-star-filled"></span> Points Summary</a></li>
		<li class="nav-tab"><a href="#player-transactions" class=""><span class="dashicons dashicons-cart"></span> Transactions</a></li>
		<li class="nav-tab"><a href="#player-notes" class=""><span class="dashicons dashicons-welcome-write-blog"></span> Notes</a></li>
		<li class="nav-tab"><a href="#player-statistics" class=""><span class="dashicons dashicons-chart-area"></span> Statistics</a></li>
	</ul>
	
	<div class="player-tab luckytab" id="player-info">
		<h2 class="screen-reader-text">Player Maintenance</h2>
		<?php /*
		<ul class="pill horizontal">
			<li class=""><a href="#player-info-basics" class=""><span class="dashicons dashicons-info"></span> Basics</a></li>
			<li class=""><a href="#player-info-contacts" class=""><span class="dashicons dashicons-phone"></span> Contacts</a></li>
			<li class=""><a href="#player-info-portal" class=""><span class="dashicons dashicons-admin-network"></span> Portal</a></li>
		</ul>
		 * 
		 * 
		 */ ?>
		<h3>Overview</h3>
		<ul class="nolist horizontal">
			<li><strong>Status:</strong> <?php echo $player["Status"]; ?></li>
			<li><strong>Joined:</strong> <?php echo $player["DateCreated"]; ?></li>
			<li><strong>Merchant:</strong> <?php if (!empty($player["MerchantID"])) : ?><a href="?page=rdr2-merchants&id=<?php echo $player["MerchantID"]; ?>" alt="Go to <?php echo $player["MerchantName"]; ?>"><?php echo $player["MerchantID"]; ?> - <?php echo $player["MerchantName"]; ?></a><?php else : ?>N/A<?php endif; ?></li>
			<li><strong>Staff Member:</strong> <?php echo $player["UserField4"]; ?></li>
			<li><strong>Entered:</strong> <?php echo $player["DateEntered"]; ?></li>
			<li><strong>Last Edited:</strong> <?php echo $player["DateLastChanged"]; ?></li>
			<li><strong>Number of Sales:</strong> <?php echo $player["NumberOfSales"]; ?></li>
			<li><strong>Last Sale:</strong> <?php echo $player["DateOfLastSale"]; ?></li>
			<li><strong>Points Balance (Non-Pooled):</strong> <?php echo $player["PointsBalance"]; ?></li>
		</ul>
		<form name="player-info-form" id="player-info-form">
			<div class="admin-info-grid">
				<div class="player-basics">
					<h3>Basics</h3>
					<table>
						<tbody>
							<tr><th scope="row"><label>Salutation</label></th><td><?php echo $player["Salutation"]; ?></td></tr>
							<tr><th scope="row"><label>Initial </label></th><td><?php echo $player["Initial"]; ?></td></tr>
							<tr><th scope="row"><label>Given Name</label></th><td><?php echo $player["FirstName"]; ?></td></tr>
							<tr><th scope="row"><label>Family Name</label></th><td><?php echo $player["Surname"]; ?></td></tr>
							<tr><th scope="row"><label>Gender</label></th><td><?php echo $player["Gender"]; ?></td></tr>
							<tr><th scope="row"><label>Age</label></th><td><?php echo $player["Age"]; ?> Years</td></tr>
							<tr><th scope="row"><label>Date of Birth</label></th><td><?php echo $player["DateOfBirth"]; ?></td></tr>
							<tr><th scope="row"><label>Occupation</label></th><td><?php echo $player["Position"]; ?></td></tr>
						</tbody>
					</table>
				</div>
				<div class="player-contacts">
					<h3>Contacts</h3>
					<table>
						<tbody>
							<tr><th scope="row"><label>Email Address</label></th><td><a href="mailto:<?php echo $player["EmailAddress"]; ?>"><?php echo $player["EmailAddress"]; ?></a></td></tr>
							<tr><th scope="row"><label>Phone Number 1</label></th><td><a href="tel:<?php echo str_replace(" ", "", $player["PhoneNumber1"]); ?>"><?php echo $player["PhoneNumber1"]; ?></a></td></tr>
							<tr><th scope="row"><label>Phone Number 2</label></th><td><a href="tel:<?php echo str_replace(" ", "", $player["PhoneNumber2"]); ?>"><?php echo $player["PhoneNumber2"]; ?></a></td></tr>
							<tr><th scope="row"><label>Mobile Phone Number 1</label></th><td><a href="tel:<?php echo str_replace(" ", "", $player["MobilePhone"]); ?>"><?php echo $player["MobilePhone"]; ?></a></td></tr>
							<tr><th scope="row"><label>Mobile Phone Number 2</label></th><td><a href="tel:<?php echo str_replace(" ", "", $player["MobilePhone2"]); ?>"><?php echo $player["MobilePhone2"]; ?></a></td></tr>
							<tr><th scope="row"><label>Fax Number</label></th><td><a href="tel:<?php echo str_replace(" ", "", $player["FaxNumber"]); ?>"><?php echo $player["FaxNumber"]; ?></a></td></tr>
						</tbody>
					</table>
				</div>
			</div>
			<div class="admin-info-grid">
				<div class="player-location">
					<h3>Location</h3>
					<table>
						<tbody>
							<tr><th scope="row"><label>Address Line 1</label></th><td><?php echo $player["Address"]; ?></td></tr>
							<tr><th scope="row"><label>Address Line 2</label></th><td><?php echo $player["Address2"]; ?></td></tr>
							<tr><th scope="row"><label>Suburb</label></th><td><?php echo $player["Suburb"]; ?></td></tr>
							<tr><th scope="row"><label>State</label></th><td><?php echo $player["State"]; ?></td></tr>
							<tr><th scope="row"><label>Postcode</label></th><td><?php echo $player["Postcode"]; ?></td></tr>
						</tbody>
					</table>
				</div>
				<div class="player-map admin-map">
					Player Location Map
				</div>
			</div>
		</form>
		<div class="player-portal">
			<h3>Player Portal</h3>
			<p>Create a Player Portal account for this Card or attach it to an existing account.</p>
		</div>
	</div>

	<div class="player-tab luckytab" id="player-points">
		<h2>Player Points Summary</h2>
		<form id="player-transactions-filter" method="get">
			<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_player_points" />
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
			<?php if (isset($player_points_filters) && !empty($player_points_filters)) echo $player_points_filters; ?>
			<?php /*<p class="alignright"><a href="<?php echo $points_export_link; ?>" class="button"><span class="dashicons dashicons-media-spreadsheet"></span> Export Player Points</a></p>*/?>
			<?php if (isset($player_points_table)) $player_points_table->display(); ?>
		</form>
	</div>

	<div class="player-tab luckytab" id="player-transactions">
		<h2>Player Transactions</h2>
		<form id="player-transactions-filter" method="get">
			<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_player_transactions" />
			<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
			<input type="hidden" name="id" value="<?php echo $_REQUEST["id"]; ?>" />
			<?php if (isset($player_transactions_filters) && !empty($player_transactions_filters)) echo $player_transactions_filters; ?>
			<?php /*<p class="alignright"><a href="<?php echo $transactions_export_link; ?>" class="button"><span class="dashicons dashicons-media-spreadsheet"></span> Export Player Transactions</a></p>*/?>
			<?php if (isset($player_transactions_table)) $player_transactions_table->display(); ?>
		</form>
	</div>
	
	<div class="player-tab luckytab" id="player-notes">
		<h2>Player Notes</h2>
		
	</div>
	
	<div class="player-tab luckytab" id="player-statistics">
		<h2>Player Statistics</h2>
		
	</div>
</div>

<script>

(function($) {
	
	$(document).ready(function() {
		
		$("#player-transactions-filter").listy();
		
	});

} )( jQuery );

</script>
<?php endif; ?>