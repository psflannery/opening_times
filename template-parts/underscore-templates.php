<?php
/**
 * @package Opening Times
 * 
 * Template part for displaying dynamically added posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 * @link http://localhost/wordpress/otdac/wp-json/wp/v2/posts/
 * @link https://github.com/tlovett1/_s_backbone
 *
 * @link https://github.com/WP-API/WP-API/issues/1558
 * @link https://1fix.io/blog/2015/06/26/adding-fields-wp-rest-api/
 *
 * @link( https://stackoverflow.com/questions/3452546/how-do-i-get-the-youtube-video-id-from-a-url, link)
 * @link( https://stackoverflow.com/questions/21607808/convert-a-youtube-video-url-to-embed-code, link)
 *
 * @link https://gist.github.com/harisrozak/a34fce1899ae9458d700
 *
 * http://2ality.com/2012/06/underscore-templates.html
 *
 * @package Opening Times
 */
?>

<script type="text/html" id="content-template">
	<article id="post-<%= post.id %>" class="post hentry panel card border-0 bg-transparent">
		<header class="accordion-header container-fluid gradient-text collapsed" role="tab" id="heading-<%= post.slug %>" data-toggle="collapse" data-parent="#accordion" data-target="#xx<%= post.slug %>" aria-expanded="false" aria-controls="<%= post.slug %>">
			<h2 class="mb-0 row">
				<% postDate = new Date( post.date ); %>
				<% if ( post._embedded['wp:term'][2][0] ) { %>
					<span class="col-md-4"><%= post._embedded['wp:term'][2][0].name %></span>
				<% } else { %>
					<span class="col-md-4"><%= post._embedded['wp:term'][3][0].name %></span>
				<% } %>
				<span class="col-md-4 text-truncate"><%= post.title.rendered %></span>
				<span class="col-md-3 text-truncate hidden-md-down"><%= post._embedded['wp:term'][0][0].name %></span>
				<span class="col text-truncate hidden-sm-down"><% print( postDate.getFullYear() ); %></span>
			</h2>
		</header>
		<div id="xx<%= post.slug %>" class="collapse container-fluid" role="tabpanel" aria-labelledby="heading-<%= post.slug %>">
			<div class="accordion-content row">
				<div class="col-md-4">
					<figure <% if ( ! post._ot_embed_url ) { %> class="featured-image" <% } %>>
						<% if ( post._ot_embed_url ) { %>
							<div class="embed-responsive embed-responsive-16by9">
								<%= post._ot_embed_url %>
							</div>
						<% } else if ( post._embedded['wp:featuredmedia'] ) { %>
							<a href="<%= post._ot_link_url %>" target="_blank" rel="noopener">
								<img src="<%= post._embedded['wp:featuredmedia'][0].media_details.sizes.medium.source_url %>">
							</a>
						<% } else { %>
							<svg class="icon icon-placeholder" aria-hidden="true" role="img"><use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#icon-placeholder"></use></svg>
						<% } %>
					</figure>
				</div>
				<div class="entry-content col-md-8 col-lg-4">
					<% if ( post._ot_link_url ) { %>
						<a href="<%= post._ot_link_url %>" class="featured-link word-wrap" target="_blank" rel="noopener"><%= post._ot_link_url %></a>
					<% } %>
					<% if ( post._ot_file ) { %>
						<a href="<%= post._ot_file %>" class="featured-link word-wrap" target="_blank" rel="noopener"><%= post._ot_file %></a>
					<% } %>
					<%= post.content.rendered %>
				</div>
				<div class="entry-meta col-md-8 col-lg-4 offset-md-4 offset-lg-0">
					<dl class="dl-inline">
						<% if ( post._embedded['wp:term'][2][0] ) { %>
						<dt><?php esc_html_e( 'Artist', 'opening_times' ); ?></dt>
						<dd><%= post._embedded['wp:term'][2][0].name %></dd>
						<% } %>
						<dt><?php esc_html_e( 'Category', 'opening_times' ); ?></dt>
						<dd><%= post._embedded['wp:term'][0][0].name %></dd>
						<% if ( post._embedded['wp:term'][1][0] ) { %>
							<dt><?php esc_html_e( 'Tags', 'opening_times' ); ?></dt>
							<dd>
							<% _.each( post._embedded['wp:term'][1], function(tag,i) { %>
								<% if ( i > 0 ) { %>, <% } %>
								<%= tag.name %>
							<% } ); %>
							</dd>
						<% } %>
						<dt><?php esc_html_e( 'Year', 'opening_times' ); ?></dt>
						<dd><% print( postDate.getFullYear() ); %></dd>
					</dl>
					<aside class="artist-bio ot-meta ot-bio" role="complementary">
					</aside>
				</div>
			</div>
		</div>
	</article>
</script>

<script type="text/html" id="more-button-template">
	<div class="more-posts">
		<a class="more-button button" href="#"><?php esc_html_e( 'More', 'opening_times' ); ?></a>
	</div>
</script>