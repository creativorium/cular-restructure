<?php
/**
 * Render: cular/services
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$eyebrow = get_field( 'eyebrow' ) ?: 'Our Services';
$heading = get_field( 'heading' );
$items   = get_field( 'items' );

if ( empty( $items ) ) {
	$items = array(
		array( 'title' => 'Start with Strategy', 'description' => 'We map the plan before we make a move.', 'url' => home_url( '/elevate/' ) ),
		array( 'title' => 'Consultancy', 'description' => 'Expert guidance to sharpen your direction.', 'url' => home_url( '/elevate/' ) ),
		array( 'title' => 'Build and Execute', 'description' => 'Hands-on delivery across your channels.', 'url' => home_url( '/activate/' ) ),
		array( 'title' => 'Marketing Services', 'description' => 'A full suite of digital marketing services.', 'url' => home_url( '/activate/' ) ),
	);
}
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-services" data-cular-reveal>
	<header class="cular-services__head">
		<p class="cular-services__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
		<?php if ( $heading ) : ?><h2 class="cular-services__heading"><?php echo esc_html( $heading ); ?></h2><?php endif; ?>
	</header>

	<div class="cular-services__grid">
		<?php foreach ( $items as $item ) : ?>
			<?php $tag = ! empty( $item['url'] ) ? 'a' : 'div'; ?>
			<<?php echo $tag; ?> class="cular-services__card"<?php echo ! empty( $item['url'] ) ? ' href="' . esc_url( $item['url'] ) . '"' : ''; ?>>
				<h3><?php echo esc_html( $item['title'] ); ?></h3>
				<?php if ( ! empty( $item['description'] ) ) : ?><p><?php echo esc_html( $item['description'] ); ?></p><?php endif; ?>
			</<?php echo $tag; ?>>
		<?php endforeach; ?>
	</div>
</section>
