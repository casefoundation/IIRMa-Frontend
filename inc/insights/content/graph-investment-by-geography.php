<?php
		$api           = new NetworkMapApi();
		$response      = $api->global( [ 'geographic_areas' ], [ 'show_continent' => 1 ] );
?>



		<ul class="geography-list">
		<?php
			$max_count = 6;

			$max = 0;

			$regions = [];

		foreach ( $response['geographic_areas_with_investors'] as $region ) {
			if ( $region['count'] > $max ) {
				$max = $region['count'];
			}
		}
			$data = $response['geographic_areas_with_investors'];

			$count = 0;

			$prepared_data = [];
			$undisclosed   = null;

		foreach ( $data as $region ) {
			if ( count( $prepared_data ) < $max_count ) {
				if ( 'Undisclosed' !== $region['name'] ) {
					$prepared_data[] = $region;
				} else {
					$undisclosed = $region;
				}
			}
		}
		if ( isset( $undisclosed ) ) {
			$prepared_data[] = $undisclosed;
		}
			$data = $prepared_data;

		foreach ( $data as $region ) {

				$scale      = ( $region['count'] + 1 ) / $max;
				$width      = 10 + ( $scale * 60 );
				$background = 'Undisclosed' === $region['name'] ? 'background: #afafaf;' : '';
				$attr       = 'width:' . $width . 'px; height:' . $width . 'px;' . $background;
				?>
					<li>
						<div class="round-blk america">
							<i style="<?php echo esc_attr( $attr ); ?>"></i>
						</div>
						<h6>
							<?php echo esc_html( $region['name'] ); ?>
						</h6>
						<span><?php echo number_format( $region['count'] ); ?></span>
					</li>
					<?php

		}
		?>
		</ul>

<?php if ( get_sub_field( 'title-2' ) ) : ?>
	<h3 class="secondblk">
		<?php the_sub_field( 'title-2' ); ?>
	</h3>
<?php endif; ?>

		<ul class="geography-list">
		<?php


			$max = 0;

			$data = $response['geographic_areas_with_companies'];

		foreach ( $data as $region ) {
			if ( $region['count'] > $max ) {
				$max = $region['count'];
			}
		}
			$count = 0;

			$prepared_data = [];
			$undisclosed   = null;

		foreach ( $data as $region ) {
			if ( count( $prepared_data ) < $max_count ) {
				if ( 'Undisclosed' !== $region['name'] ) {
					$prepared_data[] = $region;
				} else {
					$undisclosed = $region;
				}
			}
		}
		if ( isset( $undisclosed ) ) {
			$prepared_data[] = $undisclosed;
		}
			$data = $prepared_data;

		foreach ( $data as $region ) {

				$scale      = ( $region['count'] + 1 ) / $max;
				$width      = 10 + ( $scale * 60 );
				$background = 'Undisclosed' === $region['name'] ? 'background: #afafaf;' : '';
				$attr       = 'width:' . $width . 'px; height:' . $width . 'px;' . $background;
				?>
					<li>
						<div class="round-blk america">
							<i style="<?php echo esc_attr( $attr ); ?>"></i>
						</div>
						<h6>
							<?php echo esc_html( $region['name'] ); ?>
						</h6>
						<span><?php echo number_format( $region['count'] ); ?></span>
					</li>
					<?php

		}
		?>
		</ul>

<?php if ( get_sub_field( 'caption' ) ) : ?>
	<?php the_sub_field( 'caption' ); ?>
<?php endif; ?>
