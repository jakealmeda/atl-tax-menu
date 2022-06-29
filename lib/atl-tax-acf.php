<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// AUTO FILL SELECT FOR HOOKS (ACF)
add_filter( 'acf/load_field/name=tm-hook', 'atl_tm_autofill_hooks' );
function atl_tm_autofill_hooks( $field ) {

	$hookers = new AtlasSurvivalSheltersTaxMenu();

	$field['choices'] = array();

	//Loop through whatever data you are using, and assign a key/value
	if( is_array( $hookers->genesis_hooks ) ) {

		foreach( $hookers->genesis_hooks as $value ) {

			$field['choices'][$value] = $value;
		}

		return $field;

	}

}


/**
 * Auto select Checkbox options | Fields to Show
 *
 */
add_filter('acf/load_field/name=tm-hook', 'atl_tm_autofill_hooks_default' );
function atl_tm_autofill_hooks_default( $field ) {

	$field['default_value'] = 'genesis_after_header';

	return $field;

}


// AUTO FILL SELECT FOR ORDER BY (ACF)
add_filter( 'acf/load_field/name=tm-order-by', 'atl_tm_ob_autofill_hooks' );
function atl_tm_ob_autofill_hooks( $field ) {

	$hookers = new AtlasSurvivalSheltersTaxMenu();

	$field['choices'] = array();

	//Loop through whatever data you are using, and assign a key/value
	if( is_array( $hookers->order_by ) ) {

		foreach( $hookers->order_by as $key => $value ) {

			$field['choices'][$key] = $value;
		}

		return $field;

	}

}


/**
 * Auto fill Select options | TEMPLATES
 *
 */
add_filter( 'acf/load_field/name=tm-template', 'acf_atl_tax_template_choices' );
function acf_atl_tax_template_choices( $field ) {
    
    $z = new AtlasSurvivalSheltersTaxMenu();

    $file_extn = 'php';

    // get all files found in VIEWS folder
    $view_dir = $z->atl_plugin_dir_path().'templates/views/';

    $data_from_dir = setup_pulls_view_files( $view_dir, $file_extn );

    $field['choices'] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $data_from_dir ) ) {

        foreach( $data_from_dir as $field_key => $field_value ) {
            $field['choices'][$field_key] = $field_value;
        }

        return $field;

    }
    
}


/**
 * Auto fill Select options | Post Types
 *
 */
add_filter( 'acf/load_field/name=tm-post-type', 'atl_posttype_choices' );
function atl_posttype_choices( $field ) {
    
    $z = new AtlasSurvivalSheltersTaxMenu();

    $post_types = get_post_types( '', 'names' ); 

    $field[ 'choices' ] = array();

    //Loop through whatever data you are using, and assign a key/value
    if( is_array( $post_types ) ) {

        foreach( $post_types as $key => $post_type ) {

            if( !in_array( $post_type, $z->atl_not_from_these_posttypes() ) ) {

                $field[ 'choices' ][ $key ] = $post_type;
                
            }
            
        }

        return $field;

    }
    
}


/**
 * Pull all files found in $directory but get rid of the dots that scandir() picks up in Linux environments
 *
 */
if( !function_exists( 'setup_pulls_view_files' ) ) {

    function setup_pulls_view_files( $directory, $file_extn ) {

        $out = array();
        
        // get all files inside the directory but remove unnecessary directories
        $ss_plug_dir = array_diff( scandir( $directory ), array( '..', '.' ) );

        foreach( $ss_plug_dir as $filename ) {
            
            if( pathinfo( $filename, PATHINFO_EXTENSION ) == $file_extn ) {
                $out[ $filename ] = pathinfo( $filename, PATHINFO_FILENAME );
            }

        }

        /*foreach ($ss_plug_dir as $value) {
            
            // combine directory and filename
            $file = basename( $directory.$value, $file_extn );
            
            // filter files to include
            if( $file ) {
                $out[ $value ] = $file;
            }

        }*/

        // Return an array of files (without the directory)
        return $out;

    }
    
}

