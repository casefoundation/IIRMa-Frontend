<?php $title = sanitize_title( get_sub_field( 'tc_title' ) ); ?>
<div class="partners-list-outer" id="<?php echo esc_attr( $title ); ?>">
<h3><?php the_sub_field( 'tc_title' ); ?></h3>
   
<?php

	// check if the repeater field has rows of data
if ( have_rows( 'tc_columns_content' ) ) :

	?>
	<!-- partners list -->
	<div class="partners-list">
	<ul>
		<?php
			// loop through the rows of data
		while ( have_rows( 'tc_columns_content' ) ) :
			the_row();

			?>
			<li>
				<div class="single-list-item">
					   
					<?php the_sub_field( 'content' ); ?>

				</div>
			</li>
				<?php

			endwhile;
		?>
		</ul>
		</div>
		<?php
	else :

		// no rows found

	endif;

?>
</div>
