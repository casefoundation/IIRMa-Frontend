<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at ligula sodales,
	feugiat nisl in, scelerisque sapien. Nam sed turpis mauris. Morbi sapien orci,
	tempor et consequat ut, dapibus vitae massa. Nulla vitae sollicitudin nisl.
	Praesent efficitur velit tincidunt metus consequat malesuada. Pellentesque congue
	feugiat dolor ac iaculis. Sed posuere, neque non convallis molestie, ipsum diam
	accumsan risus, sit amet tristique est.</p>
<div class="featured-block objective">
   <?php the_sub_field( 'intro_text' ); ?>

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
				<h4>
					<?php the_sub_field( 'text' ); ?>
				</h4>
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
	<?php the_sub_field( 'paragraph_content' ); ?>
</div>
