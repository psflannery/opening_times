/**
 * Get Viewport Dimensions
 * returns object with viewport dimensions to match css in width and height properties
 * ( source: http://andylangton.co.uk/blog/development/get-viewport-size-width-and-height-javascript )
 */
function updateViewportDimensions() {
    var w=window,d=document,e=d.documentElement,g=d.getElementsByTagName('body')[0],x=w.innerWidth||e.clientWidth||g.clientWidth,y=w.innerHeight||e.clientHeight||g.clientHeight;
    return { width:x,height:y };
}
// setting the viewport width
var viewport = updateViewportDimensions();

/**
 * Throttle Resize-triggered Events
 * Wrap your actions in this function to throttle the frequency of firing them off, for better performance, esp. on mobile.
 * ( source: http://stackoverflow.com/questions/2854407/javascript-jquery-window-resize-how-to-fire-after-the-resize-is-completed )
 */
var waitForFinalEvent = (function () {
    var timers = {};
    return function (callback, ms, uniqueId) {
        if (!uniqueId) { uniqueId = "Don't call this twice without a uniqueId"; }
        if (timers[uniqueId]) { clearTimeout (timers[uniqueId]); }
        timers[uniqueId] = setTimeout(callback, ms);
    };
})();
// how long to wait before deciding the resize has stopped, in ms. Around 50-100 should work ok.
var timeToWaitForLast = 100;

/**
 * skip-link-focus-fix.js
 */
( function() {
    var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

    if ( ( is_webkit || is_opera || is_ie ) && 'undefined' !== typeof( document.getElementById ) ) {
        var eventMethod = ( window.addEventListener ) ? 'addEventListener' : 'attachEvent';
        window[ eventMethod ]( 'hashchange', function() {
            var element = document.getElementById( location.hash.substring( 1 ) );

            if ( element ) {
                if ( ! /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) )
                element.tabIndex = -1;

                element.focus();
            }
        }, false );
    }
})();

/**
 * Mailing List
 */
function checkform() {
	for (i=0;i<fieldstocheck.length;i++){
		if (eval("document.subscribeform.elements['"+fieldstocheck[i]+"'].type") == "checkbox") {
			if (document.subscribeform.elements[fieldstocheck[i]].checked) {
			} else {
				alert("Please enter your "+fieldnames[i]);
				eval("document.subscribeform.elements['"+fieldstocheck[i]+"'].focus()");
				return false;
			}
		} else {
			if (eval("document.subscribeform.elements['"+fieldstocheck[i]+"'].value") == "") {
				alert("Please enter your "+fieldnames[i]);
				eval("document.subscribeform.elements['"+fieldstocheck[i]+"'].focus()");
				return false;
			}
		}
	}
	for (i=0;i<groupstocheck.length;i++) {
		if (!checkGroup(groupstocheck[i],groupnames[i])) {
			return false;
		}
	}
	if (! compareEmail()) {
		alert("Email addresses you entered do not match");
		return false;
	}
	return true;
}
var fieldstocheck = new Array();
var fieldnames = new Array();
function addFieldToCheck(value,name) {
	fieldstocheck[fieldstocheck.length] = value;
	fieldnames[fieldnames.length] = name;
}
var groupstocheck = new Array();
var groupnames = new Array();
function addGroupToCheck(value,name) {
	groupstocheck[groupstocheck.length] = value;
	groupnames[groupnames.length] = name;
}
function compareEmail() {
	return (document.subscribeform.elements["email"].value == document.subscribeform.elements["emailconfirm"].value);
}
function checkGroup(name,value) {
	option = -1;
	for (i=0;i<document.subscribeform.elements[name].length;i++) {
		if (document.subscribeform.elements[name][i].checked) {
			option = i;
		}
	}
	if (option == -1) {
		alert ("Please enter your "+value);
		return false;
	}
	return true;
}

( function ( $ ) {
    /* Global Variables */
    var $window = $(window),
    	$height = $window.height(),
    	$body = $('body');
	
    /**
     * Lazy Load iFrames
     */
    function lazy_load_iframe(iframe) {
        var $iframe = $(iframe),
        src = $iframe.attr('data-src');
        $iframe.hide().removeAttr('data-src').attr('data-lazy-loaded', 'true');
        iframe.src = src;
        $iframe.show();
    }
	
	/**
	 * Main Accordion
	 */
	 function main_accordion() {
		$(".js .accordion").css({display: 'block'});
		
		$('.accordion').accordion({
			active: false,
			activate: function( event, ui ){
				viewport = updateViewportDimensions();
				if ( viewport.width < 1200 ) {
					var scrollTop = $( this ).scrollTop();
					if( ui.newHeader.length > 0 ) {
						var top = $( ui.newHeader ).offset().top;
						$( 'html, body' ).animate({ scrollTop: scrollTop + top }, 500, 'easeOutQuart' );
					} else {
						var top = $(ui.oldHeader).offset().top;
						$( 'html, body' ).animate({ scrollTop: scrollTop + top }, 500, 'easeOutQuart' );
					}
				}
				if (ui.newPanel.length > 0) {
					$(ui.newPanel).find('iframe[data-src]').each(function () {
						lazy_load_iframe(this);
					});
				}
				$(ui.oldPanel).find('iframe[data-lazy-loaded]').attr('src', function (i, val) {
					return val;
				});
			},
			animate: { easing: 'easeOutQuart', duration: 500 }, 
			header: '.entry-header',
			heightStyle: 'content',
			icons: false,
			collapsible: true,
			create: function( e, ui ) {
				var $this = $( this );
				$( window ).on( "hashchange", function( e ) {
					// var headers = $this.accordion( "option", "header" ),
					// http://stackoverflow.com/questions/15501932/jquery-ui-cannot-call-methods-on-dialog-prior-to-initialization-attempted-to-c
					var	headers = $('.accordion').accordion( "option", "header" );
					header = $( location.hash ),
					index = $this.find( headers ).index( header );
					if ( index >= 0 ) {
						$this.accordion( "option", "active", index );    
					}
				});
				$( window ).trigger( "hashchange" );
			}
			
		});
	}
	
	/**
	 * Reading Accordion
	 */
	function reading_accordion() {
		$(".js .accordion-issue").css({display: 'block'});

		$('.accordion-issue').accordion({
			animate: { easing: 'easeOutQuart', duration: 500 }, 
			header: '.editor-title',
			heightStyle: 'content',
			icons: false,
			collapsible: true,
			beforeActivate: function (event, ui) {
				ui.oldPanel.find('.accordion').accordion("option", "active", false);
			}
		});
	}
		
	/**
	 * Call the Opening Times Accordions
	 */
	function opening_times_accordion() {
		if ($.fn.accordion) {
			reading_accordion();
			main_accordion();
		}
	}
	
	/**
	 * Mobile Navigation
	 */

	/* Adjust the size of the header according to the size of the viewport */
	function header_resize() {
		viewport = updateViewportDimensions();
		var $header = $('.site-header');
		if (viewport.width < 768) {
			return $header.addClass('autoheight');
		}
		$header.removeClass('autoheight').css( 'height', '' );
		$body.removeClass('active');
	}
	
	/* Set an element to the height of the window */
	function setDivHeight () {
		$( '.autoheight' ).css( 'height', $( window ).height() );		
	}

	function mobile_nav() {
		/* Toggle the navigation menu for small screens */
		// http://blog.teamtreehouse.com/using-jquery-to-detect-when-css3-animations-and-transitions-end	
		$('.menu-toggle').click(function (e) {
			$body.toggleClass('active').css('overflow', 'hidden');
			e.preventDefault();
			$body.one('webkitTransitionEnd otransitionend oTransitionEnd msTransitionEnd transitionend', function() {
				$body.css('overflow', 'visible');
				$('body.active').css('overflow', 'hidden');
			});
		});

		header_resize();
		setDivHeight();
		
		/* Add a menu hamburger svg icon to the top of the page */
		$('<svg version="1.1" id="menu-open" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="25px" height="15px" viewBox="0 0 25 15" enable-background="new 0 0 25 15" xml:space="preserve">\
			<line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" x1="0" y1="1" x2="25" y2="1"/>\
			<line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" x1="0" y1="14" x2="25" y2="14"/>\
			<line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" x1="0" y1="7.359" x2="25" y2="7.359"/>\
		</svg>').appendTo('.menu-toggle');
	}
	
	/**
	 * Dropdowns
	 */
	function dropdowns() {
		/* Open and switch between the header dropdowns */
		var toggler = function (e) {
			var toToggle = $(this).data().toggleId;
			var visible = function() {
				if ($('.info-panel').is(':visible')) {
					$("body").addClass("open");
				} else {
					$('body').removeClass('open');
				}
			};

			$("#" + toToggle).slideToggle('slow', 'easeOutQuart', visible).siblings().slideUp('slow', 'easeOutQuart', visible);
			e.preventDefault();
		};
		$('[data-toggle-id]').on("click", toggler);
		
		/* Relocate drop-down content into the header */
		$('#info').html($('.info-panel').detach());
		
		/* Add an open/close svg icon to the header drop-down */
		$('<svg version="1.1" id="close-cross" class="closer" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="35px" height="35px" viewBox="0 0 35 35" enable-background="new 0 0 35 35" xml:space="preserve">\
			<line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" x1="35" y1="0" x2="0" y2="35"/>\
			<line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" x1="0" y1="0" x2="35" y2="35"/>\
		</svg>').appendTo('.info-panel');
		
		/* Close the menu by clicking on the cross */
		$('.closer').on('click', function() {
			$('.info-panel').slideUp('slow');
			$('body').removeClass('open');
		});
	}
	
	/**
	 * Layout
	 *
	 * Various functions to help layout the page.
	 *
	 * These all need to be wrapped in a function so that they can be called back after an ajax page load.
	 */

	/* Fade out the Social Nav when the search box is in focus */
	function searchExpand() {
		viewport = updateViewportDimensions();
		var $menu = $('.social-menu a'),
		$search = $('.social-menu .search-field');
		if (viewport.width > 768) {
			$search.focus(function () {
				$menu.hide();
			}).blur(function () {
				$menu.fadeIn(500);
			});
		} else {
			$search.focus(function () {
				$menu.show();
			});
		}
	}

	/**
	 * Layout Hacks
	 *
	 * Mostly small hacks and tweaks to configure the layout, some of which are often simpler to do like this than through the Wordpress backend.
	 * All of these need to be called back after the ajax page load.
	 *
	 * In time all of these should be imlpemented server side.
	 */
	function layout_hacks() {
		/* Remove inline styles from wp-caption */
		$(".wp-caption").removeAttr('style');
		
		/* Add a class to the first article in the editor selection on the reading pages */
		$('.editor-selection > :first-child').addClass("pseudo-content-divider-top-articles-xs-max");
		
		/* Wordpress placeholder hack */
		$('.search-field').attr("placeholder", "Search");
	}

	function layout() {
		/* Launch the gradients */
		$('.gradienter').rainbow();
	
		/* FitVids */
		$(".fitvids").fitVids({ customSelector: "iframe[data-src*='player.vimeo.com'], iframe[data-src*='youtube.com'], iframe[data-src*='youtube-nocookie.com'], iframe[data-src*='kickstarter.com'][data-src*='video.html']"});
			
		/* Pullquotes */
		$('span.pullquote').each(function() { 
			var $parentParagraph = $(this).parent('p'); 
			$parentParagraph.css('position', 'relative'); 
			$(this).clone().addClass('pulledquote').prependTo($parentParagraph); 
		});
		
		/* Open the Sharing Links in a new, smaller window */
		$('.popout-link a').click(function() {
			var	top = ($(window).height()/2)-(480/2);
			var left = ($(window).width()/2)-(480/2);
			var NWin = window.open($(this).prop('href'), '', 'height=480, width=480 top=' + top + ', left= ' + left);
			if (window.focus){
				NWin.focus();
			}
			return false;
		});
		
		searchExpand();
		layout_hacks();
	}
	
	/**
	 * Layout adjustments for the 404 page
	 */
	function four_oh_four() {
		/* Add focus to 404 page search form */
		$(".page-content .search-field").focus();
		
		/* Wrap the logo in a div to create the 404 page */
		$(".site-logo").wrap("<div class='message404'></div>");
	}

	/**
	 * Auto add protocal to url form validation
	 */
	function input_url_force_protocol() {
		$('.auto-protocol').blur(function() {
			var string = $(this).val();
			if (! string.match(/^https?:/)){
				string = "http://" + string;
			}
			$(this).val(function() {
				return string;
			});
		});
	}
	
	/**
	 * Ajax Page Load - history.js
	 */
	function ajax_load() {
		var History = window.History, // Note: Using a capital H instead of a lower h
			State = History.getState();
			//$log = $('#log');

		// If the link rel is set to `ajax`, trigger the pushstate
		function ajax_click() {
			$('a[rel=ajax]').on('click', function(e) {
				e.preventDefault();
				var path = $(this).attr('href'),
					title = $(this).text() + " - Opening Times";
				History.pushState('ajax', title, path);
			});
			
			// need to ensure this is always set to base url
			$(".search-form").submit(function(e) {
				e.preventDefault();
				var search = $("[name=s]").val(),
					path = window.location.origin + '?s=' + search,
					//path = '?s=' + search,
					title = search + " - Search Results - Opening Times";
				History.pushState('ajax', title, path);
			});
		}	
		ajax_click();
					
		// Callback the scripts needed after the Ajax page load.
		function callback_scripts() {
			layout_hacks();
			layout();
			mobile_nav();
			$body.removeClass('active').css( "overflow", "visible" );
			opening_times_accordion();
			ajax_click();
			input_url_force_protocol();

			// Update Google analytics
			/*
			var loc = window.location,
			page = loc.hash ? loc.hash.substring(1) : loc.pathname + loc.search;
			ga('send', 'pageview', page);
			*/

			/*
			 * Log all jQuery AJAX requests to Google Analytics
			 * See: http://www.alfajango.com/blog/track-jquery-ajax-requests-in-google-analytics/
			 */
			if (typeof ga !== "undefined" && ga !== null) {
				$(document).ajaxSend(function(event, xhr, settings){
					ga('send', 'pageview', settings.url);
				});
			}
		}

		// Bind to state change
		// When the statechange happens, load the appropriate url via ajax
		History.Adapter.bind(window, 'statechange', function() { // Note: Using statechange instead of popstate
			load_ot_ajax();
		});
		
		// Load Ajax
		function load_ot_ajax() {
			State = History.getState(); // Note: Using History.getState() instead of event.state
					
			$("body").prepend('<div id="ajax-loader"><span>Loading</span></div>');
			$("#ajax-loader").fadeIn();
			$("#content").load(State.url + ' #content > *', function(data, status, xhr) {
			//$("#main").load(State.url + ' #primary, #secondary', function(data) {
				if ( status === "error" ) {
					var msg = "Sorry but there was an error: ",
						msg2 = "Please reload the page.";
					$( "#ajax-loader" ).wrap( "<div id='ajax-error'></div>" ).html( msg + xhr.status + " " + xhr.statusText + "<br>" + msg2 );
				} else {
					$("#ajax-loader").fadeOut("fast", function() { 
						$(this).detach();
					});
					callback_scripts();
				}
				
				// Updates the menu
				//var request = $(data);
				//$('#menu-navigation').replaceWith($('#menu-navigation', request));
				
			});
		}
	}
	
	/* Sticky Footer */
	$('<div id="push"></div>').appendTo('#page');

	/* Firefox menu form normalisation */
	$('.search-form').height($(".menu-item").height());

	/* Konami code */
	$(window).konami({
		cheat: function () {
			$body.addClass("konami").append('<audio src="../wp-content/themes/opening_times/assets/timba.mp3" preload="auto" autoplay loop></audio>');
			$body.ready(function(){
				setTimeout(function(){
					changefont("serif");
				},400);
				setTimeout(function(){
					changefont("cursive");
				},800);
				setTimeout(function(){
					changefont("monospace");
				},1200);
				setTimeout(function(){
					changefont("fantasy");
				},1600);
				setTimeout(function(){
					changefont("sans-serif");
				},2000);
				setTimeout(function(){
					changefont("Symbol");
				},2400);
				setTimeout(function(){
					changefont("Webdings");
				},2800);
				setTimeout(function(){
					changefont("Wingdings");
				},3200);
			});
			function changefont(a){
				$("body").css({"font-family":a});
				setTimeout(function(){
					changefont(a);
				},3200);
			}
		}
	});
	
    /**
     * Prevent iOS from zooming onfocus
     * https://github.com/h5bp/mobile-boilerplate/pull/108
     * Adapted from original jQuery code here: http://nerd.vasilis.nl/prevent-ios-from-zooming-onfocus/
     */
    var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]');

    function preventZoom () {
        if (viewportmeta && navigator.platform.match(/iPad|iPhone|iPod/i)) {
            var formFields = document.querySelectorAll('input, select, textarea'),
            	contentString = 'width=device-width,initial-scale=1,maximum-scale=',
            	i = 0,
            	fieldLength = formFields.length;

            var setViewportOnFocus = function() {
                viewportmeta.content = contentString + '1';
            };

            var setViewportOnBlur = function() {
                viewportmeta.content = contentString + '10';
            };

            for (; i < fieldLength; i++) {
                formFields[i].onfocus = setViewportOnFocus;
                formFields[i].onblur = setViewportOnBlur;
            }
        }
    }

	ot_resize = function() {
		header_resize();
		setDivHeight();
		searchExpand();
	};

    ot_launch = function() {
		opening_times_accordion();
		mobile_nav();
		dropdowns();
		layout();
		input_url_force_protocol();
		ajax_load();
		preventZoom();
		ot_resize();
		if ($('body').hasClass('error404')) {
        	four_oh_four();
    	}
    	addFieldToCheck("email", "Email address");
		addFieldToCheck("emailconfirm", "Confirm your email address");
    };
	
})( jQuery );

jQuery(document).ready(function($) {
	ot_launch();

	$(window).resize(function () {
        waitForFinalEvent(function () {
            ot_resize();
        }, timeToWaitForLast, "screenz resize");
    });
});
