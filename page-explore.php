<?php

get_header(); ?>
    <!--
	<div class="betabadge-block">
		<?php $image_url = get_template_directory_uri() . '/images/beta-baldge.png'; ?>
		<img src="<?php echo esc_url( $image_url ); ?>" alt="beta">
	</div>
	-->
	<div class="main-outercon explore-wrapper">

		<?php echo get_template_part( 'inc/inner', 'header' ); ?>


		<!--Begin section-->
		<script type="text/javascript">
			var scope = 
			<?php
			echo wp_json_encode(
				[
					'data'        => get_filters_data(),
					'currentType' => 'company',
					'callbacks'   => new stdClass(),
					'cache'       => new stdClass(),
					'image_dir'   => get_template_directory_uri() . '/images/',
				]
			) ?: '{}';
			?>
			;
		</script>
		<section class="main-contentarea full-leftblock explore-page" id="explore-the-network">

		</section>


	</div>

<div class="tour-wrapper">

	<div class="tour-step" id="step-1" data-step="1">
		<div class="interest-portion">
			<div class="search-like">
				<div class="typed"></div>
			</div>
		</div>
		<span class="step-number">
			1
		</span>
		<div class="step-tooltip">
			<div class="step-content">
				<h4>Search</h4>
				<p>
					Looking for a specific investor or company? Type in the name here to find it
				</p>
			</div>
			<div class="step-buttons">
				<a href="#" class="step-nav end">End Tour</a>

				<a href="#" class="step-nav next">Next ></a>
				<a href="#" class="step-nav prev disabled">< Prev</a>
			</div>
		</div>
	</div>
	<div class="tour-step" id="step-2" data-step="2">
		<div class="interest-portion">
			<div class="sidebar-block title-block"><h3>Filter by</h3></div>
			<div class="sidebar-block">
				<h3>Type</h3>
				<div class="type-filters">
					<ul>
						<li>
							<a href="#" class="active-type" onclick="return false;">
								<span class="type-icon icon-company"></span>
								<span class="type-label">company</span>
							</a>
						</li>
						<li>
							<a href="#" id="filter-type-investor" onclick="return false;">
								<span class="type-icon icon-investor"></span>
								<span class="type-label">investor</span>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="sidebar-block selectable-module">
				<h3 onclick="return false;">industries</h3>
				<a href="#" class="collapse-block" onclick="return false;"></a>
			</div>
			<div class="sidebar-block selectable-module">
				<h3 onclick="return false;">legal structures</h3>
				<a href="#" class="collapse-block" onclick="return false;"></a>
			</div>
		</div>

		<span class="step-number">
			2
		</span>
		<div class="step-tooltip">
			<div class="step-content">
				<h4>Filter</h4>
				<p>
					Pick and choose through the filtering options to refine search results
				</p>
			</div>
			<div class="step-buttons">
				<a href="#" class="step-nav end">End Tour</a>

				<a href="#" class="step-nav next">Next ></a>
				<a href="#" class="step-nav prev">< Prev</a>
			</div>
		</div>
	</div>
	<div class="tour-step" id="step-3" data-step="3">
		<div class="interest-portion">
			<div class="map-screenshot">
				<?php $image_url = get_template_directory_uri() . '/images/content/tour_map.png'; ?>
				<img src="<?php echo esc_url( $image_url ); ?>"/>
			</div>
		</div>

		<span class="step-number">
			3
		</span>
		<div class="step-tooltip right">
			<div class="step-content">
				<h4>Map</h4>
				<p>
					The map displays results according to the filters you’ve selected,
					along with the primary connections
				</p>
				<p>At any point, click on a hub for details on that company or investor</p>
			</div>
			<div class="step-buttons">
				<a href="#" class="step-nav end">End Tour</a>

				<a href="#" class="step-nav next">Next ></a>
				<a href="#" class="step-nav prev">< Prev</a>
			</div>
		</div>
	</div>
	<div class="tour-step" id="step-4" data-step="4">
		<div class="interest-portion">
			<div class="list-toggle-container header-controls">
				<button>View list<span class="icon-list-view"></span></button>
				<span>
					Results: This data shows 25 investors,  100 funds investing in 100 companies.
				</span>
			</div>
		</div>
		<span class="step-number">
			4
		</span>
		<div class="step-tooltip">
			<div class="step-content">
				<h4>View list</h4>
				<p>
					Click on the “View list” button to see filtered results in a sortable list
				</p>
			</div>
			<div class="step-buttons">
				<a href="#" class="step-nav end">End Tour</a>

				<a href="#" class="step-nav next">Next ></a>
				<a href="#" class="step-nav prev">< Prev</a>
			</div>
		</div>
	</div>
	<div class="tour-step" id="step-5" data-step="5">
		<div class="interest-portion">
			<span>
				Displaying results 3,000
			</span>
		</div>
		<span class="step-number">
			5
		</span>
		<div class="step-tooltip top">
			<div class="step-content">
				<h4>Results</h4>
				<p>
					The Results bar will summarize the quantity of data in the current view
				</p>
			</div>
			<div class="step-buttons">
				<a href="#" class="step-nav next">End Tour</a>
				<a href="#" class="step-nav prev">< Prev</a>
			</div>
		</div>
	</div>

</div>

<?php if ( ! empty( get_query_var( 'node_type' ) ) && ! empty( get_query_var( 'node_id' ) ) ) : ?>
	<script>
		var node_type = "<?php echo esc_attr( get_query_var( 'node_type' ) ); ?>";
		var node_id = <?php echo absint( get_query_var( 'node_id' ) ); ?>;
	</script>
<?php endif; ?>







<?php
get_footer();
