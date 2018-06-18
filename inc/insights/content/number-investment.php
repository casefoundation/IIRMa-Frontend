<canvas id="investments" height="500"></canvas>
<script>
	<?php
		$api      = new NetworkMapApi();
		$response = $api->investors( [ 'top', '20' ] );

		$labels = [];
		$data   = [];

	foreach ( $response as $id => $item ) {
		$labels[] = $item['name'];
		$data[]   = $item['total_funds'];
	}
	?>
	var f = addRenderFunction(function(){
		jQuery('#investments').after(jQuery('<canvas id="investments_t" height="500"></canvas>'));
		jQuery('#investments').remove();
		jQuery('#investments_t').attr('id', 'investments');

var ctx = document.getElementById("investments");
Chart.defaults.global.defaultFontFamily ="Gotham-Book";
var myChart = new Chart(ctx, {
	type: 'horizontalBar',
	data: {
		labels: <?php echo wp_json_encode( $labels ); ?>,
		datasets: [{
			label: '# of investments',
			data: <?php echo wp_json_encode( $data ); ?>,
			backgroundColor: '#54ac91',
		}]
	},
	options: {
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
			onComplete: function () {
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
					});
				});
			}
		},
		responsive: true,
		maintainAspectRatio: false,
		scales: {
			yAxes: [{
				barThickness: 20,
				ticks: {
					beginAtZero:true
				},
				gridLines: {
					color: "rgba(0, 0, 0, 0)",
				}
			}],
			xAxes: [
				{
					gridLines: {
						color: "rgba(0, 0, 0, 0)",
					},
					position: "top",
				}
			]
		}
	}
});

	});
	f();
</script>

<?php if ( get_sub_field( 'caption' ) ) : ?>
	<?php the_sub_field( 'caption' ); ?>
<?php endif; ?>
