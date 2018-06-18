<?php wp_footer(); ?>
<!-- Modal -->
<div class="modal fade" id="tour_intro" role="dialog">
	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="title">
					<span>
						Welcome Beta Tester!
					</span>
					<h4>How To Use The Network Map </h4>
				</div>
				<div class="intro-table">
					<div class="intro-cell">
						<span class="number">1</span>
						<h4>The Network Map</h4>
						<p>
							When you enter the Network Map, you’ll see the entire dataset available.
							Use the filters to narrow your search.  Investors and
							companies—represented by blue and green points —are
							connected by transactions.

						</p>
					</div>
					<div class="intro-cell">
						<span class="number">2</span>
						<h4>Profiles</h4>
						<p>
							Click on an individual point to find a detailed profile and unique
							network for that company or investor.
						</p>
					</div>
					<div class="intro-cell">
						<span class="number">3</span>
						<h4>Have feedback? Tell us!</h4>
						<p>
							We’re excited to hear what you think! Use the Feedback buttons
							located on each page to let us know how to improve content,
							enhance the functionality and fill gaps in the data.
						</p>
					</div>
				</div>
				<div class="buttons">
					<button type="button" class="close simple-button"
							data-dismiss="modal" onclick="endTour();return false;">
						Skip the tour
					</button>
					<a class="primary-btn btn-border"  data-dismiss="modal"
						href="#" onclick="startTourSteps();return false;">
						Start the tour
					</a>
				</div>
				<div style="clear: both;"></div>
			</div>
		</div>
	</div>
</div>

</body>
</html>
