<?php $image_url = get_template_directory_uri() . '/images/content/objectives-img.jpg'; ?>
<img alt="connections" src="<?php echo esc_url( $image_url ); ?>">
<?php if ( get_sub_field( 'caption' ) ) : ?>
	<?php the_sub_field( 'caption' ); ?>
<?php endif; ?>
