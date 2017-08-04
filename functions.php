<?php
include_once( 'inc/easy-booking-custom.php' );

add_filter( 'easy_booking_availability_imports_columns', 'ebac_custom_import_column', 10, 1 );

function ebac_custom_import_column( $new_column ) {
    $new_column[] = array(
        'position' => 0,
        'content' => array(
            'client'   => __( 'Client', 'easy_booking_availability' )
        )
    );

    return $new_column;
}

add_filter('wp_mail_from', 'new_mail_from');
add_filter('wp_mail_from_name', 'new_mail_from_name');
 
function new_mail_from($old) {
 return 'contact@naitup.com';
}
function new_mail_from_name($old) {
 return 'NaïtUp';
}

add_action( 'woocommerce_thankyou', function( $order_id ){

	$bool=false;
	$one = WP_CONTENT_DIR . '/uploads/location/contrat.pdf';
	$two = WP_CONTENT_DIR . '/uploads/location/conditions.pdf';
	$three = WP_CONTENT_DIR . '/uploads/location/tarifs.pdf';
	$headers = array('Content-Type: text/html; charset=UTF-8');
	$attachments = array(  $one, $two, $three );
	$subject = 'Vos documents de location';
	

 
    $order = wc_get_order( $order_id );
  
    // 3) Get the order items
    $items = $order->get_items();

	$order_meta = get_post_meta($order_id);
	$to = $order_meta[_billing_email][0];
global $woocommerce, $post, $product;

foreach ( $items as $item_id => $item_data ) {
	
	
$res = get_post_meta($item_data->get_product_id());
//  $koostis = array_shift( wc_get_product_terms( $item_data->get_product_id(), 'pa_lieu-de-retrait', array( 'fields' => 'names' ) ) );
//          $koostis = get_the_terms($item_data->get_product_id(), 'pa_lieu-de-retrait' );

$values = wc_get_product_terms(  $item_data->get_product_id(),'pa_lieu-de-retrait', array( 'fields' =>  'all' ) );
 
      //  foreach ( $values as $term ){
         //  var_dump(term_description( $term->term_id, $term->taxonomy ));
      //  }
 
 $lieu_ids =   wc_get_product_terms( $item_data->get_product_id(), 'pa_lieu-de-retrait', array( 'fields' => 'ids' )  );
  $lieu_noms = wc_get_product_terms( $item_data->get_product_id(), 'pa_lieu-de-retrait', array( 'fields' => 'names' ) );
$lieu_slugs= wc_get_product_terms( $item_data->get_product_id(), 'pa_lieu-de-retrait', array( 'fields' => 'slugs' ) );

     $terms = get_the_terms($item_data->get_product_id(), 'product_cat' );
  $date_retour=$item_data->get_meta('_ebs_end_format', true );
   $date=$item_data->get_meta('_ebs_start_format', true );
 $heure_retour= $item_data->get_meta('_end_time', true );
 $lieu = $item_data->get_meta('pa_lieu-de-retrait', true );
$lieu_id = array_search($lieu, $lieu_slugs); 

 $i=0;
foreach ($lieu_slugs as $key => $value) {
    if ($value == $lieu) {$position=$i;  }
    $i++;
}
 
if   ( $terms[0]->term_id ==234 || $terms[0]->term_id ==235 ) { //// c'est une location
	 $bool=true;
	    }
 

    } 
 
 $date_loc =  date("d/m/Y", strtotime($date ));
 $date_retour = date("d/m/Y", strtotime($date_retour ));
if ($bool)
{ 
	
$lieu=$lieu_noms[$position];
$coord= category_description( $lieu_id );
	
$body = '<table align="center" width="90%"><tr><td>
	<p align="center"><img align="middle" src="https://naitup.com/wp-content/uploads/2017/06/nu.png" width="300"></p><br><br>
	<p>Bonjour,<br><br>


Votre demande de location est bien enregistrée, merci de nous faire parvenir des arrhes correspondant à 30% du total de la réservation dans les 6 jours afin de confirmer la réservation. 
.<br><br>
Vous pouvez nous payer par virement,voici notre RIB.

    <div style="text-align:center;">Bénéficiaire "BS Outdoor / NaïtUp
    Banque: Crédit Agricole du Languedoc
    IBAN: FR76 1350 6100 0091 8531 9200 133
    BIC: AGRIFRPP835</div>
<br><br>	
Ou par chèque à BS Outdoor / NaïtUp et à adresser à<br><br>
 <div style="text-align:center;">
BS Outdoor / NaïtUp
235 avenue des chênes rouges
30100 ALES, FRANCE</div><br><br>

Le solde devra nous parvenir (par chèque ou virement) au plus tard 20 jours avant le début du séjour.

Les détails de votre réservation sont indiqués ci-dessous.

<h3>Important:<br>Le retour devra se faire impérativement à '.$heure_retour.' le '.$date_retour.'.</h3>
<p><em>Pensez à prendre un (ou plusieurs) drap(s) housse(s) dont les tailles sont :
<ul>
<li>Duö: 120 x 200 cm </li>
<li>Quatrö : 160 x 200 cm</li>
</ul>
, pour toute la durée de votre votre séjour, à mettre sur le matelas (lui même déjà équipé d\'une housse molleton).<br>
Pensez à venir avec vos 2 barres de toit transversales installées sur votre véhicule (distantes de 70 cm à 125 cm et supportant 75Kg ou plus)<br>

Vous trouverez en pièce jointe le contrat de votre location, les Conditions Générales de Location et les tarifs.</em></p>

<h3>1.1- Date de retrait : '.$date_loc.'</h3>
<h3>1.2- Date de retour : '.$date_retour.'</h3>
<h3>1.3- Lieu de retrait : '.$lieu.'</h3>
 
<p>'.$coord.'</p>
 

<h3>2- Paiement au choix: </h3>
<h4>Par virement: </h4>
<ul>
<li>arrhes dans les 6 jours suivants votre demande de loaction et solde au moins 20 jours avant le début du séjour. Vous devrez également nous faire parvenir par courrier le chèque de caution (montant selon option choisie, à l\'ordre de BS OUTDOOR)</li>
</ul>
<h4>Par chèque: </h4>
<ul>
<li>1ier chèque: chèque d\'arrhes  (dans les 6 jours afin de confirmer la réservation)</li>
<li>2ième chèque: chèque du solde la réception du chèque de solde devra se faire au moins 20 jours avant le premier jour de votre réservation et sera encaissé 20 jours avant le premier jour de votre réservation</li>
<li>3ième chèque: chèque de caution (selon option, voir page 2 du Contrat de Location)</li>
</ul>

<h3>3-Documents</h3>
Rappel des documents à nous faire parvenir (indiqués dans le Contrat de Location) par email à <a href="mailto:contact@naitup.com">contact@naitup.com</a> ou par courrier( adresse ci-dessous).<br>
<ul>
<li>Contrat de location complété (en pièce jointe)</li>
<li>Photocopie de votre permis de conduire</li>
<li>Photocopie de la carte grise du véhicule</li>
<li>Photocopie de votre carte d\'assurance (document vert)</li>
<li>Photocopie de votre police d\'assurance si Hussarde prise en charge</li>
</ul>
<br><br>
Tous les documents + chèques sont à nous envoyer à l\'adresse suivante:<br>
NaïtUp / BS Outdoor Sarl<br>
Attn: Service Location
<br>
235 avenue des chênes rouges<br>
30100 ALES<br>
France<br>
</p>
	
	</td></tr></table><br>
	'
	;
wp_mail( $to, $subject, $body, $headers ,$attachments);
wp_mail( 'clepipou@free.fr', $subject,$body,$headers);

}
	
 
   
});

 
function hidding_coupon_field_on_cart_for_a_category($enabled) {
    // Set your special category name, slug or ID here:
    $special_cat1 = 'location-duo';$special_cat2 = 'location-quatro';
    $bool = false;

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
        $item = $cart_item['data'];
        if ( has_term( $special_cat1, 'product_cat', $item->id ) )
            $bool = true;
        if ( has_term( $special_cat2, 'product_cat', $item->id ) )
            $bool =true;
    }

    if ( $bool ) {
        $enabled = false;
    }
    
 
    return $enabled;
}
add_filter( 'woocommerce_coupons_enabled', 'hidding_coupon_field_on_cart_for_a_category' );

 
add_filter( 'woocommerce_ship_to_different_address_checked', '__return_false' );


add_filter('woocommerce_dropdown_variation_attribute_options_args', 'custom_woocommerce_product_add_to_cart_text', 10, 2);


function custom_woocommerce_product_add_to_cart_text($args){
 $args['show_option_none'] = __( 'Choisir...', 'woocommerce' ); 
  return $args;
}



 

/**
 * Auto Complete all WooCommerce orders.
 */
 
/*
add_action( 'woocommerce_thankyou', 'custom_woocommerce_auto_complete_order' );
function custom_woocommerce_auto_complete_order( $order_id ) { 
    if ( ! $order_id ) {
        return;
    }

    $order = wc_get_order( $order_id );
    $order->update_status( 'completed' );
}
 
*/

add_filter( 'woocommerce_package_rates', 'hide_shipping_method' , 10, 2 );

function hide_shipping_method( $rates, $package ) {
global $woocommerce;

$excluded_categories = array( '234', '235' );

$cart_items = $woocommerce->cart->get_cart();

foreach ( $cart_items as $key => $item ) {
$categories = get_the_terms( $item['product_id'], 'product_cat' );
foreach ($categories as $category) {
$product_cat_id = $category->term_id;

if( in_array( $product_cat_id, $excluded_categories ) ) // delete the shipping method
unset( $rates['shipping_method_to_delete'] );
}

return $rates;
}
}

 

/*

 
function wc_remove_all_quantity_fields( $return, $product ) {
	
	
	switch ( $product->category_ids=[0] ) :
	case "234":
		return true;
		break;
	case "235":
		return false;
		break;
	 
	break;
	default:	// simple product type
		return true;
	break;
endswitch;
	
	 }
add_filter( 'woocommerce_is_sold_individually', 'wc_remove_all_quantity_fields', 10, 2 );
 
*/


add_filter( 'woocommerce_product_tabs', 'woo_remove_product_tabs', 98 );
function woo_remove_product_tabs( $tabs ) {
        unset( $tabs['additional_information'] );  	// Remove the additional information tab
    return $tabs;
}
	
		add_filter( 'woocommerce_cart_no_shipping_available_html', 'change_noship_message' );
add_filter( 'woocommerce_no_shipping_available_html', 'change_noship_message' );
function change_noship_message() {
    print "Désolé mais nous ne livrons pas encore dans votre zone géographique...";
}

 
if( ! function_exists('yith_woocompare_move_loop_button') ) {
    function yith_woocompare_move_loop_button(){
        global $yith_woocompare;
        if( class_exists( 'YITH_Woocompare' ) && $yith_woocompare && ! is_admin() ){
            if ( get_option('yith_woocompare_compare_button_in_products_list') == 'yes' ){
                remove_action( 'woocommerce_after_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
                add_action( 'woocommerce_before_shop_loop_item', array( $yith_woocompare->obj, 'add_compare_link' ), 20 );
            }
        }
    }
    add_action('template_redirect', 'yith_woocompare_move_loop_button' );
} 
 



	
 /**
 * Add checkbox field to the checkout
 **/
 
 function msk_display_use_points_checkbox() {
	 
	 $terms = get_the_terms($cart_item['product_id'] , 'product_cat' );
 
	 
 if ( ! WC()->cart->is_empty() ) {
    
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	    
	  	 $terms = get_the_terms($cart_item['product_id'] , 'product_cat' );
 
        $duration = $cart_item['_booking_duration'];
    } 
  
if   ( $terms[0]->term_taxonomy_id ==234 || $terms[0]->term_taxonomy_id ==235 ) { //// c'est une location
 if ($terms[0]->term_taxonomy_id ==234) {$tente='duo';}
 if ($terms[0]->term_taxonomy_id ==235) {$tente='quatro';}
 
		if (isset($_POST['post_data'])) {
			parse_str($_POST['post_data'], $form_data);
		} else {
			$form_data = $_POST;
		} 
		
		
		
		 ?>
		<!-- KEV
		//on récupere le prix par produit et on l'affiche
		// on inclu les variables utilisé en fin de fichier
		<?php
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$textkev= "Il y a 30 % d'arrhes : ";
				$percentage = 0.7;
				$price_product =  WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] );
				$theprice = $_product->get_price();
				$price_ope =  $theprice * $percentage ;
				$price_reduc = $theprice - $price_ope;
				$garantie_loc = get_garantie_price ($duration,$tente);	
				$garantie_loc_neg =  - $garantie_loc;
				$duree_loc = ($duration*2);
				$duree_loc_neg = -($duration*2);
				global $woocommerce;
				$new_total_loc =  $woocommerce->cart->cart_contents_total+$woocommerce->cart->tax_total;

				//	echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); 
		?>
		
		-->
		<!--Script jq pour update on check --> 
		<script>jQuery('form.checkout').on('change', '#warranty', function(){ jQuery('body').trigger('update_checkout'); });
		
		jQuery('form.checkout').on('change', '#linge', function(){ jQuery('body').trigger('update_checkout'); });
		jQuery('form.checkout').on('change', '#arrhes', function(){ jQuery('body').trigger('update_checkout'); });
		</script> 

		<fieldset class="warranty">
			<h4>Options</h4>
			<label for="warranty">
				<input type="hidden" name="warranty" value="off" />
				<input type="checkbox" <?php checked($form_data['warranty'], 'on'); ?> id="warranty" name="warranty" value="on" />
				<span><?php printf(__('<strong> Garantie tranquillité  '.strtoupper($tente).': '.$garantie_loc.'€</strong> pour '.$duration.' nuits(s)', 'mosaika'), $duration); ?></span>
			</label>	
			<br>
		
			<label for="linge">
				<input type="hidden" name="linge" value="off" />
				<input type="checkbox" <?php checked($form_data['linge'], 'on'); ?> id="linge" name="linge" value="on" />
				<span><?php printf(__('<strong> Location de linge de lit (drap housse): '.$duree_loc.'€</strong> - 2€ par nuits(s)', 'mosdaika'), $duration); ?></span>
			</label>	
			<br> 
			
			<label for="arrhes">
				<input type="hidden" name="arrhes" value="off" />
			
			</label>	
			<br>
				
	
		</fieldset>
	<?php 
		}
		}
}
add_action('woocommerce_checkout_before_order_review', 'msk_display_use_points_checkbox', 10, 0 );

 
function msk_add_discount_to_cart_total($cart) {
	
	// var_dump($cart_item);
 //_ebs_start
// _ebs_end
	 if ( ! WC()->cart->is_empty() ) {
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	    	  	 $terms = get_the_terms($cart_item['product_id'] , 'product_cat' );

        $duration = $cart_item['_booking_duration'];
    } 
    
    if   ( $terms[0]->term_taxonomy_id ==234 || $terms[0]->term_taxonomy_id ==235 ) { //// c'est une location
 if ($terms[0]->term_taxonomy_id ==234) {$tente='duo';}
 if ($terms[0]->term_taxonomy_id ==235) {$tente='quatro';}

    
	if (!$_POST || (is_admin() && !is_ajax())) {
		return;
	}
	if (isset($_POST['post_data'])) {
		parse_str($_POST['post_data'], $form_data);
	} else {
		$form_data = $_POST; // fallback for final checkout (non-ajax)
	}
	// Si l'acheteur a coché la checkbox pour utiliser ses points...
	if (isset($form_data['warranty']) && $form_data['warranty'] == 'on') {
			
			 			// On ajoute un frais négatif (réduction) sur sa commande
			WC()->cart->add_fee(__('Garantie tranquillité '.strtoupper($tente).' (30%)', 'garantie'), (get_garantie_price ($duration,$tente)*0.3), false, '');
		
	}
	
	if (isset($form_data['linge']) && $form_data['linge'] == 'on') {
 		
			 			// On ajoute un frais négatif (réduction) sur sa commande
			WC()->cart->add_fee(__('Location de linge de lit (30%)', 'location_linge'), (($duration*2)*0.3), false, '');
		
		
	}
	
	
	}
}}
add_action('woocommerce_cart_calculate_fees', 'msk_add_discount_to_cart_total');

 
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
 
function woo_custom_cart_button_text() {
	
	    $product_cats = wp_get_post_terms( get_the_ID(), 'product_cat' );
	    
  
 
 switch ( $product_cats[0]->term_id ) :
	case "234":
		return __( 'RÉSERVER', 'woocommerce' );
		break;
	case "235":
		return __( 'RÉSERVER', 'woocommerce' );
		break;
	 
	break;
	default:	// simple product type
		return __( 'AJOUTER AU PANIER', 'woocommerce' );
	break;
endswitch;

 
}


/**
 * First clear the cart of all products
 */
/*
function clear_cart_before_adding( $cart_item_data ) {
  global $woocommerce;
  $woocommerce->cart->empty_cart();

  return true;
}
add_filter( 'woocommerce_add_to_cart_validation', 'clear_cart_before_adding' );
*/
 
add_filter ('woocommerce_add_to_cart_redirect', 'redirect_to_checkout');

function redirect_to_checkout() {
	
	 if ( ! WC()->cart->is_empty() ) {
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	    	  	 $terms = get_the_terms($cart_item['product_id'] , 'product_cat' );

     } }
    
    if   ( $terms[0]->term_taxonomy_id ==234 || $terms[0]->term_taxonomy_id ==235 ) { //// c'est une location
	    $url_resa='https://naitup.com/reservation/';
    return $url_resa;}
 
}
 

 
	
if( function_exists('acf_add_options_page') ) {acf_add_options_page('Réglages Location');}
	
add_filter( 'easy_booking_display_price', 'easy_booking_remove_price', 10, 1 );

function easy_booking_remove_price( $content ) {
    return "";
}

add_filter( 'easy_booking_start_text', 'wceb_custom_start_text', 10, 2 );

function wceb_custom_start_text( $text, $product = false ) {

    // You have access to the $product variable (not everywhere) in case you want to display a different text for some products
    $text = __( 'Date de retrait', 'textdomain' ); // Translation-ready
    return $text;

}

add_filter( 'easy_booking_end_text', 'wceb_custom_end_text', 10, 2 );

function wceb_custom_end_text( $text, $product = false ) {

    // You have access to the $product variable (not everywhere) in case you want to display a different text for some products
    $text = __( 'Date de retour', 'textdomain' ); // Translation-ready
    return $text;

}
	
	add_filter( 'easy_booking_booking_price_details', 'easy_booking_custom_details', 10, 3 );

function easy_booking_custom_details( $details, $product, $booking_data ) {

	$details = '<p class="booking_details">';

	if (is_weekend($booking_data['start'], $booking_data['duration'])) {$mavaleur='Forfait Week-end';} else {$mavaleur='';}
	
    $details  .=  'Durée de votre réservation: '.$booking_data['duration']. ' nuit(s)<br>'.$mavaleur ;
    $details  .= '</p>';
/*
  Array
(
   [start_date] => 29 Mai 2017
   [start] => 2017-05-29
   [duration] => 1
   [end_date] => 29 Mai 2017
   [end] => 2017-05-29
   [new_price] => 40.34
)
 
*/ 
  return $details;
}


 
 ////////////////////////////// TTC

  add_filter( 'woocommerce_get_price_html', 'kd_custom_price_message' );
 
add_filter( 'woocommerce_cart_item_price', 'kd_custom_price_message' );
add_filter( 'woocommerce_cart_item_subtotal', 'kd_custom_price_message' );  
add_filter( 'woocommerce_cart_subtotal', 'kd_custom_price_message' );  
add_filter( 'woocommerce_cart_total', 'kd_custom_price_message' ); 
add_filter( 'woocommerce_cart_product_subtotal', 'kd_custom_price_message'); 
add_filter( 'woocommerce_cart_contents_total', 'kd_custom_price_message', 10, 1 ); 
function kd_custom_price_message( $price ) {
	$afterPriceSymbol = ' TTC';
	return $price . $afterPriceSymbol;
}
 
///////////////////////////////////////



function is_weekend($date, $duree) {
	$we=false;
	$jour=date('N', strtotime($date));
	if ($jour == 5 && $duree==3) {$we=true;} // vendredi samedi dimanche lundi
	if ($jour == 5 && $duree==2) {$we=true;} // vendredi samedi dimanche
	if ($jour == 5 && $duree==1) {$we=true;} // vendredi samedi 

	if ($jour == 6 && $duree==1) {$we=true;} //  samedi dimanche
	if ($jour == 6 && $duree==2) {$we=true;} //  samedi dimanche lundi
	
return $we;
    
}


 
add_filter( 'easy_booking_two_dates_price', 'easy_booking_return_base_price', 10, 5 );



function get_garantie_price ($duree,$tente)
{
	
$url='csv/'.$tente.'/';	
	////////////  CALCUL CSV le + RECENT ////////////////////////////////////////////
//$path = '/homepages/13/d183772222/htdocs/www/wpsite/wp-content/uploads/'.$url;
$path =  wp_upload_dir()[basedir] .'/' . $url;

$latest_ctime = 0;
$latest_filename = '';    

$d = dir($path);
while (false !== ($entry = $d->read())) {
  $filepath = "{$path}/{$entry}";
  // could do also other checks than just checking whether the entry is a file
  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
    $latest_ctime = filectime($filepath);
    $latest_filename = $entry;
  }
}
////////////  CHARGEMENT CSV le + RECENT ////////////////////////////////////////////

$path= wp_upload_dir()[baseurl].'/'.$url.$latest_filename ;
 
$row = 1;
$array = null;
if (($handle = fopen($path, "r")) !== FALSE) {
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
$array[] = $data;
}
 
  
fclose($handle);
}

 $price = $array[$duree][0] ;  
 return $price;	
}
 
function easy_booking_return_base_price( $booking_price, $product, $_product, $data, $price_type ) {
/*
 	$data['duration']; // Durée de réservation
  $data['start'] ;// Date de début
 $data['end']; // Date de fin
*/
 
$duree =$data['duration']; 

 
if ($product->category_ids[0] ==234) {$tente='duo';}
if ($product->category_ids[0] ==235) {$tente='quatro';}

$url='csv/'.$tente.'/';

 
////////////  CALCUL DE LA SAISON ////////////////////////////////////////////
$lemois = date("m", strtotime($data['start']));
$saison='';
if (in_array($lemois, get_field ('mois_basse', 'option'))) { $url.='basse/'; $saison='bas'; }
if (in_array($lemois, get_field ('mois_moyenne', 'option'))) { $url.='moyenne/'; $saison='moyen'; }
if (in_array($lemois, get_field ('mois_haute', 'option'))) { $url.='haute/';$saison='haut'; }



/////////////////// EST-CE un WE ?? ////////////////////////////////////

if (is_weekend($data['start'], $data['duration'])) 

{   $price = get_field ( 'forfait_we_'.$saison ,'option' );

 }


else
 {
 
////////////  CALCUL CSV le + RECENT ////////////////////////////////////////////

//$path = '/homepages/13/d183772222/htdocs/www/wpsite/wp-content/uploads/'.$url;
$path =  wp_upload_dir()[basedir] .'/' . $url;
$latest_ctime = 0;
$latest_filename = '';    

$d = dir($path);
while (false !== ($entry = $d->read())) {
  $filepath = "{$path}/{$entry}";
  // could do also other checks than just checking whether the entry is a file
  if (is_file($filepath) && filectime($filepath) > $latest_ctime) {
    $latest_ctime = filectime($filepath);
    $latest_filename = $entry;
  }
}
////////////  CHARGEMENT CSV le + RECENT ////////////////////////////////////////////
$path=wp_upload_dir()[baseurl].'/'.$url.$latest_filename ;
 

$row = 1;
$array = null;
if (($handle = fopen($path, "r")) !== FALSE) {
while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
$array[] = $data;
}
 
  
fclose($handle);
}

 
$price = $array[$duree][0] ;  

 
}
 
 
 return wc_format_decimal( $price );    
//    return $price;
}



 
//////////////////:ACF AJOUT DES CHEMINS UPLOAS CSV ////////////////////////////////////////////

add_filter('acf/upload_prefilter/name=dl_csv_basse_saison', 'field_name_upload_prefilter');
function field_name_upload_prefilter($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir');
return $errors;
}
// second filter
function field_name_upload_dir($uploads) {
// here is where we later the path
$uploads['path'] = $uploads['basedir'].'/csv/duo/basse';
$uploads['url'] = $uploads['baseurl'].'/csv/duo/basse';
$uploads['subdir'] = ”;
return $uploads;
}

add_filter('acf/upload_prefilter/name=dl_csv_moyenne_saison', 'field_name_upload_prefilter_moy');
function field_name_upload_prefilter_moy($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir_moy');
return $errors;}
// second filter
function field_name_upload_dir_moy($uploads) {
// here is where we later the path
$uploads['path'] = $uploads['basedir'].'/csv/duo/moyenne';
$uploads['url'] = $uploads['baseurl'].'/csv/duo/moyenne';
$uploads['subdir'] = ”;
return $uploads;
}
 
 
 add_filter('acf/upload_prefilter/name=dl_csv_haute_saison', 'field_name_upload_prefilter_hte');
function field_name_upload_prefilter_hte($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir_hte');
return $errors;
}
// second filter
function field_name_upload_dir_hte($uploads) {
// here is where we later the path
$uploads['path'] = $uploads['basedir'].'/csv/duo/haute';
$uploads['url'] = $uploads['baseurl'].'/csv/duo/haute';
$uploads['subdir'] = ”;
return $uploads;
}





add_filter('acf/upload_prefilter/name=dl_csv_basse_saison_quatro', 'field_name_upload_prefilter_quatro');
function field_name_upload_prefilter_quatro($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir_quatro');
return $errors;
}
// second filter
function field_name_upload_dir_quatro($uploads) {
// here is where we later the path
$uploads['path'] = $uploads['basedir'].'/csv/quatro/basse';
$uploads['url'] = $uploads['baseurl'].'/csv/quatro/basse';
$uploads['subdir'] = ”;
return $uploads;
}

add_filter('acf/upload_prefilter/name=dl_csv_moyenne_saison_quatro', 'field_name_upload_prefilter_moy_quatro');
function field_name_upload_prefilter_moy_quatro($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir_moy_quatro');
return $errors;}
// second filter
function field_name_upload_dir_moy_quatro($uploads) {
// here is where we later the path
$uploads['path'] = $uploads['basedir'].'/csv/quatro/moyenne';
$uploads['url'] = $uploads['baseurl'].'/csv/quatro/moyenne';
$uploads['subdir'] = ”;
return $uploads;
}
 
 
 add_filter('acf/upload_prefilter/name=dl_csv_haute_saison_quatro', 'field_name_upload_prefilter_hte_quatro');
function field_name_upload_prefilter_hte_quatro($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir_hte_quatro');
return $errors;
}
// second filter
function field_name_upload_dir_hte_quatro($uploads) {
// here is where we later the path
$uploads['path'] = $uploads['basedir'].'/csv/quatro/haute';
$uploads['url'] = $uploads['baseurl'].'/csv/quatro/haute';
$uploads['subdir'] = ”;
return $uploads;
}





 add_filter('acf/upload_prefilter/name=dl_csv_garantie_duo', 'field_name_upload_prefilter_gar_duo');
function field_name_upload_prefilter_gar_duo($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir_gar_duo');
return $errors;
}
// second filter
function field_name_upload_dir_gar_duo($uploads) {
// here is where we later the path
$uploads['path'] = $uploads['basedir'].'/csv/duo';
$uploads['url'] = $uploads['baseurl'].'/csv/duo';
$uploads['subdir'] = ”;
return $uploads;
}

 add_filter('acf/upload_prefilter/name=dl_csv_garantie_quatro', 'field_name_upload_prefilter_gar_quatro');
function field_name_upload_prefilter_gar_quatro($errors) {
// in this filter we add a WP filter that alters the upload path
add_filter('upload_dir', 'field_name_upload_dir_gar_quatro');
return $errors;
}
// second filter
function field_name_upload_dir_gar_quatro($uploads) {
// csv path
$uploads['path'] = $uploads['basedir'].'/csv/quatro';
$uploads['url'] = $uploads['baseurl'].'/csv/quatro';
$uploads['subdir'] = ”;
return $uploads;
}

add_action( 'woocommerce_cart_calculate_fees','woocommerce_custom_surcharge' );
function woocommerce_custom_surcharge() {
  global $woocommerce;

	if ( ! WC()->cart->is_empty() ) {
    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
	    	  	 $terms = get_the_terms($cart_item['product_id'] , 'product_cat' );

        $duration = $cart_item['_booking_duration'];
    } 
    
    if   ( $terms[0]->term_taxonomy_id ==234 || $terms[0]->term_taxonomy_id ==235 ) { //// c'est une location
 if ($terms[0]->term_taxonomy_id ==234) {$tente='duo';}
 if ($terms[0]->term_taxonomy_id ==235) {$tente='quatro';}

    
	if (!$_POST || (is_admin() && !is_ajax())) {
		return;
	}
	if (isset($_POST['post_data'])) {
		parse_str($_POST['post_data'], $form_data);
	} else {
		$form_data = $_POST; // fallback for final checkout (non-ajax)
	}

	if (isset($form_data['arrhes']) && $form_data['arrhes'] == 'off') {
				//on redef les variables apres order
				$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$textkev= "Il y a 30 % d'arrhes : ";
				$percentage = 0.7;
				$price_product =  WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] );
				$theprice = $_product->get_price();
				$price_ope =  $theprice * $percentage ;
				$price_reduc = $theprice - $price_ope;
				$garantie_loc = get_garantie_price ($duration,$tente);	
				$garantie_loc_neg =  - $garantie_loc;
				$duree_loc = ($duration*2);
				$duree_loc_neg = -($duration*2);
				$surcharge = - $price_ope;
				if (isset($form_data['linge']) && $form_data['linge'] == 'on') {
					$currency ="€ TTC";
					$new_total_loc =  ($theprice + $duree_loc). $currency;
				}
				if (isset($form_data['warranty']) && $form_data['warranty'] == 'on') {
					$currency ="€ TTC";
					$new_total_loc = ( $theprice + $garantie_loc) . $currency;
				}
				if ($form_data['warranty'] == 'on' && $form_data['linge'] == 'on') {
					$currency ="€ TTC";
					$new_total_loc =  ($theprice + $garantie_loc + $duree_loc) . $currency;
				}
				if ($form_data['warranty'] == 'off' && $form_data['linge'] == 'off') {
					$currency ="€ TTC";
					$new_total_loc =  $theprice . $currency ;
				}
			WC()->cart->add_fee(__('Solde (après Arrhes de 30% HORS Tranquilité/LINGE SI COCHÉ) pour une somme totale de  '.$new_total_loc, 'arrhes'), ($surcharge), false, '');
			
	}

	
	//changer texte total page checkout
	add_filter('gettext', 'wc_renaming_checkout_total', 20, 3);
function wc_renaming_checkout_total( $translated_text, $untranslated_text, $domain ) {

    if( !is_admin() && is_checkout ) {
		global $woocommerce;
				
        if( $untranslated_text == 'Total' )
            $translated_text = __( 'à payer aujourd\'hui (30%)  ','theme_slug_domain' );
	
    }
	
    return $translated_text;
}
add_filter('gettext', 'translate_reply');
add_filter('ngettext', 'translate_reply');

function translate_reply($translated) {
$translated = str_ireplace('Sous-total', 'SOUS-TOTAL (HORS Tranquilité/LINGE SI COCHÉ)', $translated);
return $translated;
}	


}}
}

