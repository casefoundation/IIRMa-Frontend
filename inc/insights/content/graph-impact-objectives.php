<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#io_investors">Investors</a></li>
  <li><a data-toggle="tab" href="#io_companies">Companies</a></li>
</ul>
<div class="tab-content">
	<div id="io_investors" class="tab-pane fade in active">
	<canvas id="io-investors" width="400" height="400"></canvas>
	</div>
	<div id="io_companies" class="tab-pane fade">
	<canvas id="io-companies" width="400" height="400"></canvas>
	</div>
</div>

<script>
	<?php
		$api      = new NetworkMapApi();
		$response = $api->global( [ 'impact_objectives' ] );

		$labels = [];
		$data   = [];

		$io_types = [];

	foreach ( $response['io_with_investors_count'] as $io ) {
		if ( ! isset( $io_types[ $io['type'] ] ) ) {
			$io_types[ $io['type'] ] = [];
		}

		$io_types[ $io['type'] ][] = $io;

		$labels[] = $io['name'];
	}
		/* SORT LABELS */

		asort( $labels );
		$labels_sort = [];
	foreach ( $labels as $i => $label ) {
		$labels_sort[] = $label;
	}
		$labels = $labels_sort;

		$datasets = [];
		$colors   = [
			'social'        => '#7bb9a6',
			'environmental' => '#7bb9a6',
		];

		foreach ( $io_types as $type => $ios ) {
			$dataset = [];
			foreach ( $labels as $label ) {
				$dataset[] = 0;
			}
			foreach ( $ios as $io ) {
				foreach ( $labels as $index => $label ) {
					if ( $label === $io['name'] ) {
						$dataset[ $index ] = $io['count'];
					}
				}
			}
			$datasets[] = [
				'label'           => ucwords( $type ),
				'data'            => $dataset,
				'backgroundColor' => $colors[ $type ],
			];

		}

	?>
	var f = addRenderFunction(function() {
		Chart.defaults.global.defaultFontFamily = "Gotham-Book";
		if(jQuery('#io_investors').css('display')!='none'){
			jQuery('#io-investors').after(
				jQuery('<canvas id="io-investors-t" height="500"></canvas>')
			);
			jQuery('#io-investors').remove();
			jQuery('#io-investors-t').attr('id', 'io-investors');
		}

		var ctx = document.getElementById("io-investors");
		char_obj(ctx, {
			labels: <?php echo wp_json_encode( $labels ); ?>,
			datasets: <?php echo wp_json_encode( $datasets ); ?>
		});
	});
	f();

<?php

		$labels = [];
		$data   = [];

		$io_types = [];

foreach ( $response['io_with_companies_count'] as $io ) {
	if ( ! isset( $io_types[ $io['type'] ] ) ) {
		$io_types[ $io['type'] ] = [];
	}

	$io_types[ $io['type'] ][] = $io;

	$labels[] = $io['name'];
}

		asort( $labels );
		$labels_sort = [];
foreach ( $labels as $i => $label ) {
	$labels_sort[] = $label;
}
		$labels = $labels_sort;

		$datasets = [];
		$colors   = [
			'social'        => '#7bb9a6',
			'environmental' => '#7bb9a6',
		];

		foreach ( $io_types as $type => $ios ) {
			$dataset = [];
			foreach ( $labels as $label ) {
				$dataset[] = 0;
			}
			foreach ( $ios as $io ) {
				foreach ( $labels as $index => $label ) {
					if ( $label === $io['name'] ) {
						$dataset[ $index ] = $io['count'];
					}
				}
			}
			$datasets[] = [
				'label'           => ucwords( $type ),
				'data'            => $dataset,
				'backgroundColor' => $colors[ $type ],
			];
		}

	?>
	var f = addRenderFunction(function() {
		if(jQuery('#io_companies').css('display')!='none') {
			jQuery('#io-companies').after(
				jQuery('<canvas id="io-companies-t" height="500"></canvas>')
			);
			jQuery('#io-companies').remove();
			jQuery('#io-companies-t').attr('id', 'io-companies');
		}

		var ctx = document.getElementById("io-companies");
		char_obj(ctx, {
			labels: <?php echo wp_json_encode( $labels ); ?>,
			datasets: <?php echo wp_json_encode( $datasets ); ?>
		});
	});
	f();

	function char_obj(ctx, data){
		var myChart = new Chart(ctx, {
			type: 'horizontalBar',
			data: {
				labels: <?php echo wp_json_encode( $labels ); ?>,
				datasets: <?php echo wp_json_encode( $datasets ); ?>
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				legend: {
					display: false
				},
				tooltips: {
					enabled: false
				},
				hover: {
					animationDuration: 0
				},
				animation: {
					duration: 1,
					onComplete: onCompleteChart
				},
				scales: {
					xAxes: [{
						barThickness: 20,
						stacked: true,
						ticks: {
							beginAtZero: true,
						},
						position: "top",
						gridLines: {
							color: "rgba(0, 0, 0, 0)",
						}
					}],
					yAxes: [
						{
							gridLines: {
								color: "rgba(0, 0, 0, 0)",
							},
						}
					]
				},
			}
		});
	}
	function onCompleteChart() {
		var chartInstance = this.chart, ctx = chartInstance.ctx;

		ctx.font = Chart.helpers.fontString(
			Chart.defaults.global.defaultFontSize,
			Chart.defaults.global.defaultFontStyle,
			Chart.defaults.global.defaultFontFamily
		);
		ctx.textAlign = 'right';
		ctx.textBaseline = 'top';

		this.data.datasets.forEach(function (dataset, i) {
			var meta = chartInstance.controller.getDatasetMeta(i);
			meta.data.forEach(function (bar, index) {
				var data = dataset.data[index];
				if (data > 0) {
					var xx = bar._model.x - 5;
					if( (bar._model.x-bar._model.base) > 25){
						ctx.textAlign = 'right';
						ctx.textBaseline = 'top';
						ctx.fillStyle = '#ffffff';
					} else {
						ctx.textAlign = 'left';
						ctx.textBaseline = 'top';
						ctx.fillStyle = '#6b879d';
						xx = bar._model.x + 5;
					}
					ctx.fillText(data, xx, bar._model.y - 5);
				}
			});
		});
	}
</script>

<?php if ( get_sub_field( 'caption' ) ) : ?>
	<?php the_sub_field( 'caption' ); ?>
<?php endif; ?>
