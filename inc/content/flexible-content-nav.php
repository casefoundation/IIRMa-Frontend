<?php

// check if the flexible content field has rows of data
if ( have_rows( 'sections' ) ) :

	?>
	<ul>

	<?php
	// loop through the rows of data
	while ( have_rows( 'sections' ) ) :
		the_row();

		if ( 'content_with_title' === get_row_layout() ) :

			?>
				<li>
					<?php $title = sanitize_title( get_sub_field( 'ct_title' ) ); ?>
					<a href="#<?php echo esc_attr( $title ); ?>">
						<?php the_sub_field( 'ct_title' ); ?>
					</a>
				</li>
			<?php

		elseif ( 'two_columns' === get_row_layout() ) :

			?>
				<li>
					<?php $title = sanitize_title( get_sub_field( 'tc_title' ) ); ?>
					<a href="#<?php echo esc_attr( $title ); ?>">
						<?php the_sub_field( 'tc_title' ); ?>
					</a>
				</li>
			<?php

		endif;

	endwhile;

	?>
	</ul>
	<?php

else :

	// no layouts found

endif;

?>
