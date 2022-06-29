<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ATLSubTaxMenu {

	/**
	 * Main function
	 */
	public function main_tm_function() {

		//add_action( get_field( 'plan-hook' ), function() use ( $args ){
		add_action( get_field( 'tm-hook' ), function() {

			$atts = array(
				'tm_this_id'					=> get_the_ID(),
				'tm_post_type'					=> get_field( 'tm-post-type' ),
				'tm_set_tax'					=> get_field( 'tm-set-tax' ),
				'tm_max'						=> get_field( 'tm-max-entries-show' ),
				'tm_order_by'					=> get_field( 'tm-order-by' ),
				'tm_order'						=> get_field( 'tm-order' ),
				'template'						=> get_field( 'tm-template' ),
			);

			// display or not?
			if( empty( get_field( 'tm-status' ) ) && !empty( get_field( 'tm-set-tax' ) ) ) :

				$container_class = get_field( 'tm-section-class' );
				$container_style = get_field( 'tm-section-style' );
				if( !empty( $container_class ) && !empty( $container_style ) ) {
					echo '<div class="'.$container_class.'" style="'.$container_style.'">';
				} else {

					if( !empty( $container_class ) ) {
						echo '<div class="'.$container_class.'">';
					} elseif( !empty( $container_style ) ) {
						echo '<div style="'.$container_style.'">';
					} else {
						echo '<div>';
					}

				}

				// output
				echo $this->atl_tm_dissect_entries( $atts );
	            //echo $this->atl_tm_view_template( get_field( 'tm-template' ), 'views' );

				echo '</div>';

	        endif;

		});

	}


	/**
	 * Process pulling entries
	 */
	public function atl_tm_dissect_entries( $atts ) {

		global $bars;

        // ENTRY CSS | Declare variable empty since this block copies the previous block's wrap_sel and wrap_sty
        $bars[ 'wrap_sel' ] = '';
        $bars[ 'wrap_sty' ] = '';
        $bars[ 'cid' ] = $atts[ 'tm_this_id' ];

		$out = ''; // declare empty variable

		// loop through the tax field
		foreach( $atts[ 'tm_set_tax' ] as $tax ) {

			// capture the taxonomy
			if( empty( $taxes_tax ) )
				$taxes_tax = $tax->taxonomy;

			$taxes[] = $tax->slug;

		}

		// post per page count (max entries to show)
		$max_e = $atts[ 'tm_max' ];
		if( $max_e <= 0 ) {
			$max_ppp = -1;
		} else {
			$max_ppp = $max_e;
		}

		$argz = array(
			'post_type'			=> $atts[ 'tm_post_type' ],
			'post_status'		=> 'publish',
			'posts_per_page'	=> $max_ppp,
			'orderby'			=> $atts[ 'tm_order_by' ],
			'order'				=> $atts[ 'tm_order' ],
			'tax_query'			=> array(
				array(
					'taxonomy'		=> $taxes_tax,
					'field'			=> 'slug',
					'terms'			=> $taxes,
				),
			),
		);

		$loop = new WP_Query( $argz );

		// loop
		if( $loop->have_posts() ):

		    $post_ids = array();

		    // get all post IDs
		    while( $loop->have_posts() ): $loop->the_post();
		        
		        if( !in_array( get_the_ID(), $post_ids ) ) {
		        	
		            $post_ids[] = get_the_ID();

		            $bars[ 'pid' ] = get_the_ID();
		        
		            $out .= $this->atl_tm_view_template( $atts[ 'template' ], 'views' );

		        }
		        
		    endwhile;

		    /* Restore original Post Data 
		     * NB: Because we are using new WP_Query we aren't stomping on the 
		     * original $wp_query and it does not need to be reset.
		    */
		    wp_reset_postdata();

		endif;

		return $out;

	}


    /**
     * Get VIEW template
     */
    public function atl_tm_view_template( $layout, $dir_ext ) {

        $o = new AtlasSurvivalSheltersTaxMenu();

        $layout_file = $o->atl_plugin_dir_path().'templates/'.$dir_ext.'/'.$layout;

        if( is_file( $layout_file ) ) {

            ob_start();

            include $layout_file;

            $new_output = ob_get_clean();

            if( !empty( $new_output ) ) {
                $output = $new_output;
            } else {
                $output = FALSE;
            }


        } else {

            $output = FALSE;

        }

        return $output;

    }


    /**
     * Combine Classes for the template
     */
    public function setup_combine_classes( $classes ) {

        $block_class = !empty( $classes[ 'block_class' ] ) ? $classes[ 'block_class' ] : '';
        $item_class = !empty( $classes[ 'item_class' ] ) ? $classes[ 'item_class' ] : '';
        $manual_class = !empty( $classes[ 'manual_class' ] ) ? $classes[ 'manual_class' ] : '';

        $return = '';
        
        $ar = array( $block_class, $item_class, $manual_class );
        for( $z=0; $z<=( count( $ar ) - 1 ); $z++ ) {

            if( !empty( $ar[ $z ] ) ) {

                $return .= $ar[ $z ];

                if( $z != ( count( $ar ) - 1 ) ) {
                    $return .= ' ';
                }

            }

        }

        return $return;

    }


    /**
     * Combine Classes for the template
     */
    public function setup_combine_styles( $styles ) {

        $manual_style = $styles[ 'manual_style' ];
        $item_style = $styles[ 'item_style' ];

        if( !empty( $manual_style ) && !empty( $item_style ) ) {
                return $manual_style.' '.$item_style;
        } else {

            if( empty( $manual_style ) && !empty( $item_style ) ) {
                return $item_style;
            } else {
                return $manual_style;
            }

        }

    }


    /**
     * Array validation
     */
    public function setup_array_validation( $needles, $haystacks, $args = FALSE ) {

        if( is_array( $haystacks ) && array_key_exists( $needles, $haystacks ) && !empty( $haystacks[ $needles ] ) ) {

            return $haystacks[ $needles ];

        } else {

            return FALSE;

        }

    }


	/**
	* Get Custom Taxonomy Terms
	*/
	public function atl_get_tax_terms( $tid, $taxname, $anchor = FALSE ) {

		$out = '';

		foreach( $tid as $term ) {
			$t = get_term_by( 'term_id', $term, $taxname );
			if( $anchor !== FALSE ) {
				$out .= '<div class="item-term"><a href="'.get_term_link( $t->term_id ).'">'.$t->name.'</a></div>';
			} else {
				$out .= '<div class="item-term">'.$t->name.'</div>';
			}
			
		}

		return $out;

	}


	/**
	 * Handle the display
	 */
	public function __construct() {

		if( !is_admin() ) {

			add_action( 'wp', array( $this, 'main_tm_function' ) );

		}

	}

}