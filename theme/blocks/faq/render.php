<?php
/**
 * Render: cular/faq
 *
 * Frequently asked questions, grouped into category cards (General / Activate /
 * Elevate). Each card carries an eyebrow, a title and one or more sub-groups of
 * native <details> accordions that alternate sides, as on the live page.
 *
 * Content defaults live in data.php; an ACF repeater can override it per block.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

require_once __DIR__ . '/data.php';

$heading = get_field( 'heading' ) ?: 'Frequently Asked Questions';
$intro   = get_field( 'intro' );
if ( ! $intro ) {
	$intro = "Whether you're new to digital marketing or looking to brush up on your knowledge, we're here to help. If you can't find the answer to your question here, don't hesitate to reach out.";
}

$eyebrow = get_field( 'eyebrow' ) ?: 'Cular';
$groups  = get_field( 'groups' );
if ( empty( $groups ) ) {
	$groups = cular_faq_default_groups();
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-faq">
	<header class="cular-faq__head cular-gradient-mesh cular-gradient-mesh--about">
		<div class="cular-faq__head-inner">
			<h1 class="cular-faq__heading"><?php echo esc_html( $heading ); ?></h1>
			<p class="cular-faq__intro"><?php echo esc_html( $intro ); ?></p>
		</div>
	</header>

	<div class="cular-faq__cards">
	<?php foreach ( $groups as $group ) : ?>
		<?php
		$subgroups = $group['subgroups'] ?? array();
		if ( ! $subgroups ) {
			continue;
		}
		?>
		<article class="cular-faq__card" data-cular-reveal>
			<?php if ( $eyebrow ) : ?>
				<p class="cular-faq__eyebrow"><?php echo esc_html( $eyebrow ); ?></p>
			<?php endif; ?>
			<h2 class="cular-faq__title"><?php echo esc_html( $group['title'] ?? '' ); ?></h2>

			<?php foreach ( $subgroups as $sub ) : ?>
				<?php
				$align = ( isset( $sub['align'] ) && 'right' === $sub['align'] ) ? 'right' : 'left';
				$items = $sub['items'] ?? array();
				if ( ! $items ) {
					continue;
				}
				?>
				<div class="cular-faq__group cular-faq__group--<?php echo esc_attr( $align ); ?>">
					<?php if ( ! empty( $sub['title'] ) ) : ?>
						<h3 class="cular-faq__group-title"><?php echo esc_html( $sub['title'] ); ?></h3>
					<?php endif; ?>

					<div class="cular-faq__items">
						<?php foreach ( $items as $item ) : ?>
							<details class="cular-faq__item">
								<summary class="cular-faq__q"><?php echo esc_html( $item['q'] ?? '' ); ?></summary>
								<div class="cular-faq__a"><?php echo wp_kses_post( wpautop( $item['a'] ?? '' ) ); ?></div>
							</details>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</article>
	<?php endforeach; ?>
	</div>
</section>
