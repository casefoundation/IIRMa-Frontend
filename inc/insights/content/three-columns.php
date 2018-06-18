<div class="featured-block">
	<?php

	// check if the repeater field has rows of data
	if ( have_rows( 'tc_columns_content' ) ) :

		?>
		<ul>
		<?php

		while ( have_rows( 'tc_columns_content' ) ) :
			the_row();



			?>

			<li>
			<span>
				<?php the_sub_field( 'title' ); ?>
			</span>
			<?php the_sub_field( 'content' ); ?>
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
