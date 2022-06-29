<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class ATLWPQuery {

    public function atl_query_please( $atts ) {

        // set default order by field
        if( empty( $orderby ) ) {
            $orderby = 'date';
        } else {
            $orderby = $orderby;
        }

        // set default order
        if( empty( $order ) ) {
            $order = 'DESC';
        } else {
            $order = $order;
        }

        // set the arguments
        $args = array(
            'post_type'         => $atts[ 'tm_post_type' ],
            'post_status'       => 'publish',
            'posts_per_page'    => $atts[ 'tm_max' ],
            'post__not_in'      => $atts[ 'tm_this_id' ],
            'orderby'           => $atts[ 'tm_order_by' ],
            'order'             => $atts[ 'tm_order' ],
            'tax_query'         => array(
                array(
                    'taxonomy' => 'actor',
                    'field'    => 'term_id',
                    'terms'    => array( 103, 115, 206 ),
                    'operator' => 'IN',
                ),
            ),
        );

    }

}