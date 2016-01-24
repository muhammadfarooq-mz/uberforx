/***
Equal Heights function.
***/

(function($) {
	$.fn.equalHeights = function(browserWidth, additionalHeight) {
		// Calculating width of the scrollbar for Firefox
		var scrollbar = 0;
		if (typeof document.body.style.MozBoxShadow === 'string') {
			scrollbar = window.innerWidth - jQuery('body').width();
		} 
		// Getting number of blocks for height correction.
		var blocks = jQuery(this).children().length;
		// Setting block heights to auto.
		jQuery(this).children().css('min-height', 'auto');
		// Initializing variables.
		var currentBlock = 1;
		var equalHeight = 0;
		// Finding the highest block in the selection.
		while (currentBlock <= blocks) {
			var currentHeight = jQuery(this).children(':nth-child(' + currentBlock.toString() + ')').height();
			if (equalHeight <= currentHeight) {
				equalHeight = currentHeight;
			}
			currentBlock = currentBlock + 1;
		}
		// Equalizing heights of columns.
		if (jQuery('body').width() > browserWidth - scrollbar) {
			jQuery(this).children().css('min-height', equalHeight + additionalHeight);
		} else {
			jQuery(this).children().css('min-height', 'auto');
		}
	};
})(jQuery);

/* global document */
jQuery(document).ready(function(){

	/***
     1. Main menu jQuery plugin.
	***/

	jQuery('#sf-menu').superfish();

	/***
     2. Mobile Menu jQuery plugin.
	***/

	jQuery('#sf-menu').mobileMenu({
		switchWidth: 767,
		prependTo: '.main-menu',
		combine: false
	});

	/***
     3. Adding sliders for the advanced search form. Implementing switching between default and advanced search forms.
	***/

	/* Calling slider() function and setting slider options. */
	jQuery('#slider-distance').slider({
		range: 'min',
		value: 100,
		min: 1,
		max: 300,
		slide: function( event, ui ) {
			jQuery('#distance').text( ui.value + ' km' );
		}
	});
	/* Showing the default value on the page load. */
	jQuery('#distance').text( jQuery('#slider-distance').slider('value') + ' km' );

	/* Calling slider() function and setting slider options. */
	jQuery('#slider-rating').slider({
		range: 'min',
		value: 50,
		min: 0,
		max: 100,
		slide: function( event, ui ) {
			jQuery('#rating').text( '> ' + ui.value + '%' );
		}
	});
	/* Showing the default value on the page load. */
	jQuery('#rating').text( '> ' + jQuery('#slider-rating').slider('value') + '%' );

	/* Calling slider() function and setting slider options. */
	jQuery('#slider-days-published').slider({
		range: 'min',
		value: 30,
		min: 0,
		max: 45,
		slide: function( event, ui ) {
			jQuery('#days-published').text( '< ' + ui.value );
		}
	});
	/* Showing the default value on the page load. */
	jQuery('#days-published').text( '< ' + jQuery('#slider-days-published').slider('value') );

	/***
     4. Calling selectbox() plugin to create custom stylable select lists.
	***/

	jQuery('#category-selector-default').selectbox({
		animationSpeed: "fast",
		listboxMaxSize: 400
	});
	jQuery('#category-selector-advanced').selectbox({
		animationSpeed: "fast",
		listboxMaxSize: 400
	});
	jQuery('#country-selector-advanced').selectbox({
		animationSpeed: "fast",
		listboxMaxSize: 400
	});

	/***
     5. Custom logic for switching between search default/advanced forms and hiding/showing map.
	***/

	jQuery('#advanced-search').hide();
	jQuery('#advanced-search-button').click(function(event) {
		/* Preventing default link action */
		event.preventDefault();
		if ( jQuery('#hide-map-button').hasClass('map-collapsed') ) {
			jQuery('#map').animate({ height: '620px' });
			jQuery('#hide-map-button').text('Hide Map').removeClass('map-collapsed').addClass('map-expanded');
		}
		jQuery('#default-search').slideToggle('fast');
		jQuery('#advanced-search').slideToggle('fast');
		if (jQuery(this).text() === 'Advanced Search') {
			jQuery(this).text('Simple Search');
			jQuery(this).addClass('expanded');
		} else {
			jQuery(this).text('Advanced Search');
			jQuery(this).removeClass('expanded');
		}
	});

	jQuery('#hide-map-button').click(function(event) {
		event.preventDefault();
		if ( jQuery(this).hasClass('map-expanded') ) {
			if ( jQuery('#advanced-search-button').hasClass('expanded') ) {
				jQuery('#default-search').slideToggle('fast');
				jQuery('#advanced-search').slideToggle('fast');
				jQuery('#advanced-search-button').text('Advanced Search');
				jQuery('#advanced-search-button').removeClass('expanded');
			}
			jQuery('#map').animate({ height: '107px' });
			jQuery(this).text('Show Map').removeClass('map-expanded').addClass('map-collapsed');
		} else {
			jQuery('#map').animate({ height: '620px' });
			jQuery(this).text('Hide Map').removeClass('map-collapsed').addClass('map-expanded');
		}
	});
	
	/***
     6. Logic for custom picture gallery with thumbnails, that appears on company-page.html.
	***/

	jQuery('.photo-thumbnails .thumbnail').click(function() {
		// Setting class "current" to the thumbnail that was clicked.
		jQuery('.photo-thumbnails .thumbnail').removeClass('current');
		jQuery(this).addClass('current');
		// Getting "src" attribute of the image that was clicked.
		var path = jQuery(this).find('img').attr('src');
		// Setting "src" attribute of the big image.
		jQuery('#big-photo img').attr('src', path);
	});

	/***
     7. Adding <input> placeholders (for IE 8-9).
	***/

	jQuery('.text-input-grey, .text-input-black').placeholder();

	/***
     8. Adding autocomplete.
	***/

	jQuery(function() {
		var autosuggestions = [
			"Airport",
			"Restaurant",
			"Shop",
			"Entertainment",
			"Realestate",
			"Sports",
			"Cars",
			"Education",
			"Garden",
			"Mechanic",
			"Offices",
			"Advertising",
			"Industry",
			"Postal",
			"Libraries"
		];
		jQuery('#search-what').autocomplete({
			source: autosuggestions
		});
	});

	/***
     9. Colorbox for portfolio images.
	***/

	jQuery('.portfolio-enlarge').colorbox({ maxWidth: '90%' });

	/***
	10. Boxed version switcher.
	***/

	jQuery('#boxed-switch').click(function() {
		jQuery('.section').toggleClass('boxed');
	});

	/***
	11. Login & Register form bubbles.
	***/

	jQuery('#login-link').click(function() {
		jQuery('#login-form').toggle();
		jQuery('#register-form').hide();
	});
	jQuery('#register-link').click(function() {
		jQuery('#register-form').toggle();
		jQuery('#login-form').hide();
	});


});

/* global window */
jQuery(window).load(function(){

	/***
	12. Setting equal heights for required containers and elements on page load.
	***/

	jQuery('.equalize').equalHeights(767, 0);
	jQuery('#subscription-options').equalHeights(450, 1);

	/***
	12. Adding Twitter feed to the website footer.
	***/

	jQuery("#twitter-feed").tweet({
		username: "envato",
		template: "{avatar}{text}",
		count: 2,
		avatar_size: 24,
		loading_text: "Loading tweets..."
	});

	/***
	13. Adding Flickr feed to the website footer.
	***/

	jQuery("#flickr-feed").jflickrfeed({
		limit: 12,
		qstrings: {
			id: '52617155@N08'
		},
		itemTemplate: 
		'<li>' +
			'<a href="{{image_b}}"><img src="{{image_s}}" alt="{{title}}" /></a>' +
		'</li>'
	});

	/***
	14. Filter for the portfolio items
	***/

	jQuery('#portfolio-filter input').click(function() {
		jQuery('#portfolio-filter input').removeClass('current');
		jQuery(this).addClass('current');
		var filter = jQuery(this).attr('id');
		if ( filter === 'all' ) {
			jQuery('.portfolio-listing').slideDown('fast');
			jQuery('.portfolio-listing-small').slideDown('fast');
		} else {
			jQuery('.portfolio-listing').slideUp('fast');
			jQuery('.portfolio-listing-small').slideUp('fast');
			jQuery('.portfolio-listing.' + filter).slideDown('fast');
			jQuery('.portfolio-listing-small.' + filter).slideDown('fast');
		}
	});
	
});

jQuery(window).resize(function() {

	/***
	15. Setting equal heights for required containers and elements on page resize.
	***/

	jQuery('.zone-content.equalize').equalHeights(767, 0);
	jQuery('#subscription-options').equalHeights(450, 1);

});