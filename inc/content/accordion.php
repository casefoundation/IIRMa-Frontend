	<!--Begin Accordian-->
	<div class="accordian-content" id="accordion">
		<?php
		// check if the repeater field has rows of data
		if ( have_rows( 'terms_repeater' ) ) :
			$count = 0;
			// loop through the rows of data
			while ( have_rows( 'terms_repeater' ) ) :
				the_row();
				$class = '';
				if ( 0 === $count ) {
					$class = 'active-tab current-tab'; }
			?>
				<div class="panel <?php echo esc_attr( $class ); ?>"
					id="panel-<?php echo intval( $count ); ?>">
					<div class="panel-heading">
						<div class="trigger" href="#
						<?php
						echo 'collapse-' . intval( $count );
						?>
						">
							<h4><?php the_sub_field( 'title' ); ?></h4>
						</div>
					</div>
					<?php
					$attr = '';
					if ( 0 === $count ) {
						$attr = 'style="display:block;"';}
					?>
					<div class="panel-collapse"
						id="<?php echo esc_attr( 'collapse-' . $count ); ?>"
						<?php echo esc_attr( $attr ); ?>>
						<?php the_sub_field( 'description' ); ?>
					</div>
				</div>
				<?php
				$count++;
			endwhile;
		else :
			// no rows found
			echo 'There are no Terms yet.';
		endif;
		?>
	</div>
	<!--End Accordian-->
