<?php

// RDR2
// MERCHANT PORTAL
// REPORTS
// VIEW

global $rdr2, $order, $orderby, $message, $report, $user_id;

$players_export_link = $_SERVER["PHP_SELF"] . "?action=export-spreadsheet&data=merchant-players&id=" . $user_id . "&orderby=" . $orderby . "&order=" . $order;
$transactions_export_link = $_SERVER["PHP_SELF"] . "?action=export-spreadsheet&data=merchant-transactions&id=" . $user_id . "&orderby=" . $orderby . "&order=" . $order;

if ((!isset($report) || empty($report)) && isset($_REQUEST["report"])) 
	$report = $_REQUEST["report"];

if (empty($report)) 
	$report = "comparative_movement_report";

?>

<div class="rdr2-reports">
	<form id="merchant-reports-filter" method="get">
		<input type="hidden" name="id" value="<?php echo $rdr2->id; ?>" />
		<input type="hidden" name="ajaxaction" id="ajaxaction" value="_ajax_fetch_merchant_report" />
		<?php wp_nonce_field( 'ajax-report-nonce', '_ajax_report_nonce' ); ?>
		<?php if (isset($merchant_reports['filters']) && !empty($merchant_reports['filters'])) echo $merchant_reports['filters']; ?>
		
	</form>
	
	<?php if (isset($merchant_reports['data'])) : ?>
		<div class="report" id="<?php echo $report; ?>"><?php $rdr2->print_admin_merchant_report($report, $user_id, $merchant_reports['period'], $merchant_reports['data']); ?></div>
	<?php endif; ?>
</div>

