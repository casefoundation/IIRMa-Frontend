<?php
	get_header();
	$d_url = get_template_directory_uri();
?>
<div class="main-outercon">
    <!--
	<div class="betabadge-block landing-page">
		<img src="<?php echo esc_url( $d_url ); ?>/images/beta-baldge.png" alt="beta">
	</div>
	-->
	<div class="landing-pageouter equalhegit-outer">
		<!--left nav content blk-->
		<div class="insnavouter-section hidden-xs">

			<?php echo get_template_part( 'inc/insights/flexible-content-backgrounds' ); ?>

			<div class="nav-cont-block">
				<div class="logo-block"> <a href="<?php echo esc_url( home_url() ); ?>">
					<img src="<?php echo esc_url( $d_url ); ?>/images/logo-gray.png" alt="logo"></a>
				</div>

				<?php echo get_template_part( 'inc/insights/flexible-content-nav' ); ?>
			</div>
		</div>
		<!--left nav content blk-->
		<div class="insitcontent-section ">

			<!--Begin header section-->
			<header class="insight-header">
				<div class="header-container">
					<a href="javascript:void(0)" class="visible-xs mobile-nav">
						<img src="<?php echo esc_url( $d_url ); ?>/images/nav-icon.svg" alt="img">
					</a>
					<a href="javascript:void(0)" class="nav-close">
					<img src="<?php echo esc_url( $d_url ); ?>/images/nav-closeicon.svg" alt="img">
					</a>
					<div class="logo-outer visible-xs">
						<a href="<?php echo esc_url( home_url() ); ?>">
						<img src="<?php echo esc_url( $d_url ); ?>/images/logo-gray.png" alt="logo">
						</a>
					</div>
					<div class="header-right">
						<?php wp_nav_menu(); ?>
					</div>
				</div>
			</header>

			<script>
				google.charts.load('current', {'packages':['treemap', 'corechart']});
				var graphs_scope = {renderFunctions:[]};
				function addRenderFunction(f){
					graphs_scope.renderFunctions.push(f);
					return f;
				}
				function renderGraphs(){
					console.log('renderGraphs');
					for(var i in graphs_scope.renderFunctions){
						graphs_scope.renderFunctions[i]();
					}
				}
			</script>

			<!--Begin Banner section-->
			<div class="landing-content">
				<section class="landing-contblk">
					<h1>
						<?php the_title(); ?>
					</h1>

					<?php the_content(); ?>

					<?php echo get_template_part( 'inc/insights/flexible-content' ); ?>
				</section>
				<!--End Banner section-->
			</div>
		</div>
	</div>
	<div class="mob-trigger"></div>
</div>
<?php
get_footer();
