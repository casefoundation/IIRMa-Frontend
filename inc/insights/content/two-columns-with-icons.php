<div class="featured-block objective timing">

	<?php

	// check if the repeater field has rows of data
	if ( have_rows( 'columns_content' ) ) :
		?>
		<ul class="objectives">
		<?php

		while ( have_rows( 'columns_content' ) ) :
			the_row();

			?>
			<li>
				<span class="obj-icons">
					<?php
						$svg_file  = get_template_directory_uri() . '/images/';
						$svg_file .= get_sub_field( 'icon' ) . '.svg';
					?>
					<img alt="icons" src="<?php echo esc_url( $svg_file ); ?>">
				</span>
				<?php the_sub_field( 'text' ); ?>
			</li>
			<?php
		endwhile;
		?>
		</ul>
		<?php

	else :

		// no rows found

	endif;

	?>
</div>
