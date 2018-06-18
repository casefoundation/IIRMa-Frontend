<?php

// check if the repeater field has rows of data
if ( have_rows( 'insights_sections' ) ) :

	?>
	<ul>
	<?php

	// loop through the rows of data
	while ( have_rows( 'insights_sections' ) ) :
		the_row();

		if ( get_sub_field( 'insights_section_mobile_image' ) ) {
			$hero_image = get_sub_field( 'insights_section_mobile_image' );
		}
		$title = sanitize_title( get_sub_field( 'insights_section_title' ) );
		$attrs = isset( $hero_image ) ?
			( 'style="background-image:url(' . $hero_image['url'] . ');"' ) : '';
		?>
		<!--single block-->
		<li class="childpage-outercon" id="<?php echo esc_attr( $title ); ?>">
			<div class="childpage-header" <?php echo esc_attr( $attrs ); ?>>
				<h2>
					<strong><?php the_sub_field( 'insights_section_title' ); ?></strong>
				</h2>
			</div>
			<div class="page-contentblock">
		<?php
		// check if the flexible content field has rows of data
		if ( have_rows( 'insights_section_content' ) ) :

			// loop through the rows of data
			while ( have_rows( 'insights_section_content' ) ) :
				the_row();

				if ( get_row_layout() === 'general_content' ) :

						echo get_template_part( 'inc/insights/content/general-content' );

				elseif ( get_row_layout() === 'separation' ) :

						echo get_template_part( 'inc/insights/content/separation' );

				elseif ( get_row_layout() === 'three_columns' ) :

						echo get_template_part( 'inc/insights/content/three-columns' );

				elseif ( get_row_layout() === 'image_with_paragraph' ) :

						echo get_template_part( 'inc/insights/content/image-with-paragraph' );

				elseif ( get_row_layout() === 'investment_graph' ) :

						echo get_template_part( 'inc/insights/content/investment-graph' );

				elseif ( get_row_layout() === 'three_columns_with_icons' ) :

						echo get_template_part( 'inc/insights/content/three-columns-with-icon' );

				elseif ( get_row_layout() === 'two_columns_with_icons' ) :

						echo get_template_part( 'inc/insights/content/two-columns-with-icons' );

				elseif ( get_row_layout() === 'graph_block' ) :

						echo get_template_part( 'inc/insights/content/graph-block' );

				endif;

			endwhile;

		else :

			// no layouts found

		endif;

		?>
			</div>
		</li>
		<!--single block-->

		<?php

	endwhile;

	?>
	</ul>
	<?php

else :

	// no rows found

endif;
