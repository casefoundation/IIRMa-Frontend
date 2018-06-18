<div class="invest-tablesingleout">
	<?php if ( get_sub_field( 'show_title' ) !== 'hide' ) : ?>
		<h3 class="secondblk">
			<?php the_sub_field( 'title' ); ?>
		</h3>
	<?php endif; ?>

	<div class="image-block"> 

	<?php

	if ( get_sub_field( 'graph' ) === 'investment_geography' ) {

		echo get_template_part( 'inc/insights/content/graph-investment-by-geography' );

	} elseif ( get_sub_field( 'graph' ) === 'investment_continent' ) {

		echo get_template_part( 'inc/insights/content/investment-continent' );

	} elseif ( get_sub_field( 'graph' ) === 'investment_issue_area' ) {

		echo get_template_part( 'inc/insights/content/investment-issue-area' );

	} elseif ( get_sub_field( 'graph' ) === 'number_investment' ) {

		echo get_template_part( 'inc/insights/content/number-investment' );

	} elseif ( get_sub_field( 'graph' ) === 'legal_structures' ) {

		echo get_template_part( 'inc/insights/content/graph-legal-structures' );

	} elseif ( get_sub_field( 'graph' ) === 'investment' ) {

		echo get_template_part( 'inc/insights/content/graph-investment' );

	} elseif ( get_sub_field( 'graph' ) === 'number_impact_objectives' ) {

		echo get_template_part( 'inc/insights/content/graph-number-impact-objectives' );

	} elseif ( get_sub_field( 'graph' ) === 'impact_objectives' ) {

		echo get_template_part( 'inc/insights/content/graph-impact-objectives' );

	}
	?>

	</div>

</div>
