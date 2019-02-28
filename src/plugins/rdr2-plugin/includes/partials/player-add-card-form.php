<?php

// RDR2
// PLAYER ADD CARD FORM 
// PARTIAL

global $rdr2;

$result = FALSE;
$name = FALSE;
$fullcardnumber = FALSE;
$partialcardnumber = FALSE;
$cardnumber = (isset($_REQUEST["cardnumber"])) ? $_REQUEST["cardnumber"] : FALSE;

if ($cardnumber !== FALSE) {
	$fullcardnumber = $rdr2->make_full_card_number($cardnumber);
	$partialcardnumber = $rdr2->make_partial_card_number($cardnumber);
	$result = $rdr2->get_player_lite($fullcardnumber);
	
	if (!empty($result)) 
		$name = $rdr2->make_player_name($result);
}

?>
<p>Enter the card number from the back of your CollectablesTracker Rewards Card (above the barcode) and then click 'Find'.</p>
<form enctype="application/x-www-form-urlencoded" method="get" name="find-card" id="find-card-form">
	<p>601690 <input type="text" name="cardnumber" placeholder="000000000000" value="<?php echo $partialcardnumber; ?>"> <input type="submit" value="Find" class="button button-primary"></p>
</form>
<?php if ($cardnumber !== FALSE) : ?>
	<?php if (empty($result)) : ?>
	<p class="message error">Sorry, we couldn't find a CollectablesTracker card with that number. Please check and try again!</p> 
	<?php else : ?>
	<p class="message status success">Found card number: <span class="highlight"><?php echo $fullcardnumber; ?> - <?php echo $name; ?></span></p>
	<p><form enctype="application/x-www-form-urlencoded" method="get" name="add-card" id="add-card-form"><input type="submit" class="button button-primary" value="Add Card to My Account"><input type="hidden" name="cardnumber" value="<?php echo $cardnumber; ?>"><input type="hidden" name="action" value="addcard"></form></p>
	<?php endif; ?>
<?php else : ?>
<p class="message info">No card? No worries! <a href="/store-locator">Find a participating location near you</a> to start earning rewards!</p>
<?php endif; ?>
