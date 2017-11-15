// Definitions
var type;

// Detect Mobile Browser
function mobilecheck() {
    var check = false;
    (function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))){check = true;}})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
}

// Detect YouTube and Vimeo urls
function parseVideo(url) {
    // http://stackoverflow.com/questions/5612602/improving-regex-for-parsing-youtube-vimeo-urls
    // - Supported YouTube URL formats:
    //   - http://www.youtube.com/watch?v=My2FRPA3Gf8
    //   - http://youtu.be/My2FRPA3Gf8
    //   - https://youtube.googleapis.com/v/My2FRPA3Gf8
    // - Supported Vimeo URL formats:
    //   - http://vimeo.com/25451551
    //   - http://player.vimeo.com/video/25451551
    // - Also supports relative URLs:
    //   - //player.vimeo.com/video/25451551

    url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);

    if ( RegExp.$3.indexOf('youtu') > -1 ) {
        type = 'youtube';
    }
    else if ( RegExp.$3.indexOf('vimeo') > -1 ) {
        type = 'vimeo';
    }

    return {
        type: type,
        id: RegExp.$6
    };
}

// Detect audio and video urls
function parseMedia(url) {
	var extension = url.substr( ( url.lastIndexOf('.') + 1 ) );

	switch(extension) {
		case 'mp3':
		case 'aac':
		case 'ogg':
			type = 'audio';
			break;
		case 'mp4':
		case 'webm':
			type = 'video';
			break;
		case 'jpg':
		case 'jpeg':
		case 'png':
		case 'gif':
			type = 'image';
			break;
	}

	return type;
}

/**
 * File skip-link-focus-fix.js.
 *
 * Helps with accessibility for keyboard only users.
 *
 * Learn more: https://git.io/vWdr2
 */
( function() {
	var isWebkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    isOpera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    isIe     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( isWebkit || isOpera || isIe ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
})();

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

( function ( $ ) {
	// Global Definitions
	var breakpoints = {
			screen_xl: 1200,
			screen_lg: 992,
			screen_md: 768,
			screen_sm: 576,
			screen_xs: 480,
		},
		page = 1,
		volFade_duration = 1000;

	// Calculate if screen size is smaller/larger than default breakpoint
	function screenLessThan( breakpoint ) {

		// Get the width of the current window
		var windowWidth = $( window ).width();

		// Return true/false if window with is equal or smaller than breakpoint
		return ( parseInt( windowWidth ) <= parseInt( breakpoint ) );
	}

	// Do Lazy Load
	function lazy_load_media( el ) {
		var $el = $(el);

		$el.each(function() {
			var $src = $el.data('src');

			// Bail if we have already performed a lazy load.
			if ( $el.is('[data-lazy-loaded]') ) {
				return;
			}

			$el.attr('src', $src).attr('data-lazy-loaded', 'true').addClass('lazy-loaded');
		});
	}

	// SmoothState
	function ot_smooth_state() {
        var $body = $('body'),
            $main = $('#page'),
            $site = $('html, body'),
            //transition = 'fade',
            smoothState,
	        options = {
	           	prefetch: true,
	            prefetchOn: 'mouseover touchstart',
	            cacheLength: 2,
	            blacklist: '.post-edit-link',
	            onStart: {
	                duration: 1000,
	                //render: function (url, $container) {
	                render: function () {
	                    $main.addClass('is-exiting');
	                    $site.animate({scrollTop: 0});
	                    //$body.addClass('stop-scrolling');
	                }
	            },
	            onReady: {
	                duration: 0,
	                render: function ( $container, $newContent ) {
	                    $container.html($newContent);
	                    $container.removeClass('is-exiting');
	                }
	            },
	            //onAfter: function($container, $newContent) {
	            onAfter: function() {
	                $body.removeClass('stop-scrolling');
	                $('#overlay').remove();

	                // reset the page counter
	                page = 1;
	                ot_page_load();
	            },
            };

        smoothState = $main.smoothState(options).data('smoothState');
	}

	// Set autoplay attribute
	function media_autoplay_att( el ) {
		var $iframe = $(el).find('iframe');

		$iframe.attr('data-autoplay', '');
	}

	// Adjust mobile url params
	function mobile_url_params( el ) {
		if( mobilecheck() ) {
			var $iframe = $(el).find('iframe'),
				vid = $iframe .is(['data-src']) ? $iframe .data('src') : $iframe .attr('src');

			$iframe.each(function () {
				var src = $(this).attr('src');

				parseVideo( vid );
				
				if ( type === 'vimeo' ) {
					$(this).attr('src', src.replace('background=1', 'background=0'));
				}
			});
		}
	}

 	// Hide popovers
	function hidePopover( el ) {
		if( $('.popover').length ) {
			$(el).find('.media-sample').popover('hide');
		}
	}

	// Play media
	function playMedia( panel, vol ) {
		var media = panel.find('video, audio'),
			iframe = panel.find('iframe'),
			vid = $(iframe).is(['data-src']) ? iframe.data('src') : iframe.attr('src');

		// play HTML5 media elements
		if( $(media).length > 0 ) {
			media.each(function(){
				var $this = $(this),
					element = $this.get(0);

				if( element.hasAttribute('data-autoplay') && typeof element.play === 'function' ) {
					
					// Set the volume
					$this.prop('volume', vol);

					if(element.volume === 0) {
						element.play().then(function() {
							$this.animate({volume: vol}, volFade_duration * 2);
						});
					} else {
						element.play();
					}
				}
			});
		}

		// play embedded videos
		if( $(iframe).length > 0 ) {
			iframe.each(function() {
				// determine embed type
				parseVideo( vid );

				var element = iframe.get(0);

				if ( type === 'vimeo' && element.hasAttribute('data-autoplay') ) {
					element.contentWindow.postMessage('{"method": "play"}', '*');
				}

				if ( type === 'youtube' && element.hasAttribute('data-autoplay') ) {
					element.contentWindow.postMessage('{"event": "command", "func": "playVideo", "args": ""}', '*');
				}
			});
		}
	}

	// Stop media
	function stopMedia( panel ) {
		var media = panel.find('video, audio'),
			iframe = panel.find('iframe'),
			// This works, but maybe better to determine if src is set,
			// as performing these actions on a data attr is silly.
			vid = $(iframe).is(['data-src']) ? iframe.data('src') : iframe.attr('src');

		// pause HTML5 media elements
		if( $(media).length > 0 ) {
			media.each(function(){
				var $this = $(this),
					element = $this.get(0);

				if( ! element.hasAttribute('data-keepplaying') && typeof element.pause === 'function' ) {
					$this.animate({volume: 0}, volFade_duration, function () {
						element.pause();
					});
				}
			});
		}

		// pause embedded videos
		if( $(iframe).length > 0 ) {
			iframe.each(function() {
				// determine embed type
				parseVideo( vid );

				var element = iframe.get(0);

				if ( type === 'vimeo' && ! element.hasAttribute('data-keepplaying') ) {
					element.contentWindow.postMessage('{"method": "pause"}', '*');
				}

				if ( type === 'youtube' && ! element.hasAttribute('data-keepplaying') ) {
					element.contentWindow.postMessage('{"event": "command", "func": "pauseVideo", "args": ""}', '*');
				}
			});
		}
	}

	// Lower bg volume
	function lowerVolume( panel, vol ) {
		var media = panel.find('video, audio'),
			iframe = panel.find('iframe'),
			vid = $(iframe).is(['data-src']) ? iframe.data('src') : iframe.attr('src');

		// lower volume of HTML5 media elements
		if( $(media).length > 0 ) {
			media.each(function(){
				var $this = $(this);

				$this.animate({volume: vol}, volFade_duration);
			});
		}

		// lower volume of embedded videos
		if( $(iframe).length > 0 ) {
			iframe.each(function() {
				// determine embed type
				parseVideo( vid );

				var element = iframe.get(0);

				if ( type === 'vimeo' ) {
					element.contentWindow.postMessage('{"method": "setVolume", "value":0.1}', '*');
				}
			});
		}
	}

	// Raise bg volume
	function raiseVolume( panel, vol ) {
		var media = panel.find('video, audio'),
			iframe = panel.find('iframe'),
			vid = $(iframe).is(['data-src']) ? iframe.data('src') : iframe.attr('src');

		// raise volume of HTML5 media elements
		if( $(media).length > 0 ) {
			media.each(function(){
				var $this = $(this);

				$this.animate({volume: vol}, volFade_duration);
			});
		}

		// raise volume of embedded videos
		if( $(iframe).length > 0 ) {
			iframe.each(function() {
				// determine embed type
				parseVideo( vid );

				var element = iframe.get(0);

				if ( type === 'vimeo' ) {
					element.contentWindow.postMessage('{"method": "setVolume", "value":' + vol + '}', '*');
				}          
			});
		}
	}

	// Aspect Ratio -- full screen, centered images and embeds
	function opening_times_fs_aspect_ratio() {
		var $fsmedia = $('.aspect-ratio');

		$fsmedia.each(function() {
			var imgHeight = $(this).find('img').attr('height') ? $(this).find('img').attr('height') : '9',
				imgWidth = $(this).find('img').attr('width') ? $(this).find('img').attr('width') : '16',
				aspectRatio;

			// Do we want the intrinsic-ratio?
			if ( $(this).is('.intrinsic-ratio') ) {
				aspectRatio = imgHeight / imgWidth;

				$(this).css('padding-bottom', (aspectRatio * 100)+'%');
				return;
			}

			// Or to fill the screen and maintain ratio?
			aspectRatio = imgWidth / imgHeight;
			
			var $container = $('.slide');

			$(this).attr('data-ratio', aspectRatio);

			if ( ! screenLessThan( breakpoints.screen_md ) ) {
				$(this).removeClass('embed-responsive embed-responsive-16by9');
				if ( $(this).data('ratio') > 1 ) {
					$(this).addClass('landscape');
					if ($container.width() / $container.height() >= aspectRatio) {
						$(this).css({
							'height': $container.width() / aspectRatio,
							'width': $container.width(),
							'margin-left': '0',
							'margin-top': (($container.height() - $(this).height()) / 2)
						});
					} else {
						$(this).css({
							'height': $container.height(),
							'width': $container.height() * aspectRatio,
							'margin-top': '0',
							'margin-left': (($container.width() - $(this).width()) / 2)
						});
					}
				} else {
					$(this).addClass('portrait');
				}

			} else {
				$(this).removeAttr('style').addClass('embed-responsive embed-responsive-16by9');
			}
		});
	}

	// Expanding Search Bar
	function ot_search_expand_btn(e) {
		e.stopPropagation();

		var $searchWrap = $('.expanding-search'),
			$input = $('.expanding-search .search-field'),
			$menu = $('.navigation-social a');

		if ( ! $searchWrap.is('.in') ) {
			e.preventDefault();

			$searchWrap.addClass('in');
			$input.focus();
			$menu.addClass('invisible');

			var bodyFn = function(event) {
				if( $(event.target).is($input) ) {
					return;
				}
				$searchWrap.removeClass('in');
				$input.blur();
				$menu.removeClass('invisible');
				$(document).off('click', bodyFn);
			};

			$(document).on('click', bodyFn);
			
		} else if ( $searchWrap.is('.in') && /^\s*$/.test( $input.val() ) ) {
			e.preventDefault();

			$searchWrap.removeClass('in');
			$input.blur();
			$menu.removeClass('invisible');
		}
	}

	function ot_search_toggle() {
		var $expand = $('[data-toggle="search-expand"]'),
			$input = $('.expanding-search .search-field'),
			$submit = $('.expanding-search .search-submit'),
			$menu = $('.navigation-social a');

		if ( ! screenLessThan( breakpoints.screen_md ) ) {
			$expand.on('click', ot_search_expand_btn);
			$submit.on('click', ot_search_expand_btn);
			$input.attr('placeholder', '');

			$submit.addClass('btn').removeClass('screen-reader-text');
		} else {
			$expand.off('click', ot_search_expand_btn);
			$submit.off('click', ot_search_expand_btn);
			$input.attr('placeholder', 'Search');

			$menu.removeClass('invisible');
			$submit.addClass('screen-reader-text').removeClass('btn');
		}
	}

	// Art directed popovers
	// The WordPress editor keeps stripping out the video html from the regular popover shortcode.
	// This is a temporary fix that extends the media sample shortcode until that can be resolved.
	function ot_media_popovers( el ) {
		$(el).each(function() {
			var $this = $(this),
				mediaSrc = $this.data('media'),
				placementType = $this.closest('.slide__text--sidebar').length > 0 ? 'right' : 'bottom',
				triggerType = $this.closest('.accordion-header').length > 0 ? 'hover' : 'click',
				mediaType;

			parseMedia(mediaSrc);

			if ( type === 'audio' ) {
				mediaType = '<audio src="' + mediaSrc + '" loop autoplay controls controlsList="nodownload"></audio>';
			} 
			if ( type === 'video' ) {
				mediaType = '<video src="' + mediaSrc + '" loop autoplay></video>';
			} 
			if ( type === 'image' ) {
				mediaType = '<img src="' + mediaSrc + '">';
			}

			$this.popover({
				placement: placementType,
				html: true,
				template: '<div class="popover popover--large" role="tooltip"><div class="popover-content"><div class="popover__media-container"></div></div></div>',
				content: mediaType,
				trigger: triggerType,
			});
		});
	}

	// Custom flikity navigation btns
	function ot_flickity_nav( $el, $prev, $next, event ) {
		// Go to previous cell
		$prev.on( event, function() {
			$el.flickity('previous');
		});

		// Go to next cell
		$next.on( event, function() {
			$el.flickity('next');
		});
	}

	// Custom flickity button atts
	function ot_flickity_btn_atts( el, $prev, $next, $nav ) {
		el.on( 'cellSelect', function() {
			var target = el.selectedCell.target,
				isCarouselEnd = false;

			if ( target === el.cells[0].target ) {
				isCarouselEnd = !isCarouselEnd;

				$prev.attr('disabled', true);
			} else if ( target === el.getLastCell().target ) {
				isCarouselEnd = !isCarouselEnd;

				$next.attr('disabled', true);
			} else {
				isCarouselEnd = isCarouselEnd;

				$nav.removeAttr('disabled');
			}
		});
	}

	// Flickity infinite scroll
	function ot_flickity_append_items( carousel, el ) {
		carousel.on( 'settle.flickity', function() {
			if ( el.selectedIndex === el.cells.length - 1 ) {
				makeCellHtml( carousel );
			}
		});
	}

	// Build additional Flickity Cells
	function makeCellHtml( carousel ) {
		page++;

		var url = '/wp-json/wp/v2/news?page=' + page + '';

		$.getJSON(url).done(function( data ) {
			$.each( data, function( index, item ) {
				var $cell = $(
					'<div class="carousel-cell page-' + page + '">' + 
						'<h2 class="entry-header">' + item.title.rendered + '</h2>' + 
						'<div class="entry-content">' + item.content.rendered + '</div>' + 
					'</div>'
				);

				carousel.flickity( 'append', $cell );
			});
  		}).fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ', ' + error;
			console.log( 'Request Failed: ' + err );
		});
	}

	// Gradient Text
	function makeGradients( $el, selector, h, s, l ) {
		$el.gradienter({
			hueStart: h, 
			selector: selector, 
			saturation: s, 
			lightness: l
		});
	}

	// Actions that happen on page load, or via ajax callback
	function ot_page_load() {
		// Definitions
		var eventtype = mobilecheck() ? 'touchstart' : 'click',
			//$window = $(window),
			hash = window.location.hash,
		    $scene = $('#scene'),
			$accordion = $('.accordion .collapse'),
			$gradients = $('.gradient-container'),
			$infoCollapse = $('.site-info .collapse'),
			$infoClose = $('.site-info .close'),
		    $splashTop = $('.splash-top__link'),
		    $autoProtocol = $('.auto-protocol'),
		    //$infinite = $('.infinite'),
		    $anchorScroll = $('a[href*="#"]:not([href="#"], [data-toggle="collapse"], .ot-social-links a)'),
			isSidebarOpen = false;

		// Launch the gradients
		makeGradients( $gradients, '.gradient-text', 240, 100, 50 );

		// Expand the search form on focus
		ot_search_toggle();

		// Call aspect ratio
		opening_times_fs_aspect_ratio();
		//$window.resize(opening_times_fs_aspect_ratio).trigger('resize');

		$accordion.on('show.bs.collapse', function () {
			var $this = $(this);

			$this.parent('.card').addClass('show');

			// Show sidebar captions
			if ( $this.closest('.card').is('.slide_text--sidebar') ) {
				var $target = $($this.closest('.slide_text--sidebar').attr('data-caption'));

				// Hide the issue list
				$('.reading__issue-list').addClass('out');
				
				// Show the captions
				$target.addClass('in');
			}
		});

		$accordion.on('shown.bs.collapse', function () {
			var $this = $(this);

			// Load media when accordion opened
			if( $this.has('.lazyload').length ) {
				var $el = $($this.find('.lazyload'));

				$el.each(function() {
					lazy_load_media( this );
				});
			}

			// Play media when accordion opened
			playMedia( $this, 0.2 );
			mobile_url_params( $this );
		});

		$accordion.on('hide.bs.collapse', function () {
			var $this = $(this),
				$issueList = $('.reading__issue-list');

			$this.parent('.card').removeClass('show');

			// Remove sidebar captions
			if ( $this.closest('.card').is('.slide_text--sidebar') ) {
				var $target = $($this.closest('.slide_text--sidebar').attr('data-caption'));

				// Remove caption
				$target.removeClass('in');

				// Reinstate issue list
				if ( $('.slide_text--sidebar.show').length === 0 ) {
					$issueList.removeClass('out');
				}

				// Remove sidebar popover
				hidePopover( $target );
			} else {
				// Remove content popover
				hidePopover( this );
			}
		});

		$accordion.on('hidden.bs.collapse', function () {
			var $this = $(this);

			// Pause media on accordion close
			stopMedia( $this );
		});

		// Site info toggle
		$infoCollapse.on('show.bs.collapse', function () {
			$(this).siblings().collapse('hide');
		});

		// Close the info panels
		$infoClose.on(eventtype, function() {
			$(this).closest('.collapse').collapse('hide');
		});


		// Flickity options
		var $newsDropdown = $('#collapse-news'),
			flickityOptions = {
				cellSelector: '.carousel-cell',
				cellAlign: 'left',
				prevNextButtons: false,
				pageDots: false,
				watchCSS: true,
			},
			$carousel = $('.carousel');

		// Navigate the news items
		var $btnNav = $('.btn-nav'),
			$btnPrev = $('.btn-prev'),
			$btnNext = $('.btn-next');

		// Init Flickity when news panel is opened
		$newsDropdown.one('shown.bs.collapse', function () {
			// Init the flickity instance
			$carousel.flickity(flickityOptions);
			
			// Access flickity data
			var flkty = $carousel.data('flickity');

			// Trigger flickity resize to position previously hidden cells
			$carousel.flickity('resize');

			// Add new items at last cell
			ot_flickity_append_items( $carousel, flkty ); 

			// Add custom navigation btns
			ot_flickity_nav( $carousel, $btnPrev, $btnNext, eventtype );

			// Add custom btn atts
			ot_flickity_btn_atts( flkty, $btnPrev, $btnNext, $btnNav );
		});

		// Close the news panel
		$newsDropdown.on('hidden.bs.collapse', function () {
			// Reset flickity position
			$carousel.flickity( 'select', 0, false, true );
		});


		// Open accordion corresponding to location hash
		var $accordionId = $(hash + '.collapse');

        if ( hash && $accordionId ) {
        	$accordionId.prev('.collapsed').trigger(eventtype);
        }

        // Open accodion from new commission splash.
		$splashTop.on(eventtype, function() {
			var $splashPanel = $($(this).data('open'));

			$splashPanel.prev('.collapsed').trigger(eventtype);
		});

		// Auto add protocol to url form validation
		$autoProtocol.blur(function() {
			var string = $(this).val();
			
			if (! string.match(/^https?:/)){
				string = "http://" + string;
			}
			$(this).val(function() {
				return string;
			});
		});

		// Infinite Scroll
		/*
		if( $infinite.length ) {
			var $loadMore = $('.site-main > div');

			$loadMore.append( '<span class="load-more"></span>' );
			
			var button = $('.load-more'),
				page = 2,
				loading = false,
				scrollHandling = {
					allow: true,
					reallow: function() {
						scrollHandling.allow = true;
					},
					//(milliseconds) adjust to the highest acceptable value
					delay: 400
				};

			$window.scroll(function(){
				if( ! loading && scrollHandling.allow ) {
					scrollHandling.allow = false;
					setTimeout(scrollHandling.reallow, scrollHandling.delay);
					
					var offset = $(button).offset().top - $window.scrollTop();
					
					if( 2000 > offset ) {
						loading = true;
						var data = {
							action: 'opening_times_ajax_load_more',
							page: page,
							query: otloadmore.query,
						};

						$.post(otloadmore.url, data, function(res) {
							if( res.success) {
								$infinite.append( res.data );
								$infinite.append( button );
								page = page + 1;
								loading = false;

								// Callback scripts here
								makeGradients( $gradients, '.gradient-text', 240, 100, 50 );

							} else {
								console.log(res);
							}
						}).fail(function( jqxhr, textStatus, error ) {
							var err = textStatus + ', ' + error;
							console.log(err);
						});
					}
				}
			});
		}
		*/

	
		// Smooth Scroll to anchor
		$anchorScroll.on(eventtype, function() {
			if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && location.hostname === this.hostname) {
				
				var target = $(this.hash);

				target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
				
				if (target.length) {
					$('html, body').animate({
						scrollTop: target.offset().top
					}, 500 );
					return false;
				}
			}
		});

		// Popovers
		$('[data-toggle="popover"]').popover();

		// Popover show
		$scene.on('shown.bs.popover', function () {
			var $popoverLg = $('.popover--large');

			if( $popoverLg.length ) {
				lowerVolume( $('.accordion .show'), 0.1 );
			}
		});

		// Popover close
		$scene.on('hidden.bs.popover', function () {
			raiseVolume( $('.accordion .show'), 0.9 );
		});

		// Call Media popovers
		ot_media_popovers( '.media-sample' );

		// Ensure all slide iframes have autoplay attr
		media_autoplay_att( '.autoplay' );

        // Toggle offcanvas       
        $scene.on(eventtype, '[data-toggle="offcanvas"]', function(e) {
            e.preventDefault();

            var $this = $(this),
                $target = $($this.attr('data-target')),
                $body = $('body');
                
            isSidebarOpen = !isSidebarOpen;

            $target.toggleClass('in');
            $this.toggleClass('active');

            if ( isSidebarOpen ) {
            	$body.addClass('stop-scrolling');
            	$scene.append('<div id="overlay" class="fixed-fs offcanvas__overlay" data-toggle="offcanvas" data-target="#site-navigation"></div>');
            } else {
                $body.removeClass('stop-scrolling');
                $('#overlay').remove();
            }

            $this.attr('aria-expanded', function (i, attr) {
                return attr === 'true' ? 'false' : 'true';
            });
        });
	}

	// Resize
	function ot_resize() {
		opening_times_fs_aspect_ratio();
		ot_search_toggle();

		if ( $('.slide__text--sidebar').length > 0 ) {
			if ( screenLessThan( breakpoints.screen_md ) ) {
				$('.reading__issue-list').removeClass('out');
				$('.slide__text--sidebar').removeClass('in');
			}
		}
	}

	$(document).ready(function() {
		// Prepare to launch
		ot_smooth_state();
		ot_page_load();

		$(window).resize(function () {
			waitForFinalEvent(function () {
					ot_resize();
				}, 
				timeToWaitForLast, 
				"screenz resize"
			);
		});
	});

})( jQuery );