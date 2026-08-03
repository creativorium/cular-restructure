<?php
/**
 * Render: cular/team-grid
 *
 * "Meet the Cular Creative Team!" — a 5-across grid of gradient cards on a
 * green band. Each card stacks role, name and a bottom-anchored cut-out photo;
 * vacancies render as "Soon!" placeholders carrying the Cular mark.
 *
 * @package Cular
 */

defined( 'ABSPATH' ) || exit;

$heading = get_field( 'heading' ) ?: 'Meet the Cular Creative Team!';
$members = get_field( 'members' );

// Default roster — order and vacancies match the live About page.
if ( empty( $members ) ) {
	$members = array(
		array( 'photo' => '2024/08/IMG_9119-copy-1.png', 'name' => 'Iva', 'role' => 'Account Manager' ),
		array( 'photo' => '2024/10/mirah-556.png', 'name' => 'Mira', 'role' => 'Social Media Marketer' ),
		array( 'photo' => '2024/08/IMG_9053-copy.png', 'name' => 'Chris', 'role' => 'Copywriter' ),
		array( 'photo' => '2024/10/mertha-1.png', 'name' => 'Mertha', 'role' => 'Content Creator' ),
		array( 'photo' => '2026/04/surya-final2-e1777358268495.png', 'name' => 'Surya', 'role' => 'Graphic Designer' ),

		array( 'photo' => '2024/08/IMG_9052-copy1.png', 'name' => 'Candra', 'role' => 'Digital Advertising Specialist' ),
		array( 'photo' => '2024/10/zahara-1-1.png', 'name' => 'Zahara', 'role' => 'Social Media Marketer' ),
		array( 'photo' => '2024/08/IMG_9033-copy.png', 'name' => 'Raluca', 'role' => 'Founder' ),
		array( 'photo' => '2024/08/IMG_9121-copy.png', 'name' => 'Kiki', 'role' => 'Content Creator' ),
		array( 'photo' => '2025/05/daud-9.png', 'name' => 'Daud', 'role' => 'Graphic Designer' ),

		array( 'name' => 'Soon!' ),
		array( 'name' => 'Soon!' ),
		array( 'photo' => '2025/05/tasya-8.png', 'name' => 'Tasya', 'role' => 'HR & Accounting' ),
		array( 'name' => 'Soon!' ),
		array( 'name' => 'Soon!' ),
	);
}

$anchor = ! empty( $block['anchor'] ) ? ' id="' . esc_attr( $block['anchor'] ) . '"' : '';
?>
<section<?php echo $anchor; // phpcs:ignore ?> class="cular-team-grid">
	<h2 class="cular-team-grid__heading"><?php echo esc_html( $heading ); ?></h2>

	<div class="cular-team-grid__grid">
		<?php
		foreach ( $members as $m ) :
			$name  = $m['name'] ?? '';
			$role  = $m['role'] ?? '';
			$photo = '';
			if ( ! empty( $m['photo'] ) ) {
				$photo = is_array( $m['photo'] )
					? ( $m['photo']['url'] ?? '' )
					: content_url( '/uploads/' . $m['photo'] );
			}
			// No photo = an open position; the card shows the Cular mark instead.
			$is_open = ! $photo;
			$alt     = $role ? $name . ', ' . $role : $name;
			?>
			<figure class="cular-team-grid__card<?php echo $is_open ? ' cular-team-grid__card--soon' : ''; ?>">
				<figcaption class="cular-team-grid__caption">
					<span class="cular-team-grid__role"><?php echo $role ? esc_html( $role ) : '&nbsp;'; ?></span>
					<span class="cular-team-grid__name"><?php echo esc_html( $name ); ?></span>
				</figcaption>

				<?php if ( $photo ) : ?>
					<img class="cular-team-grid__photo" src="<?php echo esc_url( $photo ); ?>" alt="<?php echo esc_attr( $alt ); ?>" loading="lazy" />
				<?php endif; ?>
			</figure>
		<?php endforeach; ?>
	</div>
</section>
