<?php
/**
 * Render: cular/services
 *
 * Warm mesh-gradient band with large gradient service cards (matches live site).
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Our Services';
$items   = get_field( 'items' );

if ( empty( $items ) ) {
	$items = array(
		array(
			'title'       => 'Start with Strategy',
			'subtitle'    => 'Consultancy',
			'description' => 'Before we execute, we plan. Our consultancy services help you audit your current marketing, build a clear roadmap, and align on priorities — so every action has purpose.',
			'link_label'  => 'Explore Consultancy',
			'url'         => home_url( '/elevate/' ),
			'card_theme'  => 'warm',
		),
		array(
			'title'       => 'Build and Execute',
			'subtitle'    => 'Marketing Services',
			'description' => 'From social media management and content production to creative campaigns and brand design — we become your marketing team.',
			'link_label'  => 'Explore Services',
			'url'         => home_url( '/activate/' ),
			'card_theme'  => 'green',
		),
	);
}
$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-services">
	<h2 class="cular-services__heading"><?php echo esc_html( $heading ); ?></h2>

	<div class="cular-services__grid">
		<?php foreach ( $items as $item ) : ?>
			<?php $theme = ! empty( $item['card_theme'] ) ? $item['card_theme'] : 'warm'; ?>
			<article class="cular-services__card cular-services__card--<?php echo esc_attr( $theme ); ?>" data-cular-reveal>
				<h3 class="cular-services__title"><?php echo esc_html( $item['title'] ); ?></h3>

				<?php if ( ! empty( $item['subtitle'] ) ) : ?>
					<p class="cular-services__sub"><?php echo esc_html( $item['subtitle'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $item['description'] ) ) : ?>
					<p class="cular-services__desc"><?php echo esc_html( $item['description'] ); ?></p>
				<?php endif; ?>

				<?php if ( ! empty( $item['link_label'] ) && ! empty( $item['url'] ) ) : ?>
					<a class="cular-services__link" href="<?php echo esc_url( $item['url'] ); ?>">
						<?php echo esc_html( $item['link_label'] ); ?><span aria-hidden="true">&#10230;</span>
					</a>
				<?php endif; ?>
			</article>
		<?php endforeach; ?>
	</div>
</section>
