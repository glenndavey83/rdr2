<?php

?>
<div class="wrap rdr2-admin">
	<div class="admin-header">
		<h1>Merchant Groups</h1>
	</div>
	<?php if (isset($message) && !empty($message)) : ?>
	<div id="message" class="updated fade">
		<p><?php echo $message; ?></p>
	</div>
	<?php endif; ?>
	
	<?php if (isset($merchant_groups)) echo $merchant_groups; ?>
	

</div>
