<div id="<?php echo esc_attr( sanitize_title( get_sub_field( 'ct_title' ) ) ); ?>">
	<h3>
		<?php the_sub_field( 'ct_title' ); ?>
	</h3>

	<?php the_sub_field( 'ct_content' ); ?>
</div>
