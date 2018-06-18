
<div class="piechart-side" >
	<h4>Investors</h4>
	<div id="number-io-investors"></div>
</div>
<div class="piechart-side">
	<h4>Companies</h4>
	<div id="number-io-companies"></div>
</div>

<script>
	
	
	  
	<?php
		$api      = new NetworkMapApi();
		$response = $api->global( [ 'impact_objectives' ] );

		$labels = [];
		$data   = [];

		$max_index             = 0;
		$companies_count_by_io = $response['companies_count_by_io'];
	foreach ( $companies_count_by_io as $index => $counts ) {
		if ( $index > $max_index ) {
			$max_index = $index;
		}
	}
		$companies_data = [ [ 'Count', 'Companies' ] ];
	for ( $i = 0;$i <= $max_index;$i++ ) {
		if ( isset( $companies_count_by_io[ $i ] ) ) {
			$index_count = $companies_count_by_io[ $i ];
		} else {
			$index_count = 0;
		}

		$labels[] = $i;
		$data[]   = $index_count;

		$companies_data[] = [ 'Companies with ' . $i . ' Impact Objectives', $index_count ];
	}


		$labels = [];
		$data   = [];

		$max_index             = 0;
		$companies_count_by_io = $response['investors_count_by_io'];
	foreach ( $companies_count_by_io as $index => $counts ) {
		if ( $index > $max_index ) {
			$max_index = $index;
		}
	}
		$investors_data = [ [ 'Count', 'Investors' ] ];
	for ( $i = 0;$i <= $max_index;$i++ ) {
		if ( isset( $companies_count_by_io[ $i ] ) ) {
			$index_count = $companies_count_by_io[ $i ];
		} else {
			$index_count = 0;
		}

		$labels[]         = $i;
		$data[]           = $index_count;
		$investors_data[] = [ 'Investors with ' . $i . ' Impact Objectives', $index_count ];
	}

		$colors = [
			'#929292',
			'#69b39c',
			'#70b9a3',
			'#79c2ac',
			'#82cbb4',
			'#80c8b2',
			'#88d1ba',
			'#90d7c2',
			'#95dcc7',
			'#9fe6d0',
			'#a5ebd5',
			'#aaf1db',
		];

	?>
		var pie_charts = {functions:{}, charts:{}};

		google.charts.setOnLoadCallback(drawNIOCharts);


		pie_charts.functions["number-io-companies"] = function(){
			var selectedItem = pie_charts.charts["number-io-companies"].getSelection()[0];
			if (selectedItem) {
				pie_charts.charts["number-io-investors"].setSelection([{row:selectedItem.row}]);
			}
		}
		pie_charts.functions["number-io-investors"] = function(){
			var selectedItem = pie_charts.charts["number-io-investors"].getSelection()[0];
			if (selectedItem) {
				pie_charts.charts["number-io-companies"].setSelection([{row:selectedItem.row}]);
			}
		}



	function drawNIOCharts() {

		var investors_data = google.visualization.arrayToDataTable(
		<?php
			echo wp_json_encode( $investors_data );
			?>
			);
		drawPieChart("number-io-investors", "Investors", investors_data, 
		<?php
			echo wp_json_encode( $colors );
			?>
			);

		var companies_data = google.visualization.arrayToDataTable(
			<?php
				echo wp_json_encode( $companies_data );
			?>
			);
		drawPieChart("number-io-companies", "Companies", companies_data,
			<?php
				echo wp_json_encode( $colors );
				?>
		);

	}


	function drawPieChart(containerElementId, title, data, colors){
		var colorsOptions = [];

		for(var i=0;i<colors.length;i++){
			colorsOptions.push({color:colors[i]});
		}
		var options = {
			title: title,
			titlePosition: 'none',
			legend: 'none',
			tooltip: { trigger: 'selection' },
			slices: colorsOptions,
			'chartArea': {'width': '100%', 'height': '90%'},
			fontName: "Gotham-Book",
		};

		var piechart = new google.visualization.PieChart(
			document.getElementById(containerElementId)
		);
		google.visualization.events.addListener(
			piechart,
			'select',
			pie_charts.functions[containerElementId]
		);
		piechart.draw(data, options);
		pie_charts.charts[containerElementId] = piechart;
	}


	jQuery(window).resize(function(){
		drawNIOCharts();
	});

</script>

<div class="pie-selector">
	<h4>Number of Impact Objectives</h4>
	<div class="selectors">
		<ul>
			<?php foreach ( $colors as $index => $color ) : ?>
			<li>
				<a href="#" data-index="<?php echo intval( $index ); ?>" target="_blank">
					<i style="background-color:<?php echo esc_attr( $color ); ?>;"></i>
					<span>
						<?php if ( 0 === $index ) : ?>
							Undisclosed
						<?php else : ?>
							<?php echo intval( $index ); ?>
						<?php endif; ?>
					</span>
				</a>
			</li>
			<?php endforeach; ?>
		</ul>
	</div>
</div>

<?php if ( get_sub_field( 'caption' ) ) : ?>
	<?php the_sub_field( 'caption' ); ?>
<?php endif; ?>
