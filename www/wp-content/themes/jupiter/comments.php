<?php
function theme_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment; ?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div class="mk-single-comment" id="comment-<?php comment_ID(); ?>" <?php echo get_schema_markup( 'comment' ); ?>>
			<div class="gravatar"><?php echo get_avatar( $comment, $size = '45', $default = '' ); ?></div>
			<div class="comment-meta">
					<?php printf( '<span class="comment-author" ' . get_schema_markup( 'comment_author_link' ) . '>%s</span>', get_comment_author_link() ); ?>

					<?php edit_comment_link( '', '', '' ); ?>
					<time class="comment-time" <?php echo get_schema_markup( 'comment_time' ); ?>><?php echo get_comment_date(); ?></time>
			</div>
			<span class="comment-reply">
					<?php
					comment_reply_link(
						array_merge(
							$args, array(
								'depth' => $depth,
								'max_depth' => $args['max_depth'],
							)
						)
					);
?>
			</span>
			<div class="clearboth"></div>
			<div class="comment-content" <?php echo  get_schema_markup( 'comment_text' ); ?>>
					<?php comment_text(); ?>

<?php if ( '0' == $comment->comment_approved ) : ?>
					<span class="unapproved"><?php esc_html_e( 'Your comment is awaiting moderation.', 'mk_framework' ); ?></span>
<?php endif; ?>
				<div class="clearboth"></div>
			</div>


			   </div>
<?php
}

function list_pings( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
?>

<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>" class="comment-wrap comments-pings">

			<div class="comment-content">

				<div class="comment-meta">

					<?php printf( '<span class="comment_author"><b>%s</b></span>', get_comment_author_link() ); ?>

				</div>
				<div class="comment-data">
					<?php comment_text(); ?>

								<time class="comment-time"><?php echo get_comment_time( 'F jS, Y h:i A' ); ?></time>
<?php if ( '0' == $comment->comment_approved ) : ?>
					<span class="unapproved">Your comment is awaiting moderation.</span>
<?php endif; ?>
				</div>
				<div class="clearboth"></div>
	</div>

<?php } ?>

<section id="comments">
<?php if ( post_password_required() ) : ?>
	<p class="nopassword"><?php esc_html_e( 'This post is password protected. Enter the password to view any comments.', 'mk_framework' ); ?></p>
</section><!-- #comments -->
<?php
return;
endif;

if ( have_comments() ) :
?>
	<div class="blog-comment-title">
	<?php
	printf(
		_n( 'Comments ', 'Showing %1$s comments', get_comments_number(), 'mk_framework' ),
		number_format_i18n( get_comments_number() )
	);
	?>
	</div>
	<ul class="mk-commentlist">
		<?php
			wp_list_comments( 'callback=theme_comments&type=comment' );
		?>
	</ul>

<?php
if ( have_comments() ) :
?>
<?php if ( ! empty( $comments_by_type['pings'] ) ) : ?>
<div class="blog-comment-title"><?php esc_html_e( 'pingbacks / trackbacks', 'mk_framework' ); ?></div>

<ul class="mk-commentlist">
<?php wp_list_comments( 'callback=list_pings&type=pings' ); ?>
</ul>
<?php
endif;
endif;
?>

<?php
else :
	if ( ! comments_open() ) :
		endif;
	endif;
?>

	<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
	<nav class="comments-navigation">
		<div class="comments-previous"><?php previous_comments_link(); ?></div>
		<div class="comments-next"><?php next_comments_link(); ?></div>
	</nav>
<?php endif; ?>



	<?php
	$tab_index = mt_rand( 99,999 );
	$textarea_tab_index = $tab_index;
	$commenter = wp_get_current_commenter();
	$user = wp_get_current_user();
	$user_identity = $user->exists() ? $user->display_name : '';
	$req      = get_option( 'require_name_email' );
	$html_req = ( $req ? " required='required'" : '' );
	$consent  = empty( $commenter['comment_author_email'] ) ? '' : ' checked="checked"';
	$fields   = array(
		'author'  => '<div class="comment-form-name comment-form-row"><input type="text" name="author" value="' . esc_attr( $commenter['comment_author'] ) . '" class="text_input" id="author" tabindex="' . esc_attr( $tab_index++ ) . '" placeholder="' . esc_html__( 'Name *', 'mk_framework' ) . '"' . $html_req . ' maxlength="245" /></div>',

		'email'   => '<div class="comment-form-email comment-form-row"><input type="email" name="email" class="text_input" id="email" tabindex="' . esc_attr( $tab_index++ ) . '" placeholder="' . esc_html__( 'Email *', 'mk_framework' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) . '" aria-describedby="email-notes"' . $html_req . ' maxlength="100" /></div>',

		'url'     => '<div class="comment-form-website comment-form-row"><input type="url" name="url" class="text_input" id="url" tabindex="' . esc_attr( $tab_index++ ) . '" placeholder="' . esc_html__( 'Website', 'mk_framework' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) . '" maxlength="200" /></div>',

		'cookies' => '<div class="comment-form-cookies-consent"><input id="wp-comment-cookies-consent" tabindex="' . esc_attr( $tab_index++ ) . '" name="wp-comment-cookies-consent" type="checkbox" value="yes"' . $consent . ' />' .
					 '<label for="wp-comment-cookies-consent">' . __( 'Save my name, email, and website in this browser for the next time I comment.', 'mk_framework' ) . '</label></div>',
	);

		$comments_args = array(
			'fields' => $fields,
			'title_reply' => '<div class="respond-heading">' . esc_html__( 'Leave a Comment', 'mk_framework' ) . '</div>',
			'comment_field' => '<div class="comment-textarea"><textarea placeholder="' . esc_html__( 'LEAVE YOUR COMMENT', 'mk_framework' ) . '" class="textarea" name="comment" rows="8" id="comment" tabindex="' . esc_attr( $textarea_tab_index ) . '" required="required"></textarea></div>',
			'comment_notes_before' => '',
			'comment_notes_after' => '',
			'label_submit' => esc_html__( 'POST COMMENT','mk_framework' ),
		);
		comment_form( $comments_args );
	?>



</section>
