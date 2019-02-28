<?php

// CollectablesTracker
// Control Panel
// Admin
// View

$message = (isset($_REQUEST['saved'])) ? get_bloginfo('name') . ' options successfully saved.' : FALSE;
?>
		
<div class="wrap rdr2-admin">
	<div class="admin-header">
		<h1>CollectablesTracker Control Panel</h1>
	</div>
	<?php if ($message) : ?>
	<div id="message" class="updated fade">
		<p><?= $message; ?></p>
	</div>
	<?php endif; ?>
	
	<form name="rdr2-control-flush" enctype="application/x-www-form-urlencoded" id="rdr2-control-flush" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="action" value="flush">
		<input type="hidden" name="page" value="rdr2-control">
		<p><label>Flush Generated Statistics <input type="submit" value="Go"></label></p>
	</form>
	
	<form name="rdr2-control-archive-sales" enctype="application/x-www-form-urlencoded" id="rdr2-control-archive-sales" method="get" action="<?php echo $_SERVER['PHP_SELF']; ?>">
		<input type="hidden" name="action" value="archive-inactive-merchant-sales">
		<input type="hidden" name="page" value="rdr2-control">
		<p><label>Archive Inactive Merchant Sales <input type="submit" value="Go"></label></p>
	</form>
</div>