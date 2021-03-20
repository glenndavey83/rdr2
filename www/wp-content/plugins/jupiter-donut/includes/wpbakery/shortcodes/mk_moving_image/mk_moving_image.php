<?php
$path = pathinfo(__FILE__) ['dirname'];
include ($path . '/config.php');

$class[] = get_viewport_animation_class($animation);
$class[] = 'jupiter-donut-align-'.$align;
$class[] = 'jupiter-donut-' . $visibility;
$class[] = $el_class;
?>

<div class="mk-moving-image <?php echo implode(' ', $class); ?>">

	<?php if(!empty($link)) { ?>
		<a href="<?php echo $link; ?>">
	<?php } ?>

			<img class="mk-floating-<?php echo $axis; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>" src="<?php echo mk_get_wpml_media( $src ); ?>" />

	<?php if(!empty($link)) { ?>
		</a>
	<?php } ?>
</div>

<div class="clearboth"></div>
