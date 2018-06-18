<?php

// check if the flexible content field has rows of data
if ( have_rows( 'sections' ) ) :

	// loop through the rows of data
	while ( have_rows( 'sections' ) ) :
		the_row();

		if ( 'content_with_title' === get_row_layout() ) :

			echo get_template_part( 'inc/content/content-with-title' );

		elseif ( 'two_columns' === get_row_layout() ) :

			echo get_template_part( 'inc/content/two-columns' );

		elseif ( 'accordion' === get_row_layout() ) :

			echo get_template_part( 'inc/content/accordion' );

		elseif ( 'division' === get_row_layout() ) :

				echo get_template_part( 'inc/content/division' );

		endif;

	endwhile;

else :

	// no layouts found

endif;


