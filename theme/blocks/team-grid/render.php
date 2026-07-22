<?php
/**
 * Render: cular/team-grid
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Meet the Cular Creative Team!';
$members = get_field( 'members' );

// Default roster (photo path relative to /uploads, name, role).
if ( empty( $members ) ) {
	$members = array(
		array( 'photo' => '2024/08/IMG_9033-copy.png', 'name' => 'Raluca', 'role' => 'Founder' ),
		array( 'photo' => '2024/08/IMG_9119-copy-1.png', 'name' => 'Iva', 'role' => 'Account Manager' ),
		array( 'photo' => '2024/10/mirah-556.png', 'name' => 'Mira', 'role' => 'Social Media Marketer' ),
		array( 'photo' => '2024/08/IMG_9053-copy.png', 'name' => 'Chris', 'role' => 'Copywriter' ),
		array( 'photo' => '2024/10/mertha-1.png', 'name' => 'Mertha', 'role' => 'Content Creator' ),
		array( 'photo' => '2026/04/surya-final2-e1777358268495.png', 'name' => 'Surya', 'role' => 'Graphic Designer' ),
		array( 'photo' => '2024/08/IMG_9052-copy1.png', 'name' => 'Candra', 'role' => 'Digital Advertising Specialist' ),
		array( 'photo' => '2024/10/zahara-1-1.png', 'name' => 'Zahara', 'role' => 'Social Media Marketer' ),
		array( 'photo' => '2024/08/IMG_9121-copy.png', 'name' => 'Kiki', 'role' => 'Content Creator' ),
		array( 'photo' => '2025/05/daud-9.png', 'name' => 'Daud', 'role' => 'Graphic Designer' ),
		array( 'photo' => '2025/05/tasya-8.png', 'name' => 'Tasya', 'role' => 'HR & Accounting' ),
	);
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-team-grid">
	<h2 class="cular-team-grid__heading"><?php echo esc_html( $heading ); ?></h2>

	<div class="cular-team-grid__grid" data-cular-reveal-items>
		<?php foreach ( $members as $m ) : ?>
			<?php
			$photo = '';
			if ( ! empty( $m['photo'] ) ) {
				$photo = is_array( $m['photo'] )
					? ( $m['photo']['url'] ?? '' )
					: content_url( '/uploads/' . $m['photo'] );
			}
			?>
			<figure class="cular-team-grid__card">
				<div class="cular-team-grid__photo">
					<?php if ( $photo ) : ?>
						<img src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $m['name'] ); ?>" loading="lazy" />
					<?php endif; ?>
				</div>
				<figcaption>
					<span class="cular-team-grid__name"><?php echo esc_html( $m['name'] ); ?></span>
					<span class="cular-team-grid__role"><?php echo esc_html( $m['role'] ); ?></span>
				</figcaption>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
