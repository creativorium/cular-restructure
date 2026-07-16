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
		array( 'network' => 'LinkedIn', 'url' => 'https://www.linkedin.com/company/cular-creative/' ),
	);
}
$year = gmdate( 'Y' );
?>
<footer class="cular-footer">
	<div class="cular-footer__top">
		<h2 class="cular-footer__headline"><?php echo esc_html( $headline ); ?></h2>
	</div>

	<div class="cular-footer__cols">
		<?php foreach ( $columns as $col ) : ?>
			<a class="cular-footer__col" href="<?php echo esc_url( $col['url'] ); ?>">
				<h3><?php echo esc_html( $col['title'] ); ?></h3>
				<p><?php echo esc_html( $col['description'] ); ?></p>
			</a>
		<?php endforeach; ?>
	</div>

	<div class="cular-footer__news">
		<p><?php echo esc_html( $news ); ?></p>
		<?php echo do_shortcode( '[cular_newsletter]' ); // safe no-op if shortcode absent ?>
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

		<p class="cular-footer__copy">Copyright &copy; <?php echo esc_html( $year ); ?> Cular Creative</p>
	</div>
</footer>
