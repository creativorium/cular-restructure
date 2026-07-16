<?php
/**
 * Render: cular/testimonials
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Testimonials';
$items   = get_field( 'items' );

// Placeholder examples (real quotes to be filled in the editor).
if ( empty( $items ) ) {
	$items = array(
		array( 'quote' => 'Working with Cular felt like adding an in-house team overnight — sharp strategy, beautiful execution.', 'author' => 'Ananda', 'role' => 'Founder' ),
		array( 'quote' => 'They just get it. Our brand finally looks and sounds like us.', 'author' => 'Yovi', 'role' => 'Marketing Lead' ),
		array( 'quote' => 'Consistent, creative and genuinely fun to work with.', 'author' => 'Shenoa', 'role' => 'Owner' ),
	);
}
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-tst" data-cular-reveal>
	<h2 class="cular-tst__heading"><?php echo esc_html( $heading ); ?></h2>
	<div class="cular-tst__grid">
		<?php foreach ( $items as $t ) : ?>
			<figure class="cular-tst__card">
				<blockquote><?php echo esc_html( $t['quote'] ); ?></blockquote>
				<figcaption>
					<span class="cular-tst__author"><?php echo esc_html( $t['author'] ); ?></span>
					<?php if ( ! empty( $t['role'] ) ) : ?><span class="cular-tst__role"><?php echo esc_html( $t['role'] ); ?></span><?php endif; ?>
				</figcaption>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
