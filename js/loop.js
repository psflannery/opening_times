( function( $, Backbone, _, settings, undefined ) {
	'use strict';

	var document = window.document,
		$postContainer = $( '.infinite' ),
		dateTemplate = $( '#date-template' )[0],
		contentTemplate = $( '#content-template' )[0];

	// Abort completely if we don't have this stuff
	if ( ! $postContainer || ! contentTemplate ) {
		return false;
	}

	var postTemplate = _.template( contentTemplate.innerHTML );

	var origURL = window.location.href,
		offset = 1,
		page = 1,
		timer;

	var posts = new wp.api.collections.Posts(),
		options = {
			data: {
				page: settings.page || 2,
				_embed: ''
			}
		};

	if ( 'archive' === settings.loopType ) {
		options.data.filter = {};
		options.data.filter[settings.taxonomy['query_var']] = settings.queriedObject.slug;

	} else if ( 'search' === settings.loopType ) {
		options.data.filter = {
			s: settings.searchQuery
		};
	} else if ( 'author' === settings.loopType ) {
		options.data.filter = {
			author: settings.queriedObject.data.ID
		};
	}

	/**
	 * Update current url using HTML5 history API
	 *
	 * @param {Number} pageNum
	 */
	function updateURL( pageNum ) {
		var offset = offset > 0 ? offset - 1 : 0,
			pageSlug = ( -1 === pageNum ) ? origURL : window.location.protocol + '//' + settings.pathInfo.host + settings.pathInfo.path.replace( /%d/, pageNum + offset ) + settings.pathInfo.parameters;

		if ( window.location.href !== pageSlug ) {
			history.pushState( null, null, pageSlug );
		}
	}

	/**
	 * Determine URL for pushing new history. Props to Automattic's Jetpack plugin
	 * for much of this code.
	 */
	function determineURL() {
		var windowTop = $( window ).scrollTop(),
			windowBottom = windowTop + $( window ).height(),
			windowSize = $( window ).height(),
			setsInView = [],
			pageNum = false;

		$postContainer.find( '.post-set' ).each( function() {
			var $currentSet = $( this ),
				setTop = $currentSet.offset().top,
				setHeight = $currentSet.outerHeight( false ),
				setBottom = setTop + setHeight,
				setPageNum = parseInt( $currentSet.attr( 'data-page-num' ) );

			if ( 0 === setHeight ) {
				$( '> *', this ).each( function() {
					setHeight += $currentSet.outerHeight( false );
				});
			}

			// top of set is above window, bottom is below
			if ( setTop < windowTop && setBottom > windowBottom ) {
				setsInView.push( { 'id': $currentSet.attr( 'id' ), 'top': setTop, 'bottom': setBottom, 'pageNum': setPageNum } );
			}
			// top of set is between top (gt) and bottom (lt)
			else if ( setTop > windowTop && setTop < windowBottom ) {
				setsInView.push( { 'id': $currentSet.attr( 'id' ), 'top': setTop, 'bottom': setBottom, 'pageNum': setPageNum } );
			}
			// bottom of set is between top (gt) and bottom (lt)
			else if ( setBottom > windowTop && setBottom < windowBottom ) {
				setsInView.push( { 'id': $currentSet.attr( 'id' ), 'top': setTop, 'bottom': setBottom, 'pageNum': setPageNum } );
			}

		});

		// Parse number of sets found in view in an attempt to update the URL to match the set that comprises the majority of the window.
		if ( 0 === setsInView.length ) {
			pageNum = -1;
		} 
		else if ( 1 === setsInView.length ) {
			var setData = setsInView.pop();

			// If the first set of IS posts is in the same view as the posts loaded in the template by WordPress, determine how much of the view is comprised of IS-loaded posts
			if ( ( ( windowBottom - setData.top ) / windowSize ) < 0.5 ) {
				pageNum = -1;
			} else {
				pageNum = setData.pageNum;
			}
		} 
		else {
			var majorityPercentageInView = 0;

			// Identify the IS set that comprises the majority of the current window and set the URL to it.
			$.each( setsInView, function( i, setData ) {
				var topInView = 0,
					bottomInView = 0,
					percentOfView = 0;

				// Figure percentage of view the current set represents
				if ( setData.top > windowTop && setData.top < windowBottom ) {
					topInView = ( windowBottom - setData.top ) / windowSize;
				}

				if ( setData.bottom > windowTop && setData.bottom < windowBottom ) {
					bottomInView = ( setData.bottom - windowTop ) / windowSize;
				}

				// Figure out largest percentage of view for current set
				if ( topInView >= bottomInView ) {
					percentOfView = topInView;
				} 
				else if ( bottomInView >= topInView ) {
					percentOfView = bottomInView;
				}

				// Does current set's percentage of view supplant the largest previously-found set?
				if ( percentOfView > majorityPercentageInView ) {
					pageNum = setData.pageNum;
					majorityPercentageInView = percentOfView;
				}
			} );
		}

		// We do this last check in case something bad happened
		if ( 'number' === typeof pageNum ) {
			updateURL( pageNum );
		}
	}

	/**
	 * Setup scroll listeners for changing history
	 */
	function setupScrollListener() {
		$( window ).on( 'scroll', function() {

			clearTimeout( timer );
			timer = setTimeout( determineURL , 100 );
		});
	}

	/**
	 * Grab more posts if more button is clicked and append them to loop
	function setupMoreListener() {
		$postContainer.on( 'click', '.more-button', function( event ) {
			event.preventDefault();

			$moreButton.hide();

			var $setContainer = $( '<div data-page-num="' + posts.state.currentPage + '" class="post-set"></div>' );
			
			posts.each( function( model ) {
				$setContainer.append( postTemplate( { post: model.attributes, settings: settings } ) );
			});

			$postContainer.append( $setContainer );

			if ( posts.hasMore() ) {
				posts.more().done( function() {
					$moreButton.appendTo( $postContainer).show();
				} );
			}
		});
	}
	*/

	function setupMoreListener() {
		var $loadMore = $('.site-main > div');

		$loadMore.append( '<span class="load-more"></span>' );

		var button = $('.load-more'),
			loading = false,
			scrollHandling = {
				allow: true,
				reallow: function() {
					scrollHandling.allow = true;
				},
				delay: 400
			};

		$(window).scroll(function(){
			if ( ! loading && scrollHandling.allow ) {
				scrollHandling.allow = false;
				setTimeout(scrollHandling.reallow, scrollHandling.delay);

				var offset = $(button).offset().top - $(window).scrollTop();

				if( 2000 > offset ) {
					loading = true;
					//console.log(posts);
					//console.log(options);

					var $setContainer = $( '<div data-page-num="' + posts.state.currentPage + '" class="post-set"></div>' );
					posts.each( function( model ) {
						$setContainer.append( postTemplate( { post: model.attributes, settings: settings } ) );
					});

					$postContainer.append( $setContainer );
				}
			}
		});
	}

	/**
	 * Initial posts fetch
	 */
	posts.fetch( options ).done( function() {
		if ( posts.length > 0 ) {
			//$postContainer.append( $moreButton );

			setupMoreListener();
			setupScrollListener();
		}
	});

})( jQuery, Backbone, _, settings );