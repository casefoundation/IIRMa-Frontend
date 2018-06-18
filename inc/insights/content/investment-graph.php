<?php $image_url = get_template_directory_uri() . '/images/content/connection-img.jpg'; ?>
<div class="image-block">
	<img alt="connections" src="<?php echo esc_url( $image_url ); ?>">
	<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris at ligula sodales,
		feugiat nisl in, scelerisque sapien. Nam sed turpis mauris.
	</p>
	<div class="indications-block">
		<div class="indications-left">
			<ul>
				<li>
					<div class="round-img green"></div>
					<span>COMPANY</span>
				</li>
				<li>
					<div class="round-img blue"></div>
					<span>INVESTOR</span>
				</li>
				<li>
					<div class="round-img purple"></div>
					<span>FUND</span>
				</li>
				<li>
					<div class="round-img grey"></div>
					<span>INVESTMENT MADE</span>
				</li>
			</ul>
		</div>
		<div class="indications-right">
			<?php $image_url = get_template_directory_uri() . '/images/indicate.png'; ?>
			<img alt="round" src="<?php echo esc_url( $image_url ); ?>">
			<span>PROPORTIONAL TO INVESTMENT SIZE</span>
		</div>
	</div>
	<span>Case Foundation. Charitable Partner. Charitable Partner.
		Charitable Partner. Charitable Partner. <br>
		*All data subject to quantity and quality limitations present in Impact Alpha’s
		open data collection system.</span>
	<div class="button-block">
		<h6>INVEST IN MULTIPLE COMPANIES?</h6>
		<a class="primary-btn btn-border" href="#">Share your data</a>
	</div>
</div>
