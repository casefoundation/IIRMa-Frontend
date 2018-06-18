<?php

// check if the repeater field has rows of data
if ( have_rows( 'insights_sections' ) ) :

	?>
	<ul class="nav navbar-nav">
	<?php
	$count = 0;
	// loop through the rows of data
	while ( have_rows( 'insights_sections' ) ) :
		the_row();

		$title = sanitize_title( get_sub_field( 'insights_section_title' ) );
		?>
			<li <?php echo ( 0 === $count ? 'class="menu-active-item"' : '' ); ?> >
				<a href="#<?php echo esc_attr( $title ); ?>">
					<?php the_sub_field( 'insights_section_title' ); ?>
				</a>
			</li>
		<?php
		$count++;
	endwhile;

else :

	// no rows found

endif;

	?>
	</ul>
