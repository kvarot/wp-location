<?php global $wpo_wcpdf; ?>
<?php do_action( 'wpo_wcpdf_before_document', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>

<table class="head container">
	<tr>
		<td class="header">
		<?php
		if( $wpo_wcpdf->get_header_logo_id() ) {
			$wpo_wcpdf->header_logo();
		} else {
			echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) );
		}
		?>
		</td>
		<td class="shop-info">
			<div class="shop-name"><h3>Naïtup Tente Hussarde</h3></div>
			<div class="shop-address"> 235 avenue des chênes rouges,</div>
			<div class="shop-address"> 30100 Alès, France</div>
			<div class="shop-address"> Phone : (+33) 09 81 86 04 93</div>
			
		</td>
		<td class="shop-info">
			
			<div class="shop-address"> Mail:  <a href="mailto:contact@naitup.com">contact@naitup.com</a> </div>
			<div class="shop-address"> Web : <a href="https://naitup.com">https://naitup.com </a></div>
			<div class="shop-address" >  TVA : FR15498795244 </div>
			<div class="shop-address" >  SIRET : 49879524400025 </div>
		</td>
	</tr>
</table>

<h1 class="document-type-label">
<?php if( $wpo_wcpdf->get_header_logo_id() ) echo apply_filters( 'wpo_wcpdf_invoice_title', __( 'Invoice', 'wpo_wcpdf' ) ); ?>
				&nbsp;&nbsp; <?php $wpo_wcpdf->invoice_number(); ?>
</h1>

<?php do_action( 'wpo_wcpdf_after_document_label', $this->get_type(), $this->order ); ?>

<table class="order-data-addresses" >
	<tr>
		<td class="address billing-address" >
			<!-- <h3><?php _e( 'Billing Address:', 'wpo_wcpdf' ); ?></h3> -->
			<?php $wpo_wcpdf->billing_address(); ?>
			<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_email']) ) { ?>
			<div class="billing-email"><?php $wpo_wcpdf->billing_email(); ?></div>
			<?php } ?>
			<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_phone']) ) { ?>
			<div class="billing-phone"><?php $wpo_wcpdf->billing_phone(); ?></div>
			<?php } ?>
		</td>
		<td class="address shipping-address">
			<?php if ( isset($wpo_wcpdf->settings->template_settings['invoice_shipping_address']) && $wpo_wcpdf->ships_to_different_address()) { ?>
			<h3><?php _e( 'Ship To:', 'wpo_wcpdf' ); ?></h3>
			<?php $wpo_wcpdf->shipping_address(); ?>
			<?php } ?>
		</td>
		<td class="order-data">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
				<?php if ( isset($wpo_wcpdf->settings->template_settings['display_number']) && $wpo_wcpdf->settings->template_settings['display_number'] == 'invoice_number') { ?>
				<tr class="invoice-number">
					<th><?php _e( 'Invoice Number:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->invoice_number(); ?></td>
				</tr>
				<?php } ?>
				<?php if ( isset($wpo_wcpdf->settings->template_settings['display_date']) && $wpo_wcpdf->settings->template_settings['display_date'] == 'invoice_date') { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Invoice Date:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->invoice_date(); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->order_number(); ?></td>
				</tr>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->order_date(); ?></td>
				</tr>
				<tr class="payment-method">
					<th><?php _e( 'Payment Method:', 'wpo_wcpdf' ); ?></th>
					<td><?php $wpo_wcpdf->payment_method(); ?></td>
				</tr>
				<?php do_action( 'wpo_wcpdf_after_order_data', $wpo_wcpdf->export->template_type, $wpo_wcpdf->export->order ); ?>
			</table>			
		</td>
	</tr>
</table>


<table class="order-data" style="">
	<tr>
		<td class="numbers">
			<table>
				<?php do_action( 'wpo_wcpdf_before_order_data', $this->get_type(), $this->order ); ?>
				<?php if ( isset($this->settings['display_number'])) { ?>
				<tr class="invoice-number">
					<th><?php _e( 'Invoice Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_number(); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-number">
					<th><?php _e( 'Order Number:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_number(); ?></td>
				</tr>
				<?php do_action( 'wpo_wcpdf_after_order_data', $this->get_type(), $this->order ); ?>
			</table>
		</td>
		<td class="dates">
			<table>
				<?php if ( isset($this->settings['display_date'])) { ?>
				<tr class="invoice-date">
					<th><?php _e( 'Invoice Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->invoice_date(); ?></td>
				</tr>
				<?php } ?>
				<tr class="order-date">
					<th><?php _e( 'Order Date:', 'woocommerce-pdf-invoices-packing-slips' ); ?></th>
					<td><?php $this->order_date(); ?></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<?php do_action( 'wpo_wcpdf_before_order_details', $this->get_type(), $this->order ); ?>

<table class="order-details">
	<thead>
		<tr style="color: white;
	background-color: black;text-align:center">
			<?php 
			foreach ( wpo_wcpdf_templates_get_table_headers( $this ) as $column_key => $header_data ) {
				printf('<th class="%s"><span>%s</span></th>', $header_data['class'], $header_data['title']);
			}
			?>
		</tr> 
	</thead>
	<tbody style="border:1px solid #E9E9E9">
		<?php
		$tbody = wpo_wcpdf_templates_get_table_body( $this );
		if( sizeof( $tbody ) > 0 ) {
			foreach( $tbody as $item_id => $item_columns ) {
				$row_class = apply_filters( 'wpo_wcpdf_item_row_class', $item_id, $this->get_type(), $this->order, $item_id );
				printf('<tr class="%s" style="border:1px solid #E9E9E9">', $row_class);
				foreach ($item_columns as $column_key => $column_data) {
					printf('<td class="%s" style="border:1px solid #E9E9E9"><span>%s</span></td>', $column_data['class'], $column_data['data']);
				}
				echo '</tr>';
			}
		}
		?>
	</tbody>
</table>


<table class="notes-totals">
	<tr>
		<td class="notes">
			<?php do_action( 'wpo_wcpdf_after_order_details', $this->get_type(), $this->order ); ?>
			<?php do_action( 'wpo_wcpdf_before_customer_notes', $this->get_type(), $this->order ); ?>
			<div class="customer-notes">
				<?php if ( $this->get_shipping_notes() ) : ?>
					<h3><?php _e( 'Customer Notes', 'woocommerce-pdf-invoices-packing-slips' ); ?></h3>
					<?php $this->shipping_notes(); ?>
				<?php endif; ?>
			</div>
			<?php do_action( 'wpo_wcpdf_after_customer_notes', $this->get_type(), $this->order ); ?>
		</td>
		<td class="totals" style="border:1px solid black">
			<table class="totals-table">
				<?php
				$totals = wpo_wcpdf_templates_get_totals( $this );
				if( sizeof( $totals ) > 0 ) {
					foreach( $totals as $total_key => $total_data ) {
						?>
						<tr class="<?php echo $total_data['class']; ?>">
							<th class="description" style="border:1px solid black"><span><?php echo $total_data['label']; ?></span></th>
							<td class="price" style="border:1px solid black"><span class="totals-price"><?php echo $total_data['value']; ?></span></td>
						</tr>
						<?php
					}
				}
				?>
		
				
			</table>
		</td>
	</tr>
</table>

<?php if ( $this->get_footer() ): ?>
<div id="footer">
	<?php $this->footer(); ?>
</div>
<?php endif; ?>
<?php do_action( 'wpo_wcpdf_after_document', $this->get_type(), $this->order ); ?>