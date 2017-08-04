<?php ?>

<div class="wceb_custom_fields">

	<p class="form-row form-row-wide">

		<label for="start_time"><?php _e( 'Heure de retrait' ,'' ); ?></label>
		<select name="_start_time" id="start_time">
			<option value="9h"><?php _e( '9h' ,'' ); ?></option>
			<option value="10h"><?php _e( '10h' ,'' ); ?></option>
			<option value="11h"><?php _e( '11h' ,'' ); ?></option>
 			<option value="14h"><?php _e( '14h' ,'' ); ?></option>
			<option value="15h"><?php _e( '15h' ,'' ); ?></option>
			<option value="16h"><?php _e( '16h' ,'' ); ?></option>
			<option value="17h"><?php _e( '17h' ,'' ); ?></option>
		</select>

	</p>

	<p class="form-row form-row-wide">

		<label for="end_time"><?php _e( 'Heure de retour' ,'' ); ?></label>
		<select name="_end_time" id="end_time">
			<option value="9h"><?php _e( '9h' ,'' ); ?></option>
			<option value="10h"><?php _e( '10h' ,'' ); ?></option>
			<option value="11h"><?php _e( '11h' ,'' ); ?></option>
 			<option value="14h"><?php _e( '14h' ,'' ); ?></option>
			<option value="15h"><?php _e( '15h' ,'' ); ?></option>
			<option value="16h"><?php _e( '16h' ,'' ); ?></option>
			<option value="17h"><?php _e( '17h' ,'' ); ?></option>
		</select>

	</p>

	<p class="form-row form-row-wide">

		<label for="vehicule_type"><?php _e( 'Marque, modèle et année du véhicule' ,'' ); ?></label>
		<input type="text" name="_vehicule_type" id="vehicule_type" value="" maxlength="50">

	</p>

	<p class="form-row form-row-wide">

		<label for="vehicule_height"><?php _e( 'Hauteur du véhicule' ,'' ); ?></label>
		<select name="_vehicule_height" id="vehicule_height">
			<option value="less_180"><?php _e( 'Moins d\'1M80' ,'' ); ?></option>
			<option value="more_180"><?php _e( 'Plus d\'1M80' ,'' ); ?></option>
		</select>

	</p>

</div>

<div class="clearboth"></div>