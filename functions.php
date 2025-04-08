<?php
/**
 * Child functions and definitions.
 */

/**
 * Process single location
 *
 * @return void
 */
function cb_child_process_location( $location = null ) {

	if ( ! function_exists( 'jet_theme_core' ) ) {
		return false;
	}
	if( ! defined( 'ELEMENTOR_VERSION' ) ) {
		return false;
	}

	$done = jet_theme_core()->locations->do_location( $location );

	return $done;

}

function allow_custom_uploads($mimes) {
    $mimes['zip'] = 'application/zip';
	$mimes['dbf'] = 'application/dbase';
    return $mimes;
}
add_filter('upload_mimes', 'allow_custom_uploads');

//EP custom form webhook for calculation
function process_calculation( $f , $r) {
	
	 	
	 // Convert the data to JSON format
	 $json_data = json_encode($r);

	 // Your custom webhook URL
	 $webhook_url = 'https://n8n.m50.lv:5678/webhook/laboratory-calculation';
    
   	 // Send the data to the webhook endpoint using wp_remote_post
   	 $response = wp_remote_post(
       		 $webhook_url,
       		 array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
           		 'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	
	return $r;
}


add_filter( 'jet-form-builder/custom-filter/process-calculation', 'process_calculation' , 2, 10);

//EP jauns darbs
function new_job( $f , $r) {
	
	 	
	

	 // Your custom webhook URL
	
	$l = get_post_meta($r['lauks'], 'nosaukums', true);
	$n = get_post_meta($r['lauks'], 'numurs', true);
	
	// Prepare post data
    $post_data = array(
        'ID'         => $r['inserted_darbi'],
        'post_title' => $r['pasutijuma_nr'].'_'.$l.'_'.$r['tips'],
    );

	$r['title_'] =  $r['pasutijuma_nr'].'_'.$l.'_'.$r['tips'];
	
	// Convert the data to JSON format
	$json_data = json_encode($r);
	
    // Update post title from lauks
    $updated = wp_update_post($post_data);
	update_post_meta($r['inserted_darbi'], 'nosaukums', $meta_value);
	
	// Your custom webhook URL
    $webhook_url = 'https://n8n.m50.lv:5678/webhook/jauns_darbs';
    
   	// Send the data to the webhook endpoint using wp_remote_post
   	$response = wp_remote_post(
       		 $webhook_url,
       		 array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
				    'timeout' => 60,
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	
	
	
	return $r;
}


add_filter( 'jet-form-builder/custom-filter/new-job', 'new_job' , 2, 10);


//EP custom form webhook for dbf import
function process_import( $f , $r) {
	
	 	
	 // Convert the data to JSON format
	 $json_data = json_encode($r);

	 // Your custom webhook URL
	 $webhook_url = 'https://n8n.m50.lv:5678/webhook/laboratory-process-dbf';
    
   	 // Send the data to the webhook endpoint using wp_remote_post
   	 $response = wp_remote_post(
       		 $webhook_url,
       		 array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
				    'timeout' => 60,
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	 $status_code = wp_remote_retrieve_response_code($response); 
	 //if ( $status_code != 200 ) throw new \Jet_Form_Builder\Exceptions\Action_Exception( 'failed' );
	
	
	
	 return $r;
}


add_filter( 'jet-form-builder/custom-filter/process-import', 'process_import' , 2, 10);

//EP custom form webhook for dbf
function process_dbf( $f , $r) {
	
	
	 		// Convert the data to JSON format
	 		$json_data = json_encode($r);
	
			 // Your custom webhook URL
	 		$webhook_url = 'https://n8n.m50.lv:5678/webhook/laboratory-process-dbf';
    
		   	 // Send the data to the webhook endpoint using wp_remote_post
   	 		$response = wp_remote_post(
       			 $webhook_url,
       		     array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
					'timeout' => 60,  
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	 $status_code = wp_remote_retrieve_response_code($response); 
		 if ( $status_code != 200 ) throw new \Jet_Form_Builder\Exceptions\Action_Exception( 'Missing files in Google Drive' );
	
	
	 return $r;
}


add_filter( 'jet-form-builder/custom-filter/process-dbf', 'process_dbf' , 2, 10);


//EP custom form webhook for process_geojson
function process_geojson( $f , $r) {
	
	
	 		// Convert the data to JSON format
	 		$json_data = json_encode($r);
	
			 // Your custom webhook URL
	 		$webhook_url = 'https://n8n.m50.lv:5678/webhook/geojosn';
    
		   	 // Send the data to the webhook endpoint using wp_remote_post
   	 		$response = wp_remote_post(
       			 $webhook_url,
       		     array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
					'timeout' => 60, 
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	 $status_code = wp_remote_retrieve_response_code($response); 
		 if ( $status_code != 200 ) throw new \Jet_Form_Builder\Exceptions\Action_Exception( 'Missing files in Google Drive' );
	
	
	 return $r;
}


add_filter( 'jet-form-builder/custom-filter/process-geojson', 'process_geojson' , 2, 10);





//EP custom form webhook for export
function process_export( $f , $r) {
	
	 	
	 // Convert the data to JSON format
	 $json_data = json_encode($r);

	 // Your custom webhook URL
	 $webhook_url = 'https://n8n.m50.lv:5678/webhook/laboratory-process-export';
    
   	 // Send the data to the webhook endpoint using wp_remote_post
   	 $response = wp_remote_post(
       		 $webhook_url,
       		 array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
				    'timeout' => 60,
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	
	
	
   	return $r;
}


add_filter( 'jet-form-builder/custom-filter/process-export', 'process_export' , 2, 10);



function jauna_firma( $f , $r) {
	
	 	
	 // Convert the data to JSON format
	 $json_data = json_encode($r);

	 // Your custom webhook URL
	 $webhook_url = 'https://n8n.m50.lv:5678/webhook/jauna-saimnieciba';
    
   	 // Send the data to the webhook endpoint using wp_remote_post
   	 $response = wp_remote_post(
       		 $webhook_url,
       		 array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
				    'timeout' => 60,
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	
	
	
   	return $r;
}


add_filter( 'jet-form-builder/custom-filter/jauna-firma', 'jauna_firma' , 2, 10);


function laboratorija_send_email( $f , $r) {
	
	
	 // Convert the data to JSON format
	 $json_data = json_encode($r);

	 // Your custom webhook URL
	 $webhook_url = 'https://n8n.m50.lv:5678/webhook/laboratorija-send-email';
    
   	 // Send the data to the webhook endpoint using wp_remote_post
   	 $response = wp_remote_post(
       		 $webhook_url,
       		 array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
				    'timeout' => 60,
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	
	
	
   	return $r;
}

add_filter( 'jet-form-builder/custom-filter/laboratorija-send-email', 'laboratorija_send_email' , 2, 10);

//
////EP custom form webhook for dbf
function all_rows_save( $f , $r) {
	
	 	
	 // Convert the data to JSON format
	 $json_data = json_encode($f);

	 // Your custom webhook URL
	 $webhook_url = 'https://n8n.m50.lv:5678/webhook/visas-rindas';
    
   	 // Send the data to the webhook endpoint using wp_remote_post
   	 $response = wp_remote_post(
       		 $webhook_url,
       		 array(
           		 'headers' => array(
           		     'Content-Type' => 'application/json',
           		 ),
				    'timeout' => 60,
           		    'body' => $json_data,
					'sslverify' => false
        		)
   		 );
	
	return $r;
}

add_filter( 'jet-form-builder/custom-action/all-rows-save', 'all_rows_save' , 2, 10);

add_action('rest_api_init', function () {
    register_rest_route('laboratorija', '/delete-relation', [
        'methods' => 'DELETE',
        'callback' => 'delete_jetengine_relation',
        'permission_callback' => function () {
            return current_user_can('manage_options'); // Adjust permissions as needed
        },
        'args' => [
            'parent_object_id' => [
                'required' => true,
                'validate_callback' => function ($param) {
                    return is_numeric($param);
                }
            ]
        ]
    ]);
});

function delete_jetengine_relation($request) {
    $parent_object_id = $request->get_param('parent_object_id');

    if (!$parent_object_id) {
        return new WP_Error(
            'missing_parameter',
            __('Parent Object ID is required.', 'text-domain'),
            ['status' => 400]
        );
    }

    global $wpdb;

    $table_name = $wpdb->prefix . 'jet_rel_84'; // Replace with the actual JetEngine relations table name if different

    // Check if the relations exist
    $existing_relations = $wpdb->get_results(
        $wpdb->prepare("SELECT * FROM $table_name WHERE parent_object_id = %d", $parent_object_id)
    );

    if (empty($existing_relations)) {
        return new WP_Error(
            'no_relations_found',
            __('No relations found for the provided Parent Object ID.', 'text-domain'),
            ['status' => 404]
        );
    }

    // Delete relations
    $deleted = $wpdb->delete(
        $table_name,
        ['parent_object_id' => $parent_object_id],
        ['%d']
    );

    if ($deleted === false) {
        return new WP_Error(
            'deletion_failed',
            __('Failed to delete relations.', 'text-domain'),
            ['status' => 500]
        );
    }

    return new WP_REST_Response([
        'message' => __('Relations deleted successfully.', 'text-domain'),
        'deleted_count' => $deleted
    ], 200);
}




function allow_unsafe_urls ( $args ) {
       $args['reject_unsafe_urls'] = false;
       return $args;
    } ;

add_filter( 'http_request_args', 'allow_unsafe_urls' );

function webhook_http_args($http_args){
  
  return array_merge($http_args, array('sslverify'   => false));
}

add_filter( 'jet-engine/rest-api-listings/request/args', 'webhook_http_args', 10, 1 );


function allow_geojson_upload($mime_types){
    $mime_types['geojson'] = 'application/json'; // Add .geojson support
    return $mime_types;
}
add_filter('upload_mimes', 'allow_geojson_upload');
