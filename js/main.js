/* globals IntersectionObserver, jQuery */

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
		volFade_duration = 1000,
		isSidebarOpen = false,
		$body = $('body');


	// Calculate if screen size is smaller/larger than default breakpoint
	function screenLessThan( breakpoint ) {

		// Get the width of the current window
		var windowWidth = $( window ).width();

		// Return true/false if window with is equal or smaller than breakpoint
		return ( parseInt( windowWidth ) <= parseInt( breakpoint ) );
	}

	// Toogle scroll
	function stopScroll( bool, el ) {
		if ( ! screenLessThan( breakpoints.screen_md ) ) {
			return;
		}

		if ( bool === true ) {
			el.addClass('stop-scrolling');
		} else {
			el.removeClass('stop-scrolling');
		}
	}

	// Init Lazy Load
	var images,
		config = {
			// If the image gets within 200px in the Y axis, start the download.
			rootMargin: '200px 0px',
			threshold: 0.01
		},
		imageCount = 0,
		observer,
		image,
		i;

	function lazy_load_init() {
		images = document.querySelectorAll( 'img[data-lazy-src]' );
		imageCount = images.length;

		// If initialized, then disconnect the observer
		if ( observer ) {
			observer.disconnect();
		}

		// If we don't have support for intersection observer, load the images immediately
		if ( ! ( 'IntersectionObserver' in window ) ) {
			loadImagesImmediately( images );
		} else {
			// It is supported, load the images
			observer = new IntersectionObserver( onIntersection, config );

			// foreach() is not supported in IE
			for ( i = 0; i < images.length; i++ ) {
				image = images[ i ];
				if ( image.getAttribute( 'data-lazy-loaded' ) ) {
					continue;
				}

				observer.observe( image );
			}
		}
	}

	/**
	 * Load all of the images immediately
	 * @param {NodeListOf<Element>} immediateImages List of lazy-loaded images to load immediately.
	 */
	function loadImagesImmediately( immediateImages ) {
		var i;

		// foreach() is not supported in IE
		for ( i = 0; i < immediateImages.length; i++ ) {
			var image = immediateImages[ i ];
			lazy_load_media( image );
		}
	}

	/**
	 * On intersection
	 * @param {array} entries List of elements being observed.
	 */
	function onIntersection( entries ) {
		var i;

		// Disconnect if we've already loaded all of the images
		if ( imageCount === 0 ) {
			observer.disconnect();
		}

		// Loop through the entries
		for ( i = 0; i < entries.length; i++ ) {
			var entry = entries[ i ];

			// Are we in viewport?
			if ( entry.intersectionRatio > 0 ) {
				imageCount--;

				// Stop watching and load the image
				observer.unobserve( entry.target );
				lazy_load_media( entry.target );
			}
		}
	}

	// Do Lazy Load
	function lazy_load_media( el ) {
		var $el = $(el);

		$el.each(function() {
			var $src = el.getAttribute( 'data-lazy-src' ),
				$srcset = el.getAttribute( 'data-lazy-srcset' ),
				$sizes = el.getAttribute( 'data-lazy-sizes' );

			// Bail if we don't have a data src
			if ( ! $src ) {
				return;
			}

			// Bail if we have already performed a lazy load.
			if ( $el.is('[data-lazy-loaded]') ) {
				return;
			}
			
			// Prevent this from being lazy loaded a second time.
			if ( el.classList ) {
				el.classList.add( 'lazy-loaded' );
			}

			el.setAttribute( 'data-lazy-loaded', '1' );

			el.setAttribute( 'src', $src );
			el.removeAttribute( 'data-lazy-src' );

			if ( $srcset ) {
				el.setAttribute( 'srcset', $srcset );
				el.removeAttribute( 'data-lazy-srcset' );
			}

			if ( $sizes ) {
				el.setAttribute( 'sizes', $sizes );
				el.removeAttribute( 'data-lazy-sizes' );
			}
		});
	}

	// SmoothState
	function ot_smooth_state() {
        var $main = $('#page'),
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
	                render: function () {
	                    $main.addClass('is-exiting');
	                    $site.animate({scrollTop: 0});
						//$('.collapse').collapse('hide');
	                }
	            },
	            onReady: {
	                duration: 0,
	                render: function ( $container, $newContent ) {
	                    $container.html($newContent);
	                    $container.removeClass('is-exiting');
	                }
	            },
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
				vid = $iframe .is(['data-lazy-src']) ? $iframe .data('lazy-src') : $iframe .attr('src');

			$iframe.each(function () {
				var src = $(this).attr('src');

				parseVideo( vid );
				
				if ( type === 'vimeo' ) {
					$(this).attr('src', src.replace('background=1', 'background=0'));
				}
			});
		}
	}

	// Check form values match
	function matchValues( val, confirm ) {
		var value1 = val[0].value,
			value2 = confirm[0].value,
			$feedback = confirm.next('.invalid-feedback');

		if ( value1 !== value2 ) {
			$feedback.text( $feedback.data('text-original') );
		} else {
			$feedback.data('text-original', $feedback.text());
			$feedback.text( $feedback.data('text-swap') );
		}
	}

	// Validate Forms
	function formValidation( el ) {
		var form = el[0];

		form.addEventListener( 'submit', function(e) {
			if ( form.checkValidity() === false ) {
				e.preventDefault();
				e.stopPropagation();
			}
			el.addClass('was-validated');

			matchValues( $('#field-ot-mail'), $('#field-ot-mail-confirm') );
		}, false);
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
			vid = $(iframe).is(['data-lazy-src']) ? iframe.data('lazy-src') : iframe.attr('src');

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
			vid = $(iframe).is(['data-lazy-src']) ? iframe.data('lazy-src') : iframe.attr('src');

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
			vid = $(iframe).is(['data-lazy-src']) ? iframe.data('lazy-src') : iframe.attr('src');

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
			vid = $(iframe).is(['data-lazy-src']) ? iframe.data('lazy-src') : iframe.attr('src');

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
	function ot_fs_aspect_ratio() {
		var $fsmedia = $('.aspect-ratio--js');

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
				placementType = $this.data('position'),
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
	function makeGradients( selector, h, s, l ) {
		var $gradient = $('.gradient-container');

		$gradient.gradienter({
			hueStart: h, 
			selector: selector, 
			saturation: s, 
			lightness: l
		});
	}

	// Play media on accordion opened
	function ot_accordion_play_media( $accordion ) {
		$accordion.on('shown.bs.collapse', function () {
			var $this = $(this);

			// Load media when accordion opened
			if( $this.has('[data-lazy-src]').length ) {
				var $el = $($this.find('[data-lazy-src]'));

				$el.each(function() {
					lazy_load_media( this );
				});
			}

			// Play media when accordion opened
			playMedia( $this, 0.2 );
			mobile_url_params( $this );
		});
	}

	// Stop media on accodion closed
	function ot_accordion_stop_media( $accordion ) {
		$accordion.on('hidden.bs.collapse', function () {
			var $this = $(this);

			// Pause media on accordion close
			stopMedia( $this );
		});
	}

	// Accordion functions
	function ot_accordion( $accordion ) {
		if( ! $accordion.length ) {
			return;
		}

		// Accordion open
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

		// Accordion opened
		ot_accordion_play_media( $accordion );

		// Accordion close
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

		// Accordion closed
		ot_accordion_stop_media( $accordion );
	}

	// Infinite Scroll
	function ot_infinite_scroll( options, $container ) {
		if( ! $container.length ) {
			return;
		}

		$container.infiniteScroll(options);
	}

	// Actions that happen on page load, or via ajax callback
	function ot_page_load() {
		// Definitions
		var eventtype = mobilecheck() ? 'touchstart' : 'click',
			hash = window.location.hash,
		    $scene = $('#scene'),
			$accordion = $('.accordion .collapse'),
			$infoCollapse = $('.site-info .collapse'),
			$infoClose = $('.site-info .close'),
		    $autoProtocol = $('.auto-protocol'),
		    $anchorScroll = $('a[href*="#"]:not([href="#"], a[href*="#panel-"], [data-toggle="collapse"], .ot-social-links a)');
			//isSidebarOpen = false;
			//div[class^="test"]
		
		// Infinite Scroll options
		var $infiniteContainer = $('.infinite'),
			infiniteOptions = {
				path: '.nav-previous a',
				append: '.post',
				prefill: true,
				hideNav: '.nav-links',
				history: false
			};

		// Infinite Scroll
		ot_infinite_scroll( infiniteOptions, $infiniteContainer );

		// Call back scripts after infinite load.
		$infiniteContainer.on('append.infiniteScroll', function() {
			makeGradients( '.gradient-text', 240, 100, 50 );
			lazy_load_init();

			// Reinit the accordion variable
			var $accordionInfinite = $('.accordion .collapse');
			
			// Accordion closed
			ot_accordion_stop_media( $accordionInfinite );
		});

		// Launch the gradients
		makeGradients( '.gradient-text', 240, 100, 50 );

		// Expand the search form on focus
		ot_search_toggle();

		// Call aspect ratio
		ot_fs_aspect_ratio();
		//$window.resize(ot_fs_aspect_ratio).trigger('resize');
		
		// Form validation
		formValidation( $('#mailing-list-subscribe') );

		// Lazy Load images
		lazy_load_init();

		// Accordion
		ot_accordion( $accordion );

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

        //
        var $ot = $('a[href*="#panel-"]');

        $ot.on(eventtype, function (e) {
        	var foo = $(e.target).attr('href');
        	
        	$(foo + '.collapse').prev('.collapsed').trigger(eventtype);
		});
		//

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
                $target = $($this.attr('data-target'));
                
            isSidebarOpen = !isSidebarOpen;

            $target.toggleClass('in');
            $this.toggleClass('active');

            if ( isSidebarOpen ) {
            	stopScroll( true, $body );
            	$scene.append('<div id="overlay" class="fixed-fs offcanvas__overlay" data-toggle="offcanvas" data-target="#site-navigation"></div>');
            } else {
                stopScroll( false, $body );
                $('#overlay').remove();
            }

            $this.attr('aria-expanded', function (i, attr) {
                return attr === 'true' ? 'false' : 'true';
            });
        });

        // Call fullpage slide
        // Init speed reader           
        speedReader.init();

        // Init the reader controls
        readerControls.init();

        // Init theme toggler
        themeToggle.init();

        // Init text split
        splitText.init();

        // Init text animations
        // animateIn.init();
        
        // Init fullpage scroll
        fullPage.init(); 
	}

	// Resize
	function ot_resize() {
		ot_fs_aspect_ratio();
		ot_search_toggle();

		var $slideSidebar = $('.slide__text--sidebar');

		if ( $slideSidebar.length > 0 ) {
			if ( screenLessThan( breakpoints.screen_md ) ) {
				$('.reading__issue-list').removeClass('out');
				$slideSidebar.removeClass('in');
			}
		}

		stopScroll( false, $body );
	}

	// Fullpage slides
	var $wpm = $('#spritz_wpm');
	var interval = 60000/$wpm.val();  
	var paused = false;
	var i = 0;
	var zoom = 1;
	var autosave = false;
	var isSpeedRead = false;
	var isAnimated = true;
	
    var splitter = function( el ) {
        var splitWord = el.trim()
        	.replace(/([-—])(\w)/g, '$1 $2')
			.replace(/[\r\n]/g, ' {linebreak} ')
			.replace(/[ \t]{2,}/g, ' ')
			.split(' ');

		return splitWord;
    };
    
    var stopScroll = function( bool, el ) {
		var $el = $(el);

		if ( bool ) {
			$el.addClass('stop-scroll');
		} else {
			$el.removeClass('stop-scroll');
		}
	};
    
    var fullPage = {
        config: {
            page: $('#spritz_words'),
            section: 'h2, p',
        },
        
        init: function( config ) {
            // merge config defaults with init config
			$.extend( fullPage.config, config );
                        
            //this.setAnchor();
            
            fullPage.config.page.fullpage({
                sectionSelector: fullPage.config.section,
                scrollBar: true,
            });
        },
        
        setAnchor: function() {
            $(fullPage.config.section).each(function(i, page){
                var $page = $(page);
                
                $page.attr('data-anchor', 'page-' + i);
            });
        }
    };
	
	var splitText = {
		words: '',
		config: {
			$words: $('#spritz_words p'),
			paragraphSm: 16,
			paragraphMd: 32,
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( splitText.config, config );

			this.split(' ');
		},

		// Text parseing
		split: function( after ) {     
			splitText.config.$words.each(function(i, paragraph) {
				var $paragraph = $(paragraph),
					$text = $paragraph.text(),
					wrappedWords = ''; 
                    
                splitText.words = splitter( $text );

                splitText.count($paragraph, splitText.config.paragraphSm, splitText.config.paragraphMd, i);

				$(splitText.words).each(function(i, word) {
					wrappedWords += '<span class="word-' + i + '" aria-hidden="true">' + word + '</span>' + after;
				});

                splitText.populate( $paragraph, $text, wrappedWords );
			});
		},
		count: function( $paragraph, sm, md, i ) {
			if ( splitText.words.length < parseInt(sm) ) {
				$paragraph.addClass('section-sm section-' + i).attr('data-anchor', 'page-' + i);
			} else if ( splitText.words.length < parseInt(md) ) {
				$paragraph.addClass('section-md section-' + i).attr('data-anchor', 'page-' + i);
			} else {
				$paragraph.addClass('section-lg section-' + i).attr('data-anchor', 'page-' + i);
			}
		},
		populate: function( $paragraph, $text, wrappedWords ) {
			$paragraph.attr('aria-label', $text).empty().append(wrappedWords);
		}
	};
 
	var speedReader = {
        spritz:    '',
        words:     '',
        local:     {},
        wpmOutput: $wpm.next()[0],
		config: {
            container:       $('body'),
			readingProgress: $('#spritz_progress'),
			reader:          $('#spritz'),
			alert:           $('#alert'),
			save:            $('#spritz_save'),
			autosave:        $('#autosave_checkbox'),
			space:           $('#spritz_word'),
			$words:          $('#spritz_words'),
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( speedReader.config, config );

			if ( ! localStorage.jqspritz ) {
				speedReader.wordsSet();
				speedReader.wordShow(0);
				speedReader.wordUpdate();
				speedReader.pause(true);
			} else {
				speedReader.local = JSON.parse(localStorage.jqspritz);
				speedReader.config.$words.val(speedReader.local.words);
				i = speedReader.local.word;
				if ( speedReader.local.autosave ) {
					autosave = true;
					speedReader.config.container.addClass('autosave');
					speedReader.config.autosave.prop('checked', true);
				}
				$wpm.val(speedReader.local.wpm);
				interval = 60000/speedReader.local.wpm;
				speedReader.zoom(0);
				speedReader.wordsSet();
				speedReader.wordShow(i);
				speedReader.wordUpdate();
				speedReader.pause(true);
				speedReader.alert('loaded');
			}
		},

		save: function() {
            speedReader.local = {
				word: i,
				words: speedReader.config.$words.val(),
				wpm: $wpm.val(),
				autosave: autosave,
				zoom: zoom
			};
            localStorage.jqspritz = JSON.stringify(speedReader.local);
			if (!autosave) {
				speedReader.alert('saved');
			} else {
				//button_flash('save', 500);
			}			
		},
		
		// Text parseing
		wordsSet: function() {
            var $paragraph = speedReader.config.$words,
                $text = $paragraph.text();
            
            speedReader.words = splitter( $text );

            for (var i = 1; i < speedReader.words.length; i++) {
                speedReader.words[i] = speedReader.words[i].replace(/{linebreak}/g, '   ');
            }
		},

		// On each word
		wordShow: function(i) {
			speedReader.config.readingProgress.width(100*i/speedReader.words.length+'%');

			var word = speedReader.words[i];
            
            speedReader.config.space.html('<span>' + word + '</span>');
		},
		wordNext: function() {
			i++;
			speedReader.wordShow(i);
		},
		wordPrev: function() {
			i--;
			speedReader.wordShow(i);
		},

		// Iteration function
		wordUpdate: function() {
			speedReader.spritz = setInterval(function() {
				speedReader.wordNext();
				if ( i+1 === speedReader.words.length ) {
					setTimeout(function() {
						speedReader.config.space.html('');
						speedReader.pause(true);
						i = 0;
						speedReader.wordShow(0);
					}, interval);
					clearInterval(speedReader.spritz);
				}
			}, interval);
		},

		// Control functions
		pause: function( ns ) {
			if ( ! paused ) {
				clearInterval(speedReader.spritz);
				paused = true;

				speedReader.config.container.addClass('paused');
				if ( autosave && ! ns ) {
					speedReader.save();
				}
			}
		},
		play: function() {
			speedReader.wordUpdate();
			paused = false;
  			speedReader.config.container.removeClass('paused');
		},
		toggle: function() {
			if (paused) {
				speedReader.play();
			} else {
				speedReader.pause();
			}
		},

		// Speed functions
		speed: function() {
			interval = 60000/$wpm.val();
			if ( !paused ) {
				clearInterval(speedReader.spritz);
				speedReader.wordUpdate();
			}
			speedReader.config.save.removeClass('saved loaded');
		},
		slider: function() {
			$wpm.attr('value', $wpm.val());
			speedReader.output();
			speedReader.speed();
		},
		output: function() {
			speedReader.wpmOutput.value = $wpm.val() + ' words per minute.';
		},
		faster: function() {
			$wpm.val(parseInt($wpm.val())+50);
			speedReader.output();
			speedReader.speed();
		},
		slower: function() {
			if ( $wpm.val() >= 100 ) {
				$wpm.val(parseInt($wpm.val())-50);
				speedReader.output();
			}
			speedReader.speed();
		},

		// Jog functions
		back: function() {
			speedReader.pause();
			if (i >= 1) {
				speedReader.wordPrev();
			}
		},
		forward: function() {
			speedReader.pause();
			if (i < speedReader.words.length) {
				speedReader.wordNext();
			}
		},

		// Words functions
		zoom: function(c) {
			zoom = zoom+c;
			speedReader.config.reader.css('font-size', zoom+'em');
		},
		refresh: function() {
			clearInterval(speedReader.spritz);
			speedReader.wordsSet();
			i = 0;
			speedReader.pause();
			speedReader.wordShow(0);
		},
		select: function() {
			speedReader.config.$words.select();
		},
		expand: function() {
			speedReader.config.container.toggleClass('fullscreen');
		},

		// Autosave functions
		autosave: function() {
			speedReader.config.container.toggleClass('autosave');

			autosave = !autosave;

			if (autosave) {
				speedReader.config.autosave.prop('checked', true);
			} else {
				speedReader.config.autosave.prop('checked', false);
			}
		},

		// Alert functions
		alert: function(type) {
			var msg = '';
			
			switch (type) {
				case 'loaded':
					msg = 'Data loaded from local storage';
					break;
				case 'saved':
					msg = 'Words, Position and Settings have been saved in local storage for the next time you visit';
					break;
			}

			speedReader.config.alert.text(msg).fadeIn().delay(2000).fadeOut();
		}
	};
   
	var readerControls = {
		config: {
			controls:   $('.controls'),
			collapse:   $('.toggle'),
			jogBack:    '',
			jogForward: '',
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( readerControls.config, config );

			this.bindUIActions();
		},

		bindUIActions: function() {
			readerControls.config.controls.on('click', 'a, label', function(event) {
				event.preventDefault();
				readerControls.doClick(event, this);
			});

			readerControls.config.controls.on('input', 'input[type=range]', function(event) {
				readerControls.doInputChange(event, this);
			});

			readerControls.config.controls.on('mousedown', 'a', function(event) {
				event.preventDefault();
				readerControls.doMouseDown(event, this);
			});

			readerControls.config.controls.on('mouseup', 'a', function(event) {
				event.preventDefault();
				readerControls.doMouseUp(event, this);
			});
            
            $(document).on('keyup', function(event) {
                if (event.target.tagName.toLowerCase() !== 'body') {
                    return;
                }
                readerControls.doKeyUp(event, this);
            });
            
            $(document).on('keydown', function(event) {
                if (event.target.tagName.toLowerCase() !== 'body') {
                    return;
                }
                readerControls.doKeyDown(event, this);
            });
		},
        
		doClick: function(event, target) {
			switch (target.id) {
			case 'spritz_slower':
				speedReader.slower(); 
				break;
			case 'spritz_faster':
				speedReader.faster(); 
				break;
			case 'spritz_save':
				speedReader.save(); 
				break;
			case 'spritz_pause':
				speedReader.toggle(); 
				break;
			case 'spritz_smaller':
				speedReader.zoom(-0.1); 
				break;
			case 'spritz_bigger':
				speedReader.zoom(0.1); 
				break;
			case 'spritz_autosave':
				speedReader.autosave(); 
				break;
			case 'spritz_refresh':
				speedReader.refresh(); 
				break;
			case 'spritz_select':
				speedReader.select(); 
				break;
			case 'spritz_expand':
				speedReader.expand(); 
				break;
			}

			return false;
		},

		doInputChange: function(event, target) {
			switch (target.id) {
			case 'spritz_wpm':
				speedReader.slider();
				break;
			}
		},

		doMouseDown: function(event, target) {
			switch (target.id) {
			case 'spritz_back':
				readerControls.config.jogBack = setInterval(function() {
					speedReader.back();
				}, 100);
				break;
			case 'spritz_forward':
				readerControls.config.jogForward = setInterval(function() {
					speedReader.forward();
				}, 100);
				break;
			}
		},

		doMouseUp: function(event, target) {
			switch (target.id) {
			case 'spritz_back':
				clearInterval(readerControls.config.jogBack); 
				break;
			case 'spritz_forward':
				clearInterval(readerControls.config.jogForward); 
				break;
			}
		},
        
        doKeyUp: function(event) {
            switch (event.keyCode) {
            case 32:
                speedReader.toggle(); 
                //speedReader.buttonFlash('pause'); 
                break;
            case 37:
                speedReader.back(); 
                //button_flash('back');
                break;
            case 38:
                speedReader.faster(); 
                //button_flash('faster'); 
                break;
            case 39:
                speedReader.forward(); 
                //button_flash('forward'); 
                break;
            case 40:
                speedReader.slower(); 
                //button_flash('slower'); 
                break;
            }
        },
        
        doKeyDown: function(event) {
            switch (event.keyCode) {
            case 37:
                speedReader.back(); 
                //button_flash('back'); 
                break;
            case 39:
                speedReader.forward(); 
                //button_flash('forward'); 
                break;    
            }
        },
	};

	var themeToggle = {
		config: {
			container: $('body'),
			view:      $('[data-theme]'),
		},

		init: function( config ) {
            // merge config defaults with init config
			$.extend( themeToggle.config, config );
            
			this.bindUIActions();
		},

		bindUIActions: function() {
			themeToggle.config.view.on('click', function(e) {
				themeToggle.readerType(e, this);
			});
		},

		speedReader: function( bool ) {
			if ( bool ) {
				speedReader.config.reader.addClass('in');
				speedReader.play();
				speedReader.wpmOutput.value = $wpm.val() + ' words per minute.';
			} else {
				speedReader.config.reader.removeClass('in');
				speedReader.pause();
			}

			isSpeedRead = bool;
			stopScroll( bool, 'body' );
		},

		animate: function( bool ) {
			if ( bool ) {
				themeToggle.config.container.addClass('animated');
                fullPage.init();
			} else {
				themeToggle.config.container.removeClass('animated');
                $.fn.fullpage.destroy('all');
			}

			isAnimated = bool;
		},

		readerType: function(event, target) {
			switch (target.dataset.theme) {
				case 'speed':
					themeToggle.speedReader( true );
					themeToggle.animate( false );
					themeToggle.btnSelected(target);
					break;
				case 'normal':
					themeToggle.speedReader( false );
					themeToggle.animate( false );
					themeToggle.btnSelected(target);
					break;
				case 'animated':
					themeToggle.speedReader( false );
					themeToggle.animate( true );
					themeToggle.btnSelected(target);
					break;
			}
		},

		btnSelected: function(target) {
			$(themeToggle.config.view).attr('disabled', false).removeClass('active');
			$(target).attr('disabled', true).addClass('active');
		},
	};

	$(document).ready(function() {
		var $window = $(window);

		// Prepare to launch
		ot_smooth_state( $window );
		ot_page_load( $window );

		$window.resize(function () {
			waitForFinalEvent(function () {
				ot_resize();
			}, 
				timeToWaitForLast, 
				"screenz resize"
			);
		});
	});

})( jQuery );