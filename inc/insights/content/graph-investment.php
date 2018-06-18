
<canvas id="investment-number" width="400" height="400" style="margin: 30px 0;"></canvas>
<?php if ( get_sub_field( 'caption' ) ) : ?>
	<?php the_sub_field( 'caption' ); ?>
<?php endif; ?>
<?php if ( get_sub_field( 'title-2' ) ) : ?>
	<h3 class="secondblk title-2">
		<?php the_sub_field( 'title-2' ); ?>
	</h3>
<?php endif; ?>
<canvas id="investment-amount" width="400" height="400" style="margin: 30px 0;"></canvas>
<?php if ( get_sub_field( 'caption-2' ) ) : ?>
	<?php the_sub_field( 'caption-2' ); ?>
<?php endif; ?>
<script>
	<?php
		$api      = new NetworkMapApi();
		$response = $api->global( [ 'total_funds' ] );

		$investments_count = [
			'labels'  => [],
			'data'    => [],
			'percent' => [],
		];

		foreach ( $response['funds_types'] as $id => $item ) {
			$type    = $item['type'];
			$percent = ( $item['count'] / $response['total_funds'] ) * 100;
			if ( 'unknown' !== $type &&
				'venture round' !== $type &&
				'series d' !== $type &&
				'angel' !== $type &&
				'convertible note' !== $type &&
				'series f' !== $type &&
				'grant' !== $type
			) {
				$investments_count['labels'][]  = ucwords( $type );
				$investments_count['data'][]    = $item['count'];
				$investments_count['percent'][] = (int) ( $percent );
			}
		}


		$investments_amount = [
			'labels'  => [],
			'data'    => [],
			'percent' => [],
		];

		foreach ( $response['funds_types_by_amount'] as $id => $item ) {
			$type    = $item['type'];
			$percent = ( $item['count'] / $response['total_funds_amount'] ) * 100;
			if ( 'unknown' !== $type &&
				'venture round' !== $type &&
				'series d' !== $type &&
				'angel' !== $type &&
				'convertible note' !== $type &&
				'series f' !== $type &&
				'grant' !== $type
			) {
				$investments_amount['labels'][]  = ucwords( $item['type'] );
				$investments_amount['data'][]    = $item['count'];
				$investments_amount['percent'][] = (int) ( $percent );
			}
		}

	?>

		Chart.defaults.global.defaultFontFamily = "Gotham-Book";

		var ctx = document.getElementById("investment-number");
		ctx.height = 200;
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: <?php echo wp_json_encode( $investments_count['labels'] ); ?>,
				datasets: [
					{
						type: 'bar',
						label: 'Number of Investments by Investment Round',
						yAxisID: 'count',
						data: <?php echo wp_json_encode( $investments_count['data'] ); ?>,
						backgroundColor: '#69b39c',
					}
				]
			},
			options: {
				showLines: false,
				responsive: true,
				maintainAspectRatio: true,
				defaultFontFamily: "Gotham-Book",
				label: {
					font: {
						family: "Gotham-Book"
					}
				},
				legend: {
					display: false
				},
				scales: {
					yAxes: [
						{
							id: 'count',
							position: 'left',
							barThickness: 20,
							ticks: {
								beginAtZero: true,
								callback: function (label, index, labels) {
									return Math.floor(label / 100) * 100;
								}
							},

							scaleLabel: {
								display: true,
								labelString: 'Number of investments'
							},
							gridLines: {
								color: "rgba(0, 0, 0, 0)",
							}
						}
					],
					xAxes: [{
						scaleLabel: {
							display: true,
							labelString: 'Investment round'
						},
						gridLines: {
							color: "rgba(0, 0, 0, 0)",
						}
					}]
				},


			}
		});
		var ctx = document.getElementById("investment-amount");
		ctx.height = 200;
		var myChart = new Chart(ctx, {
			type: 'bar',
			data: {
				labels: <?php echo wp_json_encode( $investments_amount['labels'] ); ?>,
				datasets: [
					{
						type: 'bar',
						yAxisID: 'count',
						label: 'Total Investment Amount by Investment Round',
						data: <?php echo wp_json_encode( $investments_amount['data'] ); ?>,
						backgroundColor: '#69b39c',
					}
				]
			},
			options: {
				showLines: false,
				responsive: true,
				maintainAspectRatio: true,
				defaultFontFamily: "Gotham-Book",
				legend: {
					display: false
				},
				tooltips: {
					callbacks: {
						label: function (tooltipItem, data) {
							return "$" + abbrNFormat(tooltipItem.yLabel);
						}
					}
				},
				scales: {
					yAxes: [
						{
							id: 'count',
							position: 'left',
							barThickness: 20,
							ticks: {
								beginAtZero: true,
								callback: function (label, index, labels) {

									return "$" + abbrNFormat(label)
								}
							},
							scaleLabel: {
								display: true,
								labelString: 'Total Investment Amount'
							},
							gridLines: {
								color: "rgba(0, 0, 0, 0)",
							}
						}
					],
					xAxes: [{
						scaleLabel: {
							display: true,
							labelString: 'Investment rounds'
						},
						gridLines: {
							color: "rgba(0, 0, 0, 0)",
						}
					}]
				}
			}
		});

	function abbrNFormat(num) {
		var num = Number(num);
		if (num >= 1000000000000) {
			return (num / 1000000000000 ).toFixed(1).replace(/\.0$/, '') + 'T';
		}
		if (num >= 1000000000) {
			return (num / 1000000000).toFixed(1).replace(/\.0$/, '') + 'B';
		}
		if (num >= 1000000) {
			return (num / 1000000).toFixed(1).replace(/\.0$/, '') + 'M';
		}
		if (num >= 1000) {
			return (num / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
		}
		return num;
	}
</script>
