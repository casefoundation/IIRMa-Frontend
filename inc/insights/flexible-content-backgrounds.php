<?php

// check if the repeater field has rows of data
if ( have_rows( 'insights_sections' ) ) :

	?>
	<?php
	$count = 0;
	// loop through the rows of data
	while ( have_rows( 'insights_sections' ) ) :
		the_row();

		?>
			<?php
			if ( get_sub_field( 'insights_section_hero_image' ) ) {
				$hero_image = get_sub_field( 'insights_section_hero_image' );
			}

			$class  = 0 === $count ? 'active-background' : '';
			$class .= ' ' . sanitize_title( get_sub_field( 'insights_section_title' ) );
			$attr   = isset( $hero_image ) ?
				( 'style="background-image:url(' . $hero_image['url'] . ');"' ) :
				'';
			?>

			<div class="full-image <?php echo esc_attr( $class ); ?>"
				<?php echo esc_attr( $attr ); ?>></div>
		<?php
		$count++;
	endwhile;

else :

	// no rows found

endif;

	?>
