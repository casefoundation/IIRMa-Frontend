<!--Begin header section-->
<header>
	<div class="container">
		<div class="row">
			<div class="col-sm-12">
				<div class="header-container">
					<a href="javascript:void(0)" class="visible-xs mobile-nav">
						<?php $svg_file = get_template_directory_uri() . '/images/nav-icon.svg'; ?>
						<img src="<?php echo esc_url( $svg_file ); ?>" alt="img">
					</a>
					<a href="javascript:void(0)" class="nav-close">
						<?php
							$svg_file = get_template_directory_uri() . '/images/nav-closeicon.svg';
						?>
						<img src="<?php echo esc_url( $svg_file ); ?>" alt="img">
					</a>
					<!-- logo block -->
					<div class="logo-outer">
						<?php
							$image_file = get_template_directory_uri() . '/images/logo-gray.png';
						?>
						<a href="<?php echo esc_url( home_url() ); ?>" class="hidden-xs">
							<img src="<?php echo esc_url( $image_file ); ?>" alt="logo" width="164">
						</a>
						<a href="<?php echo esc_url( home_url() ); ?>" class="visible-xs">

							<img src="<?php echo esc_url( $image_file ); ?>" alt="logo">
						</a>
					</div>
					<!-- logo block ends -->
					<div class="header-right">

						<!-- menu -->
						<?php wp_nav_menu(); ?>
						<!-- menu ends-->

					</div>
				</div>
			</div>
		</div>
	</div>
</header>
<!--End header section-->
