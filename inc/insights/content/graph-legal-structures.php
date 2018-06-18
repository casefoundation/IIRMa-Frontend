<?php
$api = new NetworkMapApi();

$data      = [
	'labels'    => [],
	'Investors' => [],
	'Companies' => [],
	'data'      => [
		'investors' => [],
		'companies' => [],
	],
];
$max_value = 0;

/* COMPANIES */

$response = $api->companies( [ 'legal_structures' ] );

$companies = [
	'labels'     => [],
	'data'       => [],
	'percentage' => [],
];
foreach ( $response['data'] as $id => $item ) {

	$label = ucwords( $item['legal_structure'] );
	switch ( $item['legal_structure'] ) {
		case 'unknown':
			$label = 'Undisclosed';
			break;
	}

	if ( 'project' !== $label && 'llc' !== $label ) {
		$data['labels'][]                    = ucwords( $label );
		$data['data']['companies'][ $label ] = $item['count'];


		if ( $item['count'] > $max_value ) {
			$max_value = $item['count'];
		}
	}
}

/* INVESTORS */

$response = $api->investors( [ 'legal_structures' ] );

$response_investors = $response;
$investors          = [
	'labels'     => [],
	'data'       => [],
	'percentage' => [],
	'series'     => [],
];

foreach ( $response['data'] as $id => $item ) {

	$label = $item['legal_structure'];
	switch ( $label ) {
		case 'for-benefit':
			$label = 'Benefit Corporation';
			break;
		case 'other legal structure':
			$label = 'Other';
			break;
		case 'unknown':
			$label = 'Undisclosed';
			break;
	}
	$data['data']['investors'][ ucwords( $label ) ] = $item['count'];

	if ( $item['count'] > $max_value ) {
		$max_value = $item['count'];
	}
}

/* SORT LABELS */

asort( $data['labels'] );
?>




<div class="tableinvetment-mainouter">
	<div class="table-responsive">
		<table class="flextable">
			<tr>
				<td class="header-mainblock">
					<div class="tablehead">
					</div>
				</td>
				<td class="header-mainblock header-top-title">
					<div class="tablehead">
						<span>
							Investors
						</span>
					</div>
				</td>
				<td class="header-mainblock header-top-title">
					<div class="tablehead">
						<span>
							Companies
						</span>
					</div>
				</td>
			</tr>
			<?php foreach ( $data['labels'] as $label ) : ?>
			<tr>
				<td class="header-mainblock">
					<div class="tablehead">
						<span>
							<?php echo esc_html( $label ); ?>
						</span>
					</div>
				</td>

				<td class="dotes-outerblk dotblk-40">
					<?php
					$content = isset( $data['data']['investors'][ $label ] ) ?
						$data['data']['investors'][ $label ] :
						0;
					$scale   = ( 0 === $content ) ? 0 : log( $content ) / log( $max_value );
					$size    = 10 + (int) ( $scale * 50 );

					$background = 'Undisclosed' === $label ? 'background: #afafaf;' : '';
					$attr       = 'width:' . $size . 'px; height:' . $size . 'px;';
					$attr      .= $background;
					?>
					<i style="<?php echo esc_attr( $attr ); ?>">
						<div class="tooltipblk">
							<strong><?php echo number_format( $content ); ?></strong>
						</div>
					</i>
				</td>
				<?php $fl_log = log( $content ); ?>
				<td class="dotes-outerblk dotblk-40" data-log="<?php echo floatval( $fl_log ); ?>">
					<?php
					$content    = isset( $data['data']['companies'][ $label ] ) ?
						$data['data']['companies'][ $label ] :
						0;
					$scale      = ( 0 === $content ) ? 0 : log( $content ) / log( $max_value );
					$size       = 15 + (int) ( $scale * 35 );
					$background = 'Undisclosed' === $label ? 'background: #afafaf;' : '';
					$attr       = 'width:' . $size . 'px; height:' . $size . 'px;';
					$attr      .= $background;
					?>
					<i style="<?php echo esc_attr( $attr ); ?>">
						<div class="tooltipblk">
							<strong><?php echo number_format( $content ); ?></strong>
						</div>
					</i>
				</td>

			</tr>
			<?php endforeach; ?>

		</table>
	</div>
</div>


<?php if ( get_sub_field( 'caption' ) ) : ?>
	<?php the_sub_field( 'caption' ); ?>
<?php endif; ?>
