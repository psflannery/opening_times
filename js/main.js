/* globals IntersectionObserver, jQuery */

( function ( $ ) {
	// Global Definitions
	var breakpoints = {
			screen_xl: 1200,
			screen_lg: 992,
			screen_md: 768,
			screen_sm: 576,
			screen_xs: 480,
		},
		type,
		page = 1,
		isSidebarOpen = false,
		paused = false,
		i = 0,
		isSpeedRead = false,
		isAnimated = true,
		isNormal = false,
		smoothState,
		$site = $('html, body'),
		$body = $('body'),
		$page = $('#page');

	// Module execution controller
	$.readyFn = {
		list: [],
		register: function(fn) {
			$.readyFn.list.push(fn);
		},
		execute: function() {
			for (var i = 0; i < $.readyFn.list.length; i++) {
				try {
					$.readyFn.list[i].apply(document, [$]);
				} catch (e) {
					throw e;
				}
			}
		}
	};

	// Detect Mobile Browser
	var mobilecheck = function() {
		var check = false;

		(function(a){if(/(android|ipad|playbook|silk|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))){check = true;}})(navigator.userAgent||navigator.vendor||window.opera);

		return check;
	};

	var parseVideo = function( url ) {
		// http://stackoverflow.com/questions/5612602/improving-regex-for-parsing-youtube-vimeo-urls
		// - Supported YouTube URL formats:
		//   - http://www.youtube.com/watch?v=My2FRPA3Gf8
		//   - http://youtu.be/My2FRPA3Gf8
		//   - https://youtube.googleapis.com/v/My2FRPA3Gf8
		// - Supported Vimeo URL formats:
		//   - http://vimeo.com/25451551
		//   - http://player.vimeo.com/video/25451551
		// - Also supports relative URLs:
		//  

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
	};

	var parseMedia = function( url ) {
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
	};

	// Calculate if screen size is smaller/larger than default breakpoint
	var screenLessThan = function( breakpoint ) {
		// Get the width of the current window
		var windowWidth = $( window ).width();

		// Return true/false if window with is equal or smaller than breakpoint
		return ( parseInt( windowWidth ) <= parseInt( breakpoint ) );
	};

	// Toggle Scrolling
	var stopScroll = function( bool, el ) {
		var $el = $(el);

		if ( bool ) {
			$el.addClass('stop-scroll');
		} else {
			$el.removeClass('stop-scroll');
		}
	};

	// Gradient Text
	var makeGradients = function( selector, h, s, l ) {
		var $gradient = $('.gradient-container');

		$gradient.gradienter({
			hueStart: h, 
			selector: selector, 
			saturation: s, 
			lightness: l
		});
	};

	// Form validation
	var formValidate = {
		init: function () {
			var $form = $('#mailing-list-subscribe'),
				$mail = $('#field-ot-mail'),
				$confirmMail = $('#field-ot-mail-confirm');

			this.validate( $form, $mail, $confirmMail );
		},

		// Validate Forms
		validate: function( el, mail, confirm ) {
			var form = el[0];

			form.addEventListener( 'submit', function( e ) {
				if ( form.checkValidity() === false ) {
					e.preventDefault();
					e.stopPropagation();
				}

				$(el).addClass('was-validated');

				formValidate.match( mail, confirm );
			}, false);
		},

		// Check form values match
		match: function( val, confirm ) {
			var value1 = val[0].value,
				value2 = confirm[0].value,
				$feedback = confirm.next('.invalid-feedback');

			if ( value1 !== value2 ) {
				$feedback.text( $feedback.data('text-original') );
			} else {
				$feedback.data('text-original', $feedback.text());
				$feedback.text( $feedback.data('text-swap') );
			}
		},
	};

	var searchBar = {
		config: {
			$searchWrap: $('.expanding-search'), //
			$expand: $('[data-toggle="search-expand"]'),
			$input: $('.expanding-search .search-field'), //
			$submit: $('.expanding-search .search-submit'),
			$menu: $('.navigation-social a'), //
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( searchBar.config, config );

			var $expand = this.config.$expand,
				$submit = this.config.$submit;

			this.bindUIActions( $expand, $submit );
		},

		bindUIActions: function( $expand, $submit ) {
			$expand.on('click', function( event ) {
				searchBar.doExpand( event );
			});
			$submit.on('click', function( event ) {
				searchBar.doExpand( event );
			});
		},

		doExpand: function( event ) {
			event.stopPropagation();

			if ( !searchBar.config.$searchWrap.is('.in') ) {
				searchBar.open(event);
			} else if ( searchBar.config.$searchWrap.is('.in') && /^\s*$/.test(searchBar.config.$input.val()) ) {
				event.preventDefault();
				searchBar.close();
			}
		},

		open: function( event ) {
			event.preventDefault();
			searchBar.config.$searchWrap.addClass('in');
			searchBar.config.$input.focus();
			searchBar.config.$menu.addClass('invisible');

			$(document).on('click', function() {
				searchBar.bodyFn(event);
			});
		},

		close: function() {
			searchBar.config.$searchWrap.removeClass('in');
			searchBar.config.$input.blur();
			searchBar.config.$menu.removeClass('invisible');
		},

		bodyFn: function(event) {
			if( $(event.target).is(searchBar.config.$input) ) {
				return;
			}

			searchBar.close();

			$(document).off('click', function() {
				searchBar.bodyFn(event);
			});
		},
	};
	
	var doSmoothState = {
		config: {
			prefetch: true,
			prefetchOn: 'mouseover touchstart',
			//cacheLength: 2,
			blacklist: '.post-edit-link',
			debug: true,
			onBefore: function( $currentTarget, $container ) {
				infiniteScroll.config.$container.infiniteScroll('destroy');
				otPopover.config.$popMedia.popover('dispose');
				if ( $(scrollSnap.config.section).length ) {
					$.scrollify.move(0);
					$.scrollify.destroy();
				}
			},
			onStart: {
				duration: 1000,
				render: function () {
					$page.addClass('is-exiting');
					$site.animate({scrollTop: 0});
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
				//$('#overlay').remove();
				// reset the page counter
				page = 1;

				$.readyFn.execute();

				// Ensure speed reader props are
				if ( $(speedReader.config.reader).length ) {
					speedReader.refresh();
				}
			},
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( doSmoothState.config, config );

			if ( ! $page.length ) {
				return;
			}

			smoothState = $page.smoothState(doSmoothState.config).data('smoothState');
		}
	};

	// Init Lazy Load
	var images,
		config = {
			// If the image gets within 200px in the Y axis, start the download.
			rootMargin: '200px 0px',
			threshold: 0.01
		},
		imageCount = 0,
		observer,
		image;
		//i;

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

	// Load all of the images immediately
	// @param {NodeListOf<Element>} immediateImages List of lazy-loaded images to load immediately.
	function loadImagesImmediately( immediateImages ) {
		var i;

		// foreach() is not supported in IE
		for ( i = 0; i < immediateImages.length; i++ ) {
			var image = immediateImages[ i ];
			lazy_load_media( image );
		}
	}

	// On intersection
	// @param {array} entries List of elements being observed.
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

	// Art directed popovers
	var otPopover = {
        config: {
            $pop: $('.popover'),
            $popMedia: $('.media-sample'),
            $scene: $('#scene'),
        },

        init: function( config ) {
            // merge config defaults with init config
            $.extend( otPopover.config, config );
            
            $('[data-toggle="popover"]').popover();
            
            this.bindUIActions();
            this.doMedia();
        },
        
        bindUIActions: function() {
            otPopover.config.$scene.on('shown.bs.popover', function () {
                otPopover.doVolumeToggle( $('.accordion .show'), 0.1 );
            });
            
            otPopover.config.$scene.on('hidden.bs.popover', function () {
                otPopover.doVolumeToggle( $('.accordion .show'), 0.9 );

                /*
				$scene.on('hidden.bs.popover', function () {
					raiseVolume( $('.accordion .show'), 0.9 );
				});
                */
            });
        },
        
        doVolumeToggle: function( el, vol ) {
            
            /*
            raiseVolume( $('.accordion .show'), 0.9 );
            */
            /*
            var $popoverLg = $('.popover--large');

            if( $popoverLg.length ) {
                lowerVolume( $('.accordion .show'), 0.1 );
            }
            */
        },
        
        doMedia: function() {            
            otPopover.config.$popMedia.each(function(i, el) {
                var $el = $(el),
                    mediaSrc = $el.data('media'),
                    placementType = $el.data('position'),
                    triggerType = $el.closest('.accordion-header').length ? 'hover' : 'click',
                    mediaType;
                    
                parseMedia(mediaSrc);
                
                switch (type) {
                case 'audio':
                    mediaType = '<audio src="' + mediaSrc + '" loop autoplay controls controlsList="nodownload"></audio>';
                    break;
                case 'video':
                    mediaType = '<video src="' + mediaSrc + '" loop autoplay></video>';
                    break;
                case 'image':
                    mediaType = '<img src="' + mediaSrc + '">';
                    break;
                }
                
                $el.popover({
                    placement: placementType,
                    html: true,
                    template: '<div class="popover popover--large" role="tooltip"><div class="popover-content"><div class="popover__media-container"></div></div></div>',
                    content: mediaType,
                    trigger: triggerType,
                });
            });
        },

        hide: function( el ) {
            if( otPopover.config.$popMedia.length ) {
                var $el = $(el);

                $el.find(otPopover.config.$popMedia).popover('hide');
            }
        },
    };

	// News Carousel
	var carousel = {
		config: {
			options: {
				cellSelector: '.carousel-cell',
				cellAlign: 'left',
				prevNextButtons: false,
				pageDots: false,
				watchCSS: true,
			},
			$newsDropdown: $('#collapse-news'),
			$carousel: $('.carousel'),
			$btnNav: $('.btn-nav'),
			$btnPrev: $('.btn-prev'),
			$btnNext: $('.btn-next'),
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( carousel.config, config );

			var $carousel = carousel.config.$carousel;

			if( ! $carousel ) {
				return;
			}

			this.bindUIActions( $carousel );
		},

		create: function( $carousel ) {
            var _carousel = $carousel.flickity(carousel.config.options);

            carousel.bindEvents( _carousel );
        },

        bindUIActions: function( $carousel ) {
            carousel.config.$newsDropdown.one('shown.bs.collapse', function () {
                carousel.create( $carousel );
                $carousel.flickity('resize');
            });
            
            carousel.config.$newsDropdown.on('hidden.bs.collapse', function () {
                carousel.resetPosition( $carousel );
            });
            
            // Go to previous cell
            carousel.config.$btnPrev.on( 'click', function() {
                $carousel.flickity('previous');
            });

            // Go to next cell
            carousel.config.$btnNext.on( 'click', function() {
                $carousel.flickity('next');
            });
        },

        bindEvents: function( $carousel ) {
            var flkty = $carousel.data('flickity');
            
            carousel.btnAtts( flkty, carousel.config.$btnPrev, carousel.config.$btnNext, carousel.config.$btnNav );
            carousel.addItems( carousel.config.$carousel, flkty );
        },

        btnAtts: function( $carousel, $prev, $next, $nav ) {
            $carousel.on( 'cellSelect', function() {
                var target = $carousel.selectedCell.target,
                    isCarouselEnd = false;
                    
                if ( target === $carousel.cells[0].target ) {
                    isCarouselEnd = !isCarouselEnd;

                    $prev.attr('disabled', true);
                } else if ( target === $carousel.getLastCell().target ) {
                    isCarouselEnd = !isCarouselEnd;

                    $next.attr('disabled', true);
                } else {
                    isCarouselEnd = isCarouselEnd;

                    $nav.removeAttr('disabled');
                }
            });
        },

       addItems: function( $carousel, el ) {
            $carousel.on( 'settle.flickity', function() {
                if ( el.selectedIndex === el.cells.length - 1 ) {
                    carousel.makeCellHtml( $carousel );
                }
            });
        },

        makeCellHtml: function( $carousel ) {
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

                    $carousel.flickity( 'append', $cell );
                });
            }).fail(function( jqxhr, textStatus, error ) {
                var err = textStatus + ', ' + error;
                console.log( 'Request Failed: ' + err );
            });
        },

        resetPosition: function( $carousel ) {
            $carousel.flickity( 'select', 0, false, true );
        },
    };

    // Infinite Scroll
	var infiniteScroll = {
		config: {
			$container: $('.infinite'),
			options: {
				path: '.nav-previous a',
				append: '.post',
				prefill: true,
				hideNav: '.nav-links',
				history: false
			},
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( infiniteScroll.config, config );

			var $infScroll = infiniteScroll.config.$container;
			
			if( ! $infScroll.length ) {
				return;
			}

			this.create( $infScroll );
			this.bindEvents( $infScroll );
		},

		create: function( el ) {
			el.infiniteScroll(infiniteScroll.config.options);  
		},

		bindEvents: function( el ) {
			el.on('append.infiniteScroll', function() {
				makeGradients( '.gradient-text', 240, 100, 50 );
				lazy_load_init();
				
				accordion.config.$collapse.on('hidden.bs.collapse', function () {
					mediaControls.doPause( this ); // TODO - check this
				});
			});
		},
	};

	// Accordion functions
    var accordion = {
        config: {
            $collapse: $('.accordion .collapse'),
            $panelClose: $('.site-info .close'),
            card: '.card',
        },

        init: function( config ) {
            // merge config defaults with init config
            $.extend( this.config, config );
            
            if( !( accordion.config.$collapse.length || accordion.config.$panelClose.length ) ) {
                return;
            }
            
            this.bindUIActions( accordion.config.$collapse, accordion.config.$panelClose );
        },
        
        bindUIActions: function( $accordion, $panelClose ) {
            $accordion.on('show.bs.collapse', function () {
                accordion.toggle( true, this );
            });
            
            $accordion.on('shown.bs.collapse', function () {                    
                accordion.doLazyLoad( this );
                mediaControls.doPlay( this, 0.2 );
            });
            
            $accordion.on('hide.bs.collapse', function () {
                accordion.toggle( false, this );
                otPopover.hide( this );
            });
            
            $accordion.on('hidden.bs.collapse', function () {
                mediaControls.doPause( this );
            });

			// Close the info panels
			$panelClose.on('click', function() {
				$(this).closest('.collapse').collapse('hide');
			});
        },

        doLazyLoad: function( el ) {
            var $this = $(el);
            
            if( $this.has('[data-lazy-src]').length ) {
                var $el = $this.find('[data-lazy-src]');

                $el.each(function() {
                    lazy_load_media( this );
                });
            }
        },
        
        toggle: function( bool, el ) {
            var $this = $(el);
            
            if( bool ) {
                $this.parent(accordion.config.card).addClass('show');
            } else {
                $this.parent(accordion.config.card).removeClass('show');
            }
        },
    };

    // Media Controls
    var mediaControls = {
		config: {
			volFadeDuration: 1000,
			$autoPlay: $('.autoplay'),
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( mediaControls.config, config );

			this.setAutoPlay( mediaControls.config.$autoPlay );
		},

		setAutoPlay: function( $autoPlay ) {
			var $iframe = $autoPlay.find('iframe');

			$iframe.attr('data-autoplay', '');

			if( mobilecheck() && $iframe.length ) {
				mediaControls.mobileParams( $iframe );
			}
		},

		mobileParams: function( $iframe ) {
			$iframe.each(function (i, video) {
				var src = video.attr('src');
				// determine embed type
				parseVideo( src );

				if ( type === 'vimeo' ) {
					video.attr('src', src.replace('background=1', 'background=0'));
				}
			});
		},

		doPlay: function( panel, vol ) {
            mediaControls.mediaToggle( true, panel, vol );
            mediaControls.embedToggle( true, panel );
        },
        
        doPause: function( panel ) {
            mediaControls.mediaToggle( false, panel );
            mediaControls.embedToggle( false, panel );
        },

        mediaToggle: function( bool, panel, vol ) {
            var media = $(panel).find('video, audio');
            
            if( $(media).length ) {
                media.each(function(i, el) {
                    var $el = $(el),
                        element = $el.get(0);
                        
                    if( bool ) {
                        mediaControls.mediaPlay( $el, element, vol );
                    } else {
                        mediaControls.mediaPause( $el, element );
                    }
                });
            }
        },

        mediaPlay: function( $el, element, vol ) {
            if( element.hasAttribute('data-autoplay') && typeof element.play === 'function' ) {
                // Set the volume
                $el.prop('volume', vol);
                
                if(element.volume === 0) {
                    element.play().then(function() {
                        $el.animate({volume: vol}, mediaControls.config.volFadeDuration * 2);
                    });
                } else {
                    element.play();
                }
            }
        },

        mediaPause: function( $el, element ){
            if( ! element.hasAttribute('data-keepplaying') && typeof element.pause === 'function' ) {
                $el.animate({volume: 0}, mediaControls.config.volFadeDuration, function () {
                    element.pause();
                });
            }
        },

        embedToggle: function( bool, panel ) {
            var iframe = $(panel).find('iframe'),
                vid = iframe.is(['data-lazy-src']) ? iframe.data('lazy-src') : iframe.attr('src');
                
            if( $(iframe).length ) {
                iframe.each(function() {
                    // determine embed type
                    parseVideo( vid );
                    
                    var video = iframe.get(0);
                    
                    if( bool ) {
                        mediaControls.embedPlay( type, video );
                    } else {
                        mediaControls.embedPause( type, video );
                    }

                });
            }
        },

        embedPlay: function( type, video ) {
            if ( type === 'vimeo' && video.hasAttribute('data-autoplay') ) {
                video.contentWindow.postMessage('{"method": "play"}', '*');
            }

            if ( type === 'youtube' && video.hasAttribute('data-autoplay') ) {
                video.contentWindow.postMessage('{"event": "command", "func": "playVideo", "args": ""}', '*');
            }
        },

		embedPause: function( type, video ) {
			if ( type === 'vimeo' && ! video.hasAttribute('data-keepplaying') ) {
				video.contentWindow.postMessage('{"method": "pause"}', '*');
			}

			if ( type === 'youtube' && ! video.hasAttribute('data-keepplaying') ) {
				video.contentWindow.postMessage('{"event": "command", "func": "pauseVideo", "args": ""}', '*');
			}
		},
    };
	
    var splitter = function( el ) {
        var splitWord = el.trim()
        	.replace(/([-—])(\w)/g, '$1 $2')
			.replace(/[\r\n]/g, ' {linebreak} ')
			.replace(/[ \t]{2,}/g, ' ')
			.split(' ');

		return splitWord;
    };
    
    var scrollSnap = {
        config: {
            anchor: true,
            section: '.scroll-snap .section',
            sectionName : 'anchor',
            interstitial: '.site-header, .issue-content__bio, .site-footer, .spritz',
            setHeights: false,
            activeClass: 'active',
        },
        
        init: function( config ) {
            // merge config defaults with init config
			$.extend( this.config, config );

			if ( ! $(this.config.section).length ) {
				return;
			}

            this.setAnchor(this.config.anchor);
           
			$.scrollify({
				section: this.config.section,
				sectionName: this.config.sectionName,
				interstitialSection: this.config.interstitial,
				before: function( i, panels ) {
					scrollSnap.doBefore( i, panels, scrollSnap.config.section, scrollSnap.config.activeClass );
					scrollSnap.doAfter( i, panels, scrollSnap.config.activeClass );
				},
			});

			this.doUpdate();
        },

        doBefore: function( i, panels, section, activeClass ) {
        	var ref = panels[i].attr('data-anchor');

        	if ( typeof ref !== 'undefined' ) {
        		$(section).removeClass(activeClass);
        	}
        },

        doAfter: function( i, panels, activeClass ) {
			var $current = $.scrollify.current();

			$current.addClass(activeClass);
        },

        doUpdate: function() {
			$('.site-info .collapse').on('shown.bs.collapse', function () {
				$.scrollify.update();
			});

			$('.site-info .collapse').on('hidden.bs.collapse', function () {
				$.scrollify.update();
			});
        },
        
        setAnchor: function( bool ) {
        	if ( ! bool ) {
        		return;
        	}

            $(scrollSnap.config.section).each(function( i, page ){
                var $page = $(page),
                	num = i + 1;
                
                $page.attr( 'data-anchor', 'page-' + num );
            });
        }
    };
	
	var splitText = {
		config: {
			words: '',
			$words: $('[data-text="split"] .entry-content > p'),
			paragraphSm: 16,
			paragraphMd: 35,
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( this.config, config );
    		
			if ( ! $(this.config.$words).length ) {
				return;
			}
			
			splitText.split(this.config.$words, this.config.words, ' ');
		},

		// Text parseing
		split: function( $words, words, after ) {    
			$words.each(function(i, paragraph) {
				var $paragraph = $(paragraph),
					$text = $paragraph.text(),
					wrappedWords = ''; 
                    
                words = splitter( $text );

                splitText.count($paragraph, words, splitText.config.paragraphSm, splitText.config.paragraphMd, i);

				$(words).each(function(i, word) {
					wrappedWords += '<span class="word-' + i + '" aria-hidden="true">' + word + '</span>' + after;
				});

                splitText.populate( $paragraph, $text, '<div>' + wrappedWords + '</div>');
			});
		},
		count: function( $paragraph, words, sm, md, i ) {
			if ( words.length < parseInt(sm) ) {
				$paragraph.addClass('section section-sm section-' + i);
			} else if ( words.length < parseInt(md) ) {
				$paragraph.addClass('section section-md section-' + i);
			} else {
				$paragraph.addClass('section section-lg section-' + i);
			}
		},
		populate: function( $paragraph, $text, wrappedWords ) {
			$paragraph.attr('aria-label', $text).empty().append(wrappedWords);
		}
	};
 
	var speedReader = {
		config: {
			container:       $body,
			readingProgress: $('#spritz_progress'),
			reader:          $('#spritz'),
			space:           $('#spritz_word'),
			$words:          $('[data-text="split"] .entry-content > p'),
			spritz:          '',
			words:           '',
			local:           {},
			$wpm:            $('#spritz_wpm'),
			interval:        60000/$('#spritz_wpm').val(),  
			wpmOutput:       $('#spritz_wpm').next()[0],
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( speedReader.config, config );

			if ( ! $(speedReader.config.reader).length ) {
				return;
			}

			speedReader.wordsSet();
			speedReader.wordShow(0);
			speedReader.wordUpdate();
			speedReader.pause(true);
		},
		
		// Text parseing
		wordsSet: function() {
            var $paragraph = speedReader.config.$words,
                $text = $paragraph.text();
            
            speedReader.config.words = splitter( $text );

            for (var i = 1; i < speedReader.config.words.length; i++) {
                speedReader.config.words[i] = speedReader.config.words[i].replace(/{linebreak}/g, '   ');
            }
		},

		// On each word
		wordShow: function(i) {
			speedReader.config.readingProgress.width(100*i/speedReader.config.words.length+'%');

			var word = speedReader.config.words[i];
            
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
			speedReader.config.spritz = setInterval(function() {
				speedReader.wordNext();
				if ( i+1 === speedReader.config.words.length ) {
					setTimeout(function() {
						speedReader.config.space.html('');
						speedReader.pause(true);
						i = 0;
						speedReader.wordShow(0);
					}, speedReader.config.interval);
					clearInterval(speedReader.config.spritz);
				}
			}, speedReader.config.interval);
		},

		// Control functions
		pause: function() {
			if ( ! paused ) {
				clearInterval(speedReader.config.spritz);
				paused = true;

				speedReader.config.container.addClass('paused');
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
			speedReader.config.interval = 60000/speedReader.config.$wpm.val();
			if ( !paused ) {
				clearInterval(speedReader.config.spritz);
				speedReader.wordUpdate();
			}
		},
		slider: function() {
			speedReader.config.$wpm.attr('value', speedReader.config.$wpm.val());
			speedReader.output();
			speedReader.speed();
		},
		output: function() {
			speedReader.config.wpmOutput.value = speedReader.config.$wpm.val() + ' words per minute.';
		},
		refresh: function() {
			clearInterval(speedReader.config.spritz);
			speedReader.wordsSet();
			i = 0;
			speedReader.pause();
			speedReader.wordShow(0);
		},
	};
   
	var readerControls = {
		config: {
			controls: $('.controls'),
		},

		init: function( config ) {
			// merge config defaults with init config
			$.extend( readerControls.config, config );

			this.bindUIActions( readerControls.config.controls );
		},

		bindUIActions: function( $controls ) {
			$controls.on('click', '#spritz_pause', function(event) {
				event.preventDefault();
				speedReader.toggle(); 
			});

			$controls.on('input', 'input[type=range]', function() {
				speedReader.slider();
			});
		},
	};

	var themeToggle = {
		config: {
			container: $body,
			btnSelect: $('[data-theme]'),
			content:   $('[data-toggle="theme"] .entry-content'),
			switch:    $('[data-toggle="theme"]'),
		},

		init: function( config ) {
            // merge config defaults with init config
			$.extend( themeToggle.config, config );

			if( ! themeToggle.config.switch ) {
				return;
			}
            
			this.bindUIActions( themeToggle.config.btnSelect );
		},

		bindUIActions: function( btnSelect ) {
			btnSelect.on('click', function(e) {
				themeToggle.readerType(e, this);
			});
		},

		speedReader: function( bool ) {
			if ( bool ) {
				speedReader.config.reader.addClass('in');
				speedReader.play();
				speedReader.config.wpmOutput.value = speedReader.config.$wpm.val() + ' words per minute.';
			} else {
				speedReader.config.reader.removeClass('in'); 
				speedReader.pause();
			}

			isSpeedRead = bool;
			stopScroll( bool, 'body' );
		},

		slides: function( bool ) {
			if ( bool ) {
				var slide = window.location.hash.split('-')[1];

				themeToggle.config.container.addClass('animated');
                scrollSnap.init();
                $.scrollify.instantMove(parseInt(slide));
			} else {
				themeToggle.config.container.removeClass('animated');
				$.scrollify.destroy();
			}

			isAnimated = bool;
		},

		normal: function( bool ) {
			if ( bool ) {
				themeToggle.config.content.removeClass('col-md-12');
				themeToggle.config.content.addClass('col-md-8');
				themeToggle.config.switch.removeClass('text-size--poster format-slides');
				$('html, body').scrollTop( 0 );
			} else {
				themeToggle.config.content.removeClass('col-md-8');
				themeToggle.config.content.addClass('col-md-12');
				themeToggle.config.switch.addClass('text-size--poster format-slides');
			}

			isNormal = bool;
		},

		readerType: function(event, target) {
			switch (target.dataset.theme) {
				case 'speed':
					themeToggle.speedReader( true );
					themeToggle.normal( false );
					themeToggle.slides( false );
					themeToggle.btnSelected(target);
					break;
				case 'normal':
					themeToggle.speedReader( false );
					themeToggle.normal( true );
					themeToggle.slides( false );
					themeToggle.btnSelected(target);
					break;
				case 'animated':
					themeToggle.speedReader( false );
					themeToggle.normal( false );
					themeToggle.slides( true );
					themeToggle.btnSelected(target);
					break;
			}
		},

		btnSelected: function(target) {
			$(themeToggle.config.btnSelect).attr('disabled', false).removeClass('active');
			$(target).attr('disabled', true).addClass('active');
		},
	};

	$.readyFn.register(function() {
		// Compoents
		carousel.init({
			$newsDropdown: $('#collapse-news'),
			$carousel:     $('.carousel'),
			$btnNav:       $('.btn-nav'),
			$btnPrev:      $('.btn-prev'),
			$btnNext:      $('.btn-next'),
		});

		infiniteScroll.init({
			$container: $('.infinite'),
		});

		otPopover.init({
            $pop:      $('.popover'),
            $popMedia: $('.media-sample'),
            $scene:    $('#scene'),
        });

        accordion.init({
            $collapse:   $('.accordion .collapse'),
            $panelClose: $('.site-info .close'),
            card:        '.card',
        });

        mediaControls.init({
			volFadeDuration: 1000,
			$autoPlay: $('.autoplay'),
		});

        // Custom
        splitText.init({
			words: '',
			$words: $('[data-text="split"] .entry-content > p'),
		});

		speedReader.init({
			container:       $('body'),
			readingProgress: $('#spritz_progress'),
			reader:          $('#spritz'),
			space:           $('#spritz_word'),
			$words:          $('[data-text="split"] .entry-content > p'),
			spritz:          '',
			words:           '',
			local:           {},
			$wpm:            $('#spritz_wpm'),
			interval:        60000/$('#spritz_wpm').val(),  
			wpmOutput:       $('#spritz_wpm').next()[0],
		});

		readerControls.init({
			controls: $('.controls'),
		});

		scrollSnap.init();
        
		// Utils
		lazy_load_init();
		makeGradients( '.gradient-text', 240, 100, 50 );
		formValidate.init();

		themeToggle.init({
			btnSelect: $('[data-theme]'),
			content:   $('[data-toggle="theme"] .entry-content'),
			switch:    $('[data-toggle="theme"]'),
		});

		searchBar.init({
			$searchWrap: $('.expanding-search'),
			$expand:     $('[data-toggle="search-expand"]'),
			$input:      $('.expanding-search .search-field'),
			$submit:     $('.expanding-search .search-submit'),
			$menu:       $('.navigation-social a'),
		});
	});

	// You Ready for this?
	$(document).ready(function() {
		doSmoothState.init();
		$.readyFn.execute();
	});

})( jQuery );
