<?php
/**
 * Rest api endpoint.
 *
 * This file is used to house endpoint functions, that fetch data using the rest api.
 *
 * @link( example endpoint, http://pauls-macbook-air.local:5757/wp-json/wp/v2/news)
 * @link( rest api docs, http://v2.wp-api.org/extending/modifying/#what-register_rest_field-does)
 * 
 * @package Opening Times
 */

/**
 * Add fields to Posts json
 *
 * @since Opening Times 1.0.0
 */
add_action( 'rest_api_init', 'opening_times_register_metaboxes' );
function opening_times_register_metaboxes() {
    register_rest_field( 'post',
        '_ot_link_url',
        array(
            'get_callback'    => 'opening_times_get_rest_post_meta',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    register_rest_field( 'post',
        '_ot_file',
        array(
            'get_callback'    => 'opening_times_get_rest_post_meta',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    register_rest_field( 'post',
        '_ot_residency_start_date',
        array(
            'get_callback'    => 'opening_times_get_rest_post_meta',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    register_rest_field( 'post',
        '_ot_residency_end_date',
        array(
            'get_callback'    => 'opening_times_get_rest_post_meta',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    register_rest_field( 'post',
        '_ot_embed_url',
        array(
            'get_callback'    => 'opening_times_get_rest_oembed',
            'update_callback' => null,
            'schema'          => null,
        )
    );
    register_rest_field( 'post',
        '_ot_artist_bio',
        array(
            'get_callback'    => 'opening_times_get_tax_description',
            'update_callback' => null,
            'schema'          => null,
        )
    );
}

/**
 * Get the value of custom fields
 *
 * @param array $object Details of current post.
 * @param string $field_name Name of field.
 * @param WP_REST_Request $request Current request
 *
 * @return mixed
 *
 * @since Opening Times 1.0.0
 */
function opening_times_get_rest_post_meta( $object, $field_name, $request ) {
    return get_post_meta( $object[ 'id' ], $field_name, true );
}

/**
 * Convert value of oembed field and generate markup.
 * 
 * @param  array $object Details of current post.
 * @param  string $field_name Name of field.
 * @param  WP_REST_Request $request Current request
 * 
 * @return mixed
 *
 * @since Opening Times 1.0.0
 */
function opening_times_get_rest_oembed( $object, $field_name, $request ) {    
    $url = esc_url( get_post_meta( $object[ 'id' ], $field_name, true ) );
    
    //return wp_oembed_get( $url );
    return apply_filters( 'the_content', $url );
}

/**
 * Get the description for the artist taxonomy
 * 
 * @return string taxonomy description
 *
 * @since Opening Times 1.0.0
 */
function opening_times_get_tax_description() {
    $tax_description = get_the_terms( get_the_ID(), 'artists' );

    if ( '' == $tax_description ) {
        return;
    }

    foreach ( $tax_description as $tax ) {
        if (  $tax->description ) {
            return wpautop( wptexturize( $tax->description ) );
        }
    };
}
