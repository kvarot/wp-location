<?php

// Load CSS and JS
if ( ! is_admin() ) {
    add_action( 'wp_enqueue_scripts', 'wceb_enqueue_custom_scripts', 10 );
}

function wceb_enqueue_custom_scripts() {
	global $post;

	if ( ! is_product() ) {
        return;
    }

    $product = wc_get_product( $post->ID );

    if ( ! $product ) {
        return;
    }

    if ( function_exists( 'wceb_is_bookable' ) && wceb_is_bookable( $product ) && has_term( array( 'location-duo', 'location-quatro' ), 'product_cat' ) ) {

		wp_enqueue_style(
	        'wceb_custom_style',
	        get_stylesheet_directory_uri() . '/inc/wceb_custom_style.css',
	        true
	    );

	    wp_enqueue_script(
	        'wceb_custom_script',
	        get_stylesheet_directory_uri() . '/inc/wceb_custom_script.js',
	        array('jquery'),
	        '1.0',
	        true
	    );

	}

}

// Add fields HTML
add_action( 'easy_booking_after_date_inputs', 'wceb_add_custom_product_options', 10 );

function wceb_add_custom_product_options() {
	global $product;

	if ( function_exists( 'wceb_is_bookable' ) && wceb_is_bookable( $product ) && has_term( array( 'location-duo', 'location-quatro' ), 'product_cat' ) ) {
		include_once( 'fields-template.php' );
	}
	
}

// Check that required fields are completed
add_filter( 'woocommerce_add_to_cart_validation', 'wceb_check_custom_fields', 20, 5 );

function wceb_check_custom_fields( $passed = true, $product_id, $quantity, $variation_id = '', $cart_item_data = array() ) {

    if ( has_term( array( 'location-duo', 'location-quatro' ), 'product_cat', $product_id ) ) {

    	if ( empty( $_POST['_start_time'] ) ) {
    		wc_add_notice( __( '"Heure de retrait" est requis', 'easy_booking' ), 'error' );
            $passed = false;
    	}

    	if ( empty( $_POST['_end_time'] ) ) {
    		wc_add_notice( __( '"Heure de retour" est requis', 'easy_booking' ), 'error' );
            $passed = false;
    	}

    	if ( empty( $_POST['_vehicule_type'] ) ) {
    		wc_add_notice( __( '"Type de véhicule" est requis', 'easy_booking' ), 'error' );
            $passed = false;
    	}

    	if ( empty( $_POST['_vehicule_height'] ) ) {
    		wc_add_notice( __( '"Hauteur du véhicule" est requis', 'easy_booking' ), 'error' );
            $passed = false;
    	}

    }

	return $passed;
	
}

// Save cart item data
add_filter( 'woocommerce_add_cart_item_data', 'wceb_add_custom_attributes', 10, 3 );

function wceb_add_custom_attributes ( $cart_item_data, $product_id, $variation_id ) {
	$start_time      = empty( $_POST['_start_time'] ) ? '' : sanitize_text_field( $_POST['_start_time'] );
	$end_time        = empty( $_POST['_end_time'] ) ? '' : sanitize_text_field( $_POST['_end_time'] );
	$vehicule_type   = empty( $_POST['_vehicule_type'] ) ? '' : sanitize_text_field( $_POST['_vehicule_type'] );
	$vehicule_height = empty( $_POST['_vehicule_height'] ) ? '' : sanitize_text_field( $_POST['_vehicule_height'] );

	if ( ! empty( $start_time ) ) {
		$cart_item_data[ '_start_time' ] = $start_time;
	}

	if ( ! empty( $end_time ) ) {
		$cart_item_data[ '_end_time' ] = $end_time;
	}

	if ( ! empty( $vehicule_type ) ) {
		$cart_item_data[ '_vehicule_type' ] = $vehicule_type;
	}

	if ( ! empty( $vehicule_height ) ) {
		$cart_item_data[ '_vehicule_height' ] = $vehicule_height;
	}

	return $cart_item_data;
}

// Save cart item data again
add_filter( 'woocommerce_get_cart_item_from_session', 'wceb_add_custom_attributes_from_session', 10, 2 );

function wceb_add_custom_attributes_from_session( $cart_item, $values ) {

    if ( isset( $values['_start_time'] ) ) {
        $cart_item['_start_time'] = $values['_start_time'];
    }

    if ( isset( $values['_end_time'] ) ) {
        $cart_item['_end_time'] = $values['_end_time'];
    }

    if ( isset( $values['_vehicule_type'] ) ) {
        $cart_item['_vehicule_type'] = $values['_vehicule_type'];
    }

    if ( isset( $values['_vehicule_height'] ) ) {
        $cart_item['_vehicule_height'] = $values['_vehicule_height'];
    }
    
    return $cart_item;
}

// Display cart item data
add_filter( 'woocommerce_get_item_data', 'wceb_get_custom_item_data', 10, 2 );

function wceb_get_custom_item_data( $other_data, $cart_item ) {

	if ( isset( $cart_item['_start_time'] ) && ! empty ( $cart_item['_start_time'] ) ) {

        $other_data[] = array(
            'name'  => 'Heure de retrait',
            'value' => $cart_item['_start_time']
        );

    }

    if ( isset( $cart_item['_end_time'] ) && ! empty ( $cart_item['_end_time'] ) ) {

        $other_data[] = array(
            'name'  => 'Heure de retour',
            'value' => $cart_item['_end_time']
        );

    }

    if ( isset( $cart_item['_vehicule_type'] ) && ! empty ( $cart_item['_vehicule_type'] ) ) {

        $other_data[] = array(
            'name'  => 'Type de véhicule',
            'value' => $cart_item['_vehicule_type']
        );

    }

    if ( isset( $cart_item['_vehicule_height'] ) && ! empty ( $cart_item['_vehicule_height'] ) ) {

        $other_data[] = array(
            'name'  => 'Hauteur du véhicule',
            'value' => ( $cart_item['_vehicule_height'] === 'less_180' ) ? 'Moins d\'1M80' : 'Plus d\'1M80'
        );

    }

    if ( isset( $cart_item['_booking_duration'] ) && ! empty ( $cart_item['_booking_duration'] ) ) {

        $other_data[] = array(
            'name'  => 'Durée de votre réservation',
            'value' => $cart_item['_booking_duration'] . ' nuit(s)'
        );

    }

    return $other_data;

}

// Save order item data (WooCommerce > 3.0 only)
add_action( 'woocommerce_checkout_create_order_line_item', 'wceb_add_custom_order_item_meta', 10, 3 );

function wceb_add_custom_order_item_meta( $item, $cart_item_key, $values ) {

	if ( ! empty( $values['_start_time'] ) ) {
        $item->add_meta_data( '_start_time', sanitize_text_field( $values['_start_time'] ) );
    }

    if ( ! empty( $values['_end_time'] ) ) {
        $item->add_meta_data( '_end_time', sanitize_text_field( $values['_end_time'] ) );
    }

    if ( ! empty( $values['_vehicule_type'] ) ) {
        $item->add_meta_data( '_vehicule_type', sanitize_text_field( $values['_vehicule_type'] ) );
    }

    if ( ! empty( $values['_vehicule_height'] ) ) {
        $item->add_meta_data( '_vehicule_height', sanitize_text_field( $values['_vehicule_height'] ) );
    }

    if ( ! empty( $values['_booking_duration'] ) ) {
        $item->add_meta_data( '_booking_duration', sanitize_text_field( $values['_booking_duration'] ) );
    }

}

// Display order item data (WooCommerce > 3.0 only)
add_filter( 'woocommerce_display_item_meta', 'wceb_display_custom_order_item_meta', 10, 3 );

function wceb_display_custom_order_item_meta( $html, $item, $args ) {

    $start_time = $item->get_meta( '_start_time' );
    if ( isset( $start_time ) && ! empty( $start_time ) ) {
        $html .= '<dl class="variation">' . wp_kses_post( 'Heure de retrait : ' . $start_time ) . '</dl>';
    }

    $end_time = $item->get_meta( '_end_time' );
    if ( isset( $end_time ) && ! empty( $end_time ) ) {
        $html .= '<dl class="variation">' . wp_kses_post( 'Heure de retour : ' . $end_time ) . '</dl>';
    }

    $vehicule_type = $item->get_meta( '_vehicule_type' );
    if ( isset( $vehicule_type ) && ! empty( $vehicule_type ) ) {
        $html .= '<dl class="variation">' . wp_kses_post( 'Type de véhicule : ' . $vehicule_type ) . '</dl>';
    }

    $vehicule_height = $item->get_meta( '_vehicule_height' );
    if ( isset( $vehicule_height ) && ! empty( $vehicule_height ) ) {
    	$height = ( $vehicule_height === 'less_180' ) ? 'Moins d\'1M80' : 'Plus d\'1M80';
        $html .= '<dl class="variation">' . wp_kses_post( 'Hauteur du véhicule : ' . $height ) . '</dl>';
    }

    $booking_duration = $item->get_meta( '_booking_duration' );
    if ( isset( $booking_duration ) && ! empty( $booking_duration ) ) {
        $html .= '<dl class="variation">' . wp_kses_post( 'Durée de votre réservation : ' . $booking_duration ) . ' nuit(s)</dl>';
    }

    return $html;
}

// Display order item data correctly
add_filter( 'woocommerce_order_item_display_meta_key', 'wceb_custom_meta_key', 10, 1 );

function wceb_custom_meta_key( $display_key ) {
	if ( $display_key === '_start_time' ) {
		$display_key = 'Heure de retrait';
	}

	if ( $display_key === '_end_time' ) {
		$display_key = 'Heure de retour';
	}

	if ( $display_key === '_vehicule_type' ) {
		$display_key = 'Type de véhicule';
	}

	if ( $display_key === '_vehicule_height' ) {
		$display_key = 'Hauteur du véhicule';
	}

    if ( $display_key === '_booking_duration' ) {
        $display_key = 'Durée de votre réservation';
    }

	return $display_key;
}

// Display order item data correctly
add_filter( 'woocommerce_order_item_display_meta_value', 'wceb_custom_meta_value', 10, 1 );

function wceb_custom_meta_value( $display_value ) {
    
	if ( $display_value === 'less_180' ) {
		$display_value = 'Moins d\'1M80';
	}

	if ( $display_value === 'more_180' ) {
		$display_value = 'Plus d\'1M80';
	}

	return $display_value;
}