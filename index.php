<?php
get_header(); ?>
<!--
<div class="betabadge-block">
	<?php $image_url = get_template_directory_uri() . '/images/beta-baldge.png'; ?>
	<img src="<?php echo esc_url( $image_url ); ?>" alt="beta">
</div>
-->
<div class="main-outercon">

	<?php echo get_template_part( 'inc/inner', 'header' ); ?>


	<!--Begin Banner section-->
	<section class="main-contentarea full-leftblock about-page">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<!-- left sidebar -->
					<div class="left-sidebar-outer hidden-xs">
						<div class="left-sidebar-content">
							<?php
								echo get_template_part( 'inc/content/flexible-content-nav' );
							?>
						</div>
					</div>
					<!-- left sidebar ends-->

					<!-- middle block -->
					<div class="middle-content-blk">

						<?php
						if ( have_posts() ) :

							while ( have_posts() ) :
								the_post();
									?>
									<h1><?php the_title(); ?></h1>

									<?php
										the_content();

										echo get_template_part( 'inc/content/flexible-content' );
								endwhile;

						else :

								?>
									<h1><?php esc_html_e( 'Nothing Found', 'network-map' ); ?></h1>

									<p>
									<?php
									esc_html_e(
										'Apologies, but no results were found for 
										the requested archive. Perhaps searching will help 
										find a related post.', 'network-map'
									);
									?>
									</p>
								<?php

						endif;
						?>






					</div>
				</div>
			</div>
		</div>
	</section>
	<!--End Banner section-->

</div>

<?php
get_footer();
