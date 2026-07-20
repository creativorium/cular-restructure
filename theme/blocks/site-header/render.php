<?php
/**
 * Render: cular/site-header
 *
 * Single pill button that flips MENU <-> CLOSE and expands into a
 * full-screen menu panel. Simplified port of the old MDW side-menu effect.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$logo   = get_field( 'logo' );
$nav    = get_field( 'nav_items' );
$social = get_field( 'social' );

// Sensible defaults so the header works before anyone fills ACF.
if ( empty( $nav ) ) {
	$nav = array(
		array( 'label' => 'Home', 'url' => home_url( '/' ) ),
		array( 'label' => 'About Us', 'url' => home_url( '/about/' ) ),
		array( 'label' => 'Marketing Services', 'url' => home_url( '/activate/' ) ),
		array( 'label' => 'Consultancy', 'url' => home_url( '/elevate/' ) ),
		array( 'label' => 'Case Study', 'url' => home_url( '/case-study/' ) ),
		array( 'label' => 'Portfolio', 'url' => home_url( '/portfolio-cular/' ) ),
		array( 'label' => 'Field Notes', 'url' => home_url( '/blog/' ) ),
		array( 'label' => 'Contact', 'url' => home_url( '/contact/' ) ),
	);
}
if ( empty( $social ) ) {
	$social = array(
		array( 'network' => 'Instagram', 'url' => 'https://www.instagram.com/cularcreative/' ),
		array( 'network' => 'YouTube', 'url' => 'https://www.youtube.com/channel/UCLL8OTY2tGxaeoX1YNUN2vA' ),
		array( 'network' => 'Facebook', 'url' => 'https://www.facebook.com/cularcreative/' ),
		array( 'network' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/cular-creative/' ),
	);
}

$logo_url = ! empty( $logo['url'] ) ? $logo['url'] : CULAR_URI . '/assets/img/logo-green.png';
?>
<div class="cular-header" data-cular-header>
	<a class="cular-header__brand" href="<?php echo esc_url( home_url( '/' ) ); ?>">
		<img src="<?php echo esc_url( $logo_url ); ?>" alt="<?php echo esc_attr( ! empty( $logo['alt'] ) ? $logo['alt'] : 'Cular Creative' ); ?>" />
	</a>

	<button class="cular-header__toggle" type="button" data-cular-menu-toggle aria-expanded="false" aria-controls="cular-menu">
		<span class="cular-header__toggle-inner">
			<span class="cular-header__toggle-label" data-open>Menu</span>
			<span class="cular-header__toggle-label" data-close>Close</span>
		</span>
	</button>

	<div class="cular-menu" id="cular-menu" data-cular-menu aria-hidden="true">
		<nav class="cular-menu__nav" aria-label="Primary">
			<ul>
				<?php foreach ( $nav as $i => $item ) : ?>
					<li style="--index:<?php echo (int) $i; ?>">
						<a href="<?php echo esc_url( $item['url'] ); ?>">
							<span class="cular-menu__arrow" aria-hidden="true"></span>
							<span class="cular-menu__label"><?php echo esc_html( $item['label'] ); ?></span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</nav>

		<?php if ( $social ) : ?>
			<div class="cular-menu__social">
				<span>Follow us:</span>
				<ul>
					<?php foreach ( $social as $i => $s ) : ?>
						<li style="--index:<?php echo (int) $i; ?>">
							<a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $s['network'] ); ?></a>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>
</div>
