jQuery(function () {

	//one page

	$allClass = jQuery('.insnavouter-section').attr('class');
	if (jQuery.fn.onePageNav) {
		jQuery('.insnavouter-section ul').onePageNav({
			currentClass: 'menu-active-item',
			scrollThreshold: 0.2,
			scrollOffset: 0,
			begin: function() {

			},
			end: function() {

				$activeClass2 = jQuery('.menu-active-item a').attr('href');
				$activeClass2 = $activeClass2.replace("#", "");

				jQuery('.insnavouter-section .full-image.active-background').removeClass('active-background');
								jQuery('.insnavouter-section .full-image.'+$activeClass2).addClass('active-background');

			},
			scrollChange: function($currentListItem) {
				$activeClass = jQuery('.menu-active-item a').attr('href');
				$activeClass = $activeClass.replace("#", "");

				jQuery('.insnavouter-section .full-image.active-background').removeClass('active-background');
								jQuery('.insnavouter-section .full-image.'+$activeClass).addClass('active-background');

			}
		});

		jQuery('.left-sidebar-content ul').onePageNav({
			currentClass: 'active',
			scrollThreshold: 0.2,
			scrollOffset: 80,
		});

	}
		jQuery('#menu-primary > li.menu-item-has-children').each(function(i, item){
			var url = jQuery(item).find('a').attr('href');
			var current_path = window.location.href.split('#')[0];
			if(current_path==url){
				jQuery(item).find('.sub-menu li a').each(function(j, subItem){
					if(jQuery(subItem).attr("href").indexOf(url)!=-1){
						var subUrl = jQuery(subItem).attr("href").split(url);
						jQuery(subItem).click(function(e){
						   var anchor = jQuery(this).attr('href').split('#')[1];
						   e.preventDefault();
							jQuery('html, body').animate({
								scrollTop: jQuery( '#'+anchor ).offset().top
							}, 500);
						});
					}
				});
			}
		});

		jQuery(document).scroll(function(e){
			var st = jQuery(window).scrollTop();
			if(jQuery('.middle-content-blk h1').length){
				var offset = -90;
				if(jQuery('#wpadminbar').length){
					offset -= jQuery('#wpadminbar').height();
				}
				if(st > jQuery('.middle-content-blk h1').offset().top+jQuery('.middle-content-blk h1').height()+40+offset){
					jQuery('.left-sidebar-content').addClass("fixed");
				} else {
					jQuery('.left-sidebar-content').removeClass("fixed");
				}
			}
			if(st>0){
				jQuery('.main-outercon header').addClass('fixed-nav');
			} else {
				jQuery('.main-outercon header').removeClass('fixed-nav');
			}
			updateZoomControlByViewport();

		});



   var $table = jQuery('.flextable');
		var $fixedColumn = $table.clone().insertBefore($table).addClass('fixed-column');
		$fixedColumn.find('th:not(:first-child),td:not(:first-child)').remove();
		$fixedColumn.find('tr').each(function (i, elem) {
		jQuery(this).height($table.find('tr:eq(' + i + ')').height());
	});





  if(jQuery('.mob-trigger').is(':visible')) {
  var allPanels = jQuery('.page-contentblock').slideUp();
	jQuery("body").on('click', '.childpage-header', function() {
  //jQuery('.childpage-header').click(function() {
	  $this = jQuery(this);
	  $target =  $this.next();

	  if($this.hasClass('active-headerblk')){
		  $target.removeClass('active').slideUp();
		  jQuery('.childpage-header').removeClass('active-headerblk');
	  }else{
		  if(!$target.hasClass('active')){
			 allPanels.removeClass('active').slideUp();
			 $target.addClass('active').slideDown(function(){
				 jQuery(window).resize();
			 });
		  }
		  if(!$this.parent().hasClass('active-headerblk')){
			 jQuery('.childpage-header').removeClass('active-headerblk');
			 $this.addClass('active-headerblk');
		  }
	  }
	return false;
  });


}

	/**
	 * Vertically center Bootstrap 3 modals so they aren't always stuck at the top
	 */
	jQuery(function() {
		function reposition() {
			var modal = jQuery(this),
				dialog = modal.find('.modal-dialog');
			modal.css('display', 'block');

			// Dividing by two centers the modal exactly, but dividing by three
			// or four works better for larger screens.
			dialog.css("margin-top", Math.max(0, (jQuery(window).height() - dialog.height()) / 2));
		}
		// Reposition when a modal is shown
		jQuery('.modal').on('show.bs.modal', reposition);
		// Reposition when the window is resized
		jQuery(window).on('resize', function() {
			jQuery('.modal:visible').each(reposition);
		});
	});


	jQuery("#signup_form form").validate({
		submitHandler: function(form) {
			var data = jQuery(form).serialize()+"&action=send_mailchimp";
			jQuery.post(
				ajax_object.ajax_url,
				data,
				function(response){
					if(response){
						jQuery(".response-msg").html(response.message);
						jQuery(".response-msg").show();
						jQuery("#signup_form .title").hide();
						jQuery("#signup_form p.message").hide();
						jQuery("#signup_form form").hide();
					}else{
						alert("There where some problem to send the data, try again later.")
					}
				},
				"json"
			);
		}
	});

	//togglenav
	jQuery(window).bind("debouncedresize", function () {

		if (jQuery(window).width() > 767) {
			jQuery(".header-container").addClass("header-mobile");
		} else {
			jQuery(".header-container").removeClass("header-mobile");
		}

		// Masonry
		if (jQuery.fn.masonry) {
			if(jQuery('.partners-list').length){
				var container = document.querySelector('.partners-list');

				var msnry = new Masonry(container, {
					gutter: 46,
					transitionDuration: '0.8s',
					itemSelector: '.partners-list ul li'
				});
			}
		}


	});

	if (jQuery(window).width() > 767) {
		jQuery(".header-container").addClass("header-mobile");
	} else {
		jQuery(".header-container").removeClass("header-mobile");
	}

	//clicktoggle
	jQuery('.mobile-nav').click(function () {
		jQuery(this).parent().toggleClass('open');
		jQuery(this).parent().children(".header-right").slideToggle('slow');

	});

	//closenav
	jQuery('.nav-close').click(function () {
		jQuery(this).parent().removeClass('open');
		jQuery(this).parent().children(".header-right").slideUp('slow');

	});



	// Masonry
	if (jQuery.fn.masonry) {
		if(jQuery('.partners-list').length){
			var container = document.querySelector('.partners-list');
			var msnry = new Masonry(container, {
				gutter: 46,
				transitionDuration: '0.8s',
				itemSelector: '.partners-list ul li'
			});
		}
	}


	// equal height

	equalheight = function (container) {
		var currentTallest = 0,
			currentRowStart = 0,
			rowDivs = new Array(),
			$el,
			topPosition = 0;
		jQuery(container).each(function () {

			$el = jQuery(this);
			jQuery($el).height('auto')
			topPostion = $el.position().top;

			if (currentRowStart != topPostion) {
				for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
					rowDivs[currentDiv].height(currentTallest);
				}
				rowDivs.length = 0; // empty the array
				currentRowStart = topPostion;
				currentTallest = $el.height();
				rowDivs.push($el);
			} else {
				rowDivs.push($el);
				currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
			}
			for (currentDiv = 0; currentDiv < rowDivs.length; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
		});
	}

	jQuery(window).load(function () {
		equalheight('.equalhegit-outer .equal-article');
		if(typeof renderGraphs != 'undefined'){
			renderGraphs();
		}

		jQuery(window).resize();


		jQuery( "#zoom-control" ).slider({
			orientation: "vertical",
			range: "min",
			min: 0,
			max: 100,
			value: 58,
			slide: function( event, ui ) {
				var zoomValue = Math.floor(20+Number(100-ui.value)*3.8);
				jQuery('.zoom-control > span').text("Zoom "+zoomValue+'%');
				showGraphZoom(.02+(4-.02)*ui.value/100);
			}
		});

		jQuery(document).scroll();

		setTimeout(function(){
			jQuery(document).on('shown.bs.tab', 'a[data-toggle="tab"]', function (e) {
				jQuery(window).resize();
			})
		}, 1000);
	});


	jQuery(window).resize(function () {
		equalheight('.equalhegit-outer .equal-article');
		if(typeof renderGraphs != 'undefined'){
			renderGraphs();
		}

	});

	jQuery('.sidebar-block').each(function(i,item){
		jQuery(this).find('.collapse-block').click(function(e){
			e.preventDefault();

			if(jQuery('.sidebar-block.collapsed').length){
				jQuery('.sidebar-block.collapsed').find('.filters-content').slideUp();
				jQuery('.sidebar-block.collapsed').removeClass('collapsed');
			}

			if(jQuery(this).closest('.sidebar-block').hasClass('collapsed')){
				jQuery(this).closest('.sidebar-block').removeClass('collapsed');
				jQuery(this).siblings('.filters-content').slideUp();
			} else {
				jQuery(this).closest('.sidebar-block').addClass('collapsed');
				jQuery(this).siblings('.filters-content').slideDown();
			}
		})
	});
	jQuery('.panel-heading .trigger').click(function(e){
		var closest_panel = jQuery(this).closest('.panel');
		togglePanel(closest_panel.attr('id'));
	});





	jQuery(window).load(function(){

		jQuery(document).on("click",'.graph-area .graph-header .header-controls button', function(e){
			jQuery('.graph-area .graph-header .header-controls').fadeOut();
			jQuery('.table-view').fadeIn();

			closeNodeDetails();

			nmTrackEvent('Explore', 'List View');

		});
		jQuery(document).on("click", '.table-view .header-controls button', function(e){
			hideTableView();
		});
		jQuery('#search-module').submit(function(e){
			e.preventDefault();
		})


		jQuery('.tooltip').tooltipster({maxWidth:500, delay:0, animationDuration:0});


		/* TOUR MODAL INITIALIZATION */
		if(jQuery('.tour-wrapper').length>0){
			if (jQuery.cookie('disclosure-modal') == "true") {
				if (jQuery.cookie('tour-displayed') != "true") {
					jQuery.cookie('tour-displayed', "true", {path: '/', expires: 7});
					startTour();
				}
			}
		}

	});
	jQuery(document).on("click", '.close-module', function(e){
		closeNodeDetails();
		e.preventDefault();
	});

	jQuery('.glossary-search-form').submit(function(e){
		e.preventDefault();
	});
	jQuery.expr[":"].contains = jQuery.expr.createPseudo(function(arg) {
		return function( elem ) {
			return jQuery(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
		};
	});
	jQuery('.glossary-search').on('input, change, keydown, keyup',function(e){
		var self = jQuery(this);
		var text = self.val().trim();
		if ( text && text.length > 0 ) {
			jQuery('.accordian-content .panel').hide();
			jQuery('.accordian-content .panel:contains("' + text +'")').show();
		} else {
			jQuery('.accordian-content .panel').show();
		}
	});

	jQuery('.tour-step .end').click(function(e){
		endTour();
	});
	jQuery('.tour-step .next').click(function(e){
		tourNextStep();
	});
	jQuery('.tour-step .prev').click(function(e){
		tourPrevStep();
	});

	jQuery(".typed").typed({
		strings: ["Some Company", "Investor Name", "Another Company", "Interesting", "Some Keywords"],
		typeSpeed: 20,
		backSpeed: 20,
		loop: true,
	});

	jQuery('.dismiss-disclosure-btn').click(function(e){
		jQuery.cookie('disclosure-modal', "true", { path: '/' });
	});


	jQuery('.pie-selector a').click(function(e) {
		var index = jQuery(this).data('index');
		if (typeof pie_charts != 'undefined') {
			pie_charts.charts["number-io-companies"].setSelection([{row: index}]);
			pie_charts.charts["number-io-investors"].setSelection([{row: index}]);
		}
	   e.preventDefault();
	});




	jQuery(window).bind("popstate", function(e) {
		changeNode(e.originalEvent.state);
	});

	if(typeof node_type != 'undefined' && typeof node_id != 'undefined'){
		showNodeDescription(node_type, node_id, true, false);
	}

});
function changeNode(node){
	if(node!=null){
		var node_parts = node.split('/');
		showNodeDescription(node_parts[0], node_parts[1], true);
	} else {

		s.camera.goTo({
			x: 0,
			y: 0,
			ratio : 1.8,
		});

		s.refresh();
		closeNodeDetails();
	}

}

function togglePanel(id){
	var eventName = jQuery('h1').length ? jQuery('h1').text() : 'Generic';
	var target_panel = jQuery('#'+id);

	if(jQuery('.panel.current-tab').length>0){
		if(target_panel.attr('id')!=jQuery('.panel.active-tab').attr('id')){
			jQuery('.panel.active-tab').find('.panel-collapse').slideUp();
			jQuery('.panel.active-tab').removeClass('active-tab');
		}
	}


	if(!target_panel.hasClass('active-tab')){
		target_panel.addClass('active-tab');
		target_panel.find('.panel-collapse').slideDown();

		nmTrackEvent(eventName, target_panel.find('h4').text());

	} else {
		target_panel.removeClass('active-tab');
		target_panel.find('.panel-collapse').slideUp();

	}
}

/* *****************
 * ** TOUR FUNCTIONS
 * *****************
 */
function startTour(){
	if(!isMobile.any()){
		nmTrackEvent('Explore', 'Show Tour Modal');
		jQuery('#tour_intro').modal('show');
	}
}
function startTourSteps(){
	nmTrackEvent('Explore', 'Start Tour');
	jQuery('.active-step, .step-done').removeClass('active-step').removeClass('step-done')
	jQuery('#step-1').addClass("active-step");
	jQuery('.tour-wrapper').addClass('show-tour');
	current_tour_step = 1;
	nmTrackEvent('Explore', 'Tour Step '+current_tour_step);
}
function endTour(){
	nmTrackEvent('Explore', 'End Tour');
	jQuery.cookie('tour-finished', "true", { path: '/' });
	jQuery('.tour-wrapper').removeClass('show-tour');
}
function tourNextStep(){
	if(current_tour_step+1 <= max_tour_steps){
		current_tour_step++;
		jQuery('#step-'+(current_tour_step-1)).removeClass("active-step").addClass('step-done');
		jQuery('#step-'+current_tour_step).addClass("active-step");
		nmTrackEvent('Explore', 'Tour Step '+current_tour_step);
	} else {
		endTour();
	}
}
function tourPrevStep(){
	if(current_tour_step-1 >= 1){
		current_tour_step--;
		jQuery('#step-'+(current_tour_step+1)).removeClass("active-step");
		jQuery('#step-'+current_tour_step).addClass("active-step").removeClass('step-done');
		nmTrackEvent('Explore', 'Tour Step '+current_tour_step);

	}
}

var current_tour_step = 1;
var max_tour_steps = 5;


jQuery(document).ready(function() {

	jQuery('header a').on('click touchend', function(e) {
		if(jQuery(this).attr('target')!='_blank'){
			var el = jQuery(this);
			var link = el.attr('href');
			window.location = link;
		}
	});


	jQuery('.general-content ul li').matchHeight({
			byRow: true,
			property: 'height',
			target: null,
			remove: false
	});

	jQuery(document).scroll();


	/* ADD MODAL TO BUTTON */
	jQuery("a.open-modal, .open-modal a").click(function (e) {
		if(jQuery(this).attr('href').indexOf('#')!=-1){
			jQuery(jQuery(this).attr('href')).modal('show');
		}
	});

	jQuery.ajax({
		url: ajax_object.ajax_url+'?action=get_api_timestamp',
		dataType: 'json'
	}).done(function(data) {

		var date = new Date(data.timestamp);
		jQuery('.api-timestamp').text("Data updated "+(date.getMonth()+1)+'/'+date.getDate()+'/'+date.getFullYear());
	});

});

function updateZoomControl(zoomValue){
	jQuery( "#zoom-control" ).slider('value', Math.floor((zoomValue-20)/(380)*100));
}
function updateZoomControlByViewport(){

		if(typeof s != 'undefined'){
			if(s!=null) {
				updateZoomControl(s.camera.ratio*100);
			}
		}
}



/* **************************
   *** EASING FUNCTIONS
   **************************
*/


var EasingFunctions = {
  // no easing, no acceleration
  linear: function (t) { return t },
  // accelerating from zero velocity
  easeInQuad: function (t) { return t*t },
  // decelerating to zero velocity
  easeOutQuad: function (t) { return t*(2-t) },
  // acceleration until halfway, then deceleration
  easeInOutQuad: function (t) { return t<.5 ? 2*t*t : -1+(4-2*t)*t },
  // accelerating from zero velocity
  easeInCubic: function (t) { return t*t*t },
  // decelerating to zero velocity
  easeOutCubic: function (t) { return (--t)*t*t+1 },
  // acceleration until halfway, then deceleration
  easeInOutCubic: function (t) { return t<.5 ? 4*t*t*t : (t-1)*(2*t-2)*(2*t-2)+1 },
  // accelerating from zero velocity
  easeInQuart: function (t) { return t*t*t*t },
  // decelerating to zero velocity
  easeOutQuart: function (t) { return 1-(--t)*t*t*t },
  // acceleration until halfway, then deceleration
  easeInOutQuart: function (t) { return t<.5 ? 8*t*t*t*t : 1-8*(--t)*t*t*t },
  // accelerating from zero velocity
  easeInQuint: function (t) { return t*t*t*t*t },
  // decelerating to zero velocity
  easeOutQuint: function (t) { return 1+(--t)*t*t*t*t },
  // acceleration until halfway, then deceleration
  easeInOutQuint: function (t) { return t<.5 ? 16*t*t*t*t*t : 1+16*(--t)*t*t*t*t }
}

var isMobile = {
	Android: function() {
		return navigator.userAgent.match(/Android/i) ? true : false;
	},
	BlackBerry: function() {
		return navigator.userAgent.match(/BlackBerry/i) ? true : false;
	},
	iOS: function() {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i) ? true : false;
	},
	Windows: function() {
		return navigator.userAgent.match(/IEMobile/i) ? true : false;
	},
	any: function() {
		return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Windows());
	}
};
function nmTrackEvent(section, action, label){
	if(typeof __gaTracker != 'undefined'){
		__gaTracker('send', 'event', section, action, label);
	}


	if(typeof woopra != 'undefined'){
		var woopra_track = {
			section: section,
			action: action
		};
		if(typeof label != 'undefined'){
			woopra_track.data = label;
		}
		woopra.track("event", woopra_track);

	}
}

function nmGetSearchTrackingObject() {
  if (!window.nmSearchObj) {
	window.nmSearchObj = {
	  'search': null,
	  'filter': null
	}
  }
  return window.nmSearchObj;
}

function nmTrackSearchSend() {
	if(typeof __gaTracker != 'undefined'){
		__gaTracker('send', 'event', 'Explore', 'Search', JSON.stringify(nmGetSearchTrackingObject()));
	}

  var searchObj = nmGetSearchTrackingObject()
	if(typeof woopra != 'undefined'){
		woopra.track('search',{
			'search': JSON.stringify(searchObj.search),
			'filter': JSON.stringify(searchObj.filter)
		});
	}

}

function nmTrackSearch(searchData) {
  var searchObj = nmGetSearchTrackingObject()
  searchObj.search = searchData;
  nmTrackSearchSend();
}

function nmTrackFilter(filterData) {
  var searchObj = nmGetSearchTrackingObject()
  searchObj.filter = filterData;
  nmTrackSearchSend();
}
