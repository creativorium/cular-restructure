<?php
/**
 * Render: cular/site-footer
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$headline = get_field( 'headline' ) ?: "Your Brand's Marketing Team";
$columns  = get_field( 'columns' );
$news     = get_field( 'newsletter_text' );
$social   = get_field( 'social' );
$logo     = get_field( 'logo' );

$logo_url = ! empty( $logo['url'] ) ? $logo['url'] : CULAR_URI . '/assets/img/logo-full.png';

if ( empty( $columns ) ) {
	$columns = array(
		array( 'title' => 'Our Services', 'description' => 'Dig into our full suite of Digital Marketing Services.', 'url' => home_url( '/activate/' ) ),
		array( 'title' => 'Our Portfolio', 'description' => "A showcase of Cular's boldest projects.", 'url' => home_url( '/portfolio-cular/' ) ),
		array( 'title' => 'Our Team', 'description' => "We're more than an agency – we're a family that knows how to balance hard work with fun!", 'url' => home_url( '/about/' ) ),
	);
}
if ( empty( $news ) ) {
	$news = 'Curious about our world? Join our newsletter for exclusive content, special offers, and a dose of inspiration.';
}
if ( empty( $social ) ) {
	$social = array(
		array( 'network' => 'Instagram', 'url' => 'https://www.instagram.com/cularcreative/' ),
		array( 'network' => 'Facebook', 'url' => 'https://www.facebook.com/cularcreative/' ),
		array( 'network' => 'Linkedin', 'url' => 'https://www.linkedin.com/company/cular-creative/' ),
	);
}
$year = gmdate( 'Y' );
?>
<footer class="cular-footer">
	<div class="cular-footer__grid">
		<div class="cular-footer__brand">
			<?php // Intrinsic size of assets/img/logo-full.png; always below the fold, so lazy. ?>
			<img class="cular-footer__logo" src="<?php echo esc_url( $logo_url ); ?>" width="420" height="102" loading="lazy" decoding="async" alt="Cular Creative" />
			<p class="cular-footer__tagline luxia"><?php echo esc_html( $headline ); ?></p>
		</div>

		<?php foreach ( $columns as $col ) : ?>
			<a class="cular-footer__col" href="<?php echo esc_url( $col['url'] ); ?>">
				<h3 class="luxia"><?php echo esc_html( $col['title'] ); ?></h3>
				<p><?php echo esc_html( $col['description'] ); ?></p>
			</a>
		<?php endforeach; ?>

		<div class="cular-footer__news">
			<p><?php echo esc_html( $news ); ?></p>
			<?php // TODO: wire action to the real newsletter provider (Mailchimp/WPForms). ?>
			<form class="cular-footer__form" method="post" action="<?php echo esc_url( home_url( '/contact/' ) ); ?>">
				<input type="email" name="email" placeholder="Email" aria-label="Email" required />
				<button type="submit" aria-label="Subscribe">
					<svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M2 21l21-9L2 3v7l15 2-15 2z"/></svg>
				</button>
			</form>
		</div>
	</div>

	<div class="cular-footer__bottom">
		<ul class="cular-footer__meta">
			<li><a href="<?php echo esc_url( home_url( '/privacy-terms/' ) ); ?>">Privacy &amp; Terms</a></li>
			<li><a href="<?php echo esc_url( home_url( '/faqs/' ) ); ?>">Frequently Asked Questions</a></li>
		</ul>

		<div class="cular-footer__social">
			<span>Follow Us:</span>
			<ul>
				<?php foreach ( $social as $s ) : ?>
					<li><a href="<?php echo esc_url( $s['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $s['network'] ); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>

	<p class="cular-footer__copy">Copyright &copy; <?php echo esc_html( $year ); ?> Cular Creative</p>
</footer>
