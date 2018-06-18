<?php
get_header(); ?>
<div class="main-outercon">
	<!--
	<div class="betabadge-block landing-page">
		<?php
		$landing_img = get_template_directory_uri() . '/images/beta-baldge.png';
		?>
		<img src="<?php echo esc_url( $landing_img ); ?>" alt="beta">
	</div>
	-->
	<div class="landing-pageouter equalhegit-outer">

		<?php
		if ( get_field( 'hero_image' ) ) {
			$hero_image = get_field( 'hero_image' );
		}
		?>
		<?php
			$hero_url          = isset( $hero_image ) ?
				( 'style="background-image:url(' . $hero_image['url'] . ');"' ) :
				'';
			$default_image_url = get_template_directory_uri() . '/images/logo-gray.png';
		?>
		<div class="banner-section equal-article" <?php echo esc_attr( $hero_url ); ?> >
			<div class="logo-block">
				<a href="<?php echo esc_url( home_url() ); ?>">
					<img src="<?php echo esc_url( $default_image_url ); ?>" alt="logo"></a>
			</div>
			<div class="land-mobicontent visible-xs">
				<h1>
					<?php the_title(); ?>
				</h1>
				<?php if ( get_field( 'sub_title' ) ) : ?>
					<h2>
						<?php the_field( 'sub_title' ); ?>
					</h2>
				<?php endif; ?>
			</div>

			<?php

				// check if the repeater field has rows of data
			if ( have_rows( 'hero_items' ) ) :
				?>
				<ul>
				<?php
					// loop through the rows of data
				while ( have_rows( 'hero_items' ) ) :
					the_row();

					?>
					<li>
						<label>
							<?php the_sub_field( 'title' ); ?>
						</label>
						<span>
							<?php echo do_shortcode( get_sub_field( 'value' ) ); ?>
						</span>
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

		<div class="landingcontent-section equal-article">

			<!--Begin header section-->
			<header class="landing-header">
				<div class="header-container">
					<a href="javascript:void(0)" class="visible-xs mobile-nav">
						<?php
							$nav_icon_svg = get_template_directory_uri() . '/images/nav-icon.svg';
							$close_svg    = get_template_directory_uri();
							$close_svg   .= '/images/nav-closeicon.svg';
						?>
						<img src="<?php echo esc_url( $nav_icon_svg ); ?>" alt="img">
					</a>
					<a href="javascript:void(0)" class="nav-close">
						<img src="<?php echo esc_url( $close_svg ); ?>" alt="img">
					</a>

					<div class="header-right">
						<div class="logo-outer front-page visible-xs">
							<a href="<?php echo esc_url( home_url() ); ?>">
								<img src="<?php echo esc_url( $default_image_url ); ?>" alt="logo">
							</a>
						</div>
						<?php wp_nav_menu(); ?>

					</div>
				</div>
			</header>
			<!--End header section-->
			<!--Begin Banner section-->

			<div class="landing-content">
				<section class="landing-contblk">
					<div class="hidden-xs">
						<h1>
							<?php the_title(); ?>
						</h1>
						<?php if ( get_field( 'sub_title' ) ) : ?>
							<h2>
								<?php the_field( 'sub_title' ); ?>
							</h2>
						<?php endif; ?>
					</div>
					<div class="land-mobilebuttons visible-xs">
						<?php

						// check if the repeater field has rows of data
						if ( have_rows( 'call_to_action' ) ) :
							?>

							<?php
								// loop through the rows of data
							while ( have_rows( 'call_to_action' ) ) :
								the_row();

								?>
								<a href="
								<?php
								$link_data = get_sub_field( 'link' );
								echo esc_url( get_permalink( $link_data[0] ) );
?>
" class="<?php the_sub_field( 'link_class' ); ?>">
									<?php the_sub_field( 'label' ); ?>
									<i>
									<?php
									switch ( get_sub_field( 'icon' ) ) {
										case 'insights':
											$img_url  = get_template_directory_uri();
											$img_url .= '/images/insights-icon.svg';
											?>
										<img src="<?php echo esc_url( $img_url ); ?>" alt="icon">

											<?php
											break;
										case 'network':
											$img_url  = get_template_directory_uri();
											$img_url .= '/images/network-Icon.svg';
											?>
										<img src="<?php echo esc_url( $img_url ); ?>" alt="icon">

											<?php
											break;
									}
									?>
									</i>
								</a>
								<?php

							endwhile;

							?>

							<?php
						else :

							// no rows found

						endif;

						?>

					</div>

					<?php the_content(); ?>

					<div class="hidden-xs">
						<?php

						// check if the repeater field has rows of data
						if ( have_rows( 'call_to_action' ) ) :
							?>

							<?php
								// loop through the rows of data
							while ( have_rows( 'call_to_action' ) ) :
								the_row();

								?>
								<a href="
								<?php
								$link_data = get_sub_field( 'link' );
								echo esc_url( get_permalink( $link_data[0] ) );
?>
" class="<?php the_sub_field( 'link_class' ); ?>">
									<?php the_sub_field( 'label' ); ?>
									<i>
									<?php
									switch ( get_sub_field( 'icon' ) ) {
										case 'insights':
											$img_url  = get_template_directory_uri();
											$img_url .= '/images/insights-icon.svg';
											?>
										<img src="<?php echo esc_url( $img_url ); ?>" alt="icon">

											<?php
											break;
										case 'network':
											$img_url  = get_template_directory_uri();
											$img_url .= '/images/network-Icon.svg';
											?>
										<img src="<?php echo esc_url( $img_url ); ?>" alt="icon">

											<?php
											break;
									}
									?>
									</i>
								</a>
								<?php

							endwhile;

							?>

							<?php
						else :

							// no rows found

						endif;

						?>

					</div>

				</section>
				<!--End Banner section-->

			</div>
		</div>
	</div>
</div>
<?php
get_footer();
