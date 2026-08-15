<?php
/**
 * Render: cular/form-index
 *
 * The /form/ landing page. It was a leftover container from the Elementor
 * structure — it returned 200 and rendered header, footer and nothing else, so
 * anyone who reached it saw a blank page.
 *
 * Rather than hardcode a list that goes stale the moment a form is added or
 * unpublished, this reads the actual child pages and, for each one, pulls the
 * form's name and description straight out of the intake plugin's registry
 * using the `form_type` stored in that page's block attributes. Publish a new
 * form page and it appears here; unpublish one and it disappears.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$intro      = get_field( 'intro' );
$parent     = (int) get_field( 'parent' );
$empty_text = get_field( 'empty_text' ) ?: 'No forms are published yet.';

if ( ! $parent ) {
	$parent = (int) get_the_ID();
}

$children = get_posts(
	array(
		'post_type'      => 'page',
		'post_parent'    => $parent,
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'orderby'        => 'menu_order title',
		'order'          => 'ASC',
	)
);

// The plugin's registry, when it is active — gives each form a real
// description instead of us duplicating the copy here.
$types = array();
if ( class_exists( 'Cular_Intake_Form' ) ) {
	$instance = Cular_Intake_Form::get_instance();
	if ( method_exists( $instance, 'get_form_types' ) ) {
		$types = (array) $instance->get_form_types();
	}
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore WordPress.Security.EscapeOutput ?> class="cular-fidx">

	<?php if ( $intro ) : ?>
		<p class="cular-fidx__intro" data-cular-reveal><?php echo esc_html( $intro ); ?></p>
	<?php endif; ?>

	<?php if ( ! $children ) : ?>
		<p class="cular-fidx__empty"><?php echo esc_html( $empty_text ); ?></p>
	<?php else : ?>
		<ul class="cular-fidx__list" data-cular-reveal-items>
			<?php foreach ( $children as $child ) : ?>
				<?php
				// The form each page carries is stored in the contact block's
				// attributes; inc/site-chrome.php already parses it the same way.
				$type = function_exists( 'cular_page_intake_form_type' ) ? cular_page_intake_form_type( $child ) : '';

				// `public`, never `description`. The latter is written for the
				// admin Form Types screen and carries migration notes — ticket
				// numbers, "was the 36-question Elementor form", "use Web Design
				// instead" — which read as nonsense to a visitor and expose how
				// the site is built. Falls back to the page excerpt, then blank.
				$desc = '';
				if ( $type && ! empty( $types[ $type ]['public'] ) ) {
					$desc = $types[ $type ]['public'];
				} elseif ( $child->post_excerpt ) {
					$desc = $child->post_excerpt;
				}
				?>
				<li class="cular-fidx__item">
					<a class="cular-fidx__card" href="<?php echo esc_url( get_permalink( $child ) ); ?>">
						<span class="cular-fidx__title"><?php echo esc_html( get_the_title( $child ) ); ?></span>

						<?php if ( $desc ) : ?>
							<span class="cular-fidx__desc"><?php echo esc_html( $desc ); ?></span>
						<?php endif; ?>

						<?php // Just "Open form" — echoing the form's name here only repeated the card title. ?>
						<span class="cular-fidx__go">
							Open form
							<svg viewBox="0 0 24 24" width="16" height="16" aria-hidden="true" focusable="false"><path fill="currentColor" d="M13.2 5.2 12 6.4l4.8 4.8H4v1.6h12.8L12 17.6l1.2 1.2 7-7z"/></svg>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>
</section>
