<?php

namespace qpaypro_woocommerce\Admin\Helpers;

    class WC_Gateway_QPayPro extends \WC_Payment_Gateway {
        public function __construct(){
            $this->id                 = 'qpaypro';
            $this->icon               = plugin_dir_url(__FILE__) . 'ccs.png' ;
			$this->method_title       = __( 'QPayPro', 'wp-qpaypro-woocommerce' );
            $this->method_description = __( 'Allows you to receive online payments through QPayPro. ', 'wp-qpaypro-woocommerce' );
            $this->supports = array( 'products' );

            // Bool. Can be set to true if you want payment fields to show on the checkout
            // if doing a direct integration, which we are doing in this case
            $this->has_fields = true;
            // Supports the default credit card form
            //$this->supports = array( 'default_credit_card_form' );
            
            
            // Load the settings.
            $this->init_form_fields();
            $this->init_settings();

            
            $this->enabled = $this->get_option('enabled');
            // Define user set variables
            //$this->title        = strlen($this->get_option( 'title' )) > 0 ? $this->get_option( 'title' ) : 'Tarjeta de Crédito via QPayPro';
            $this->title        = 'Tarjeta de crédito por QPayPro (Visa - Mastercard)';
            //$this->description  = $this->get_option( 'description' );
            //$this->instructions = $this->get_option( 'instructions', $this->description );

            // Actions
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( &$this, 'process_admin_options' ) );
            add_action( 'woocommerce_thankyou_' . $this->id, array( $this, 'thankyou_page' ) );

            // Customer Emails
            add_action( 'woocommerce_email_before_order_table', array( $this, 'email_instructions' ), 10, 3 );
            add_action('admin_notices', array( $this, 'dl_qpp_admin_notice' ));
        }

        function dl_qpp_admin_notice(){
        	echo '<div class="notice notice-info">
				<p>' . __('You are using the <strong>QPayPro for WooCommerce</strong> plugin developed by <a href="https://digitallabs.agency" target="_blank">Digital Labs</a>. If you need assistance configuring the plugin, help with your eCommerce site or just want to say hi, feel free to contact us <a href="https://digitallabs.agency/contacto" target="_blank">here</a>. We will be happy to work with you.', 'wp-qpaypro-woocommerce') . '</p>
				</div>';
		}

        function is_valid_for_use() {
            return true;
        }

        function is_available() {
            $return = true;
            if($this->enabled != 'yes') {
                $return = false;
            }
            return $return;
        }
        function payment_fields() {
            global $woocommerce;
            
            if ($this->description) {
                echo wpautop(wptexturize($this->description));
            }
            
            $sessionID = uniqid();
            WC()->session->set('sessionID' , $sessionID);
            
            $cards = array("VISA","MasterCard");

            $year_options = '';
            for ($i = date('Y'); $i <= (date('Y') + 10); $i++) {
                $twoYearDigit  = substr($i, -2);
                $year_options .= '<option value="' . $twoYearDigit . '">' . $i . '</option>';
            }
			
			
			if ($this->get_option('visaencuotas')=='Yes'){
			$divVisaEnCuotas = '<p class="form-row form-row-first">
										<label for="visa_en_cuotas" id="name_visa_cuotas">' . __( 'Method of payment', 'wp-pagalo-woocommerce' ) . '</label>
										<select name="qpaypro_visaencuotas" id="qpaypro_visaencuotas" class="woocommerce-select" >
												<option value="0" selected>' . __( 'Up front', 'wp-qpaypro-woocommerce' ) . '</option>
												<option value="3">3 ' . __('installments', 'wp-qpaypro-woocommerce') . '</option>
												<option value="6">6 ' . __('installments', 'wp-qpaypro-woocommerce') . '</option>
												<option value="10">10 ' . __('installments', 'wp-qpaypro-woocommerce') . '</option>
												<option value="12">12 ' . __('installments', 'wp-qpaypro-woocommerce') . '</option>
										</select>
								</p>';
			}else{
				$divVisaEnCuotas ='';
			}
            echo '	<fieldset>

                        <p class="form-row ">
                                <label for="is_ccnum">' . __('Credit Card Holder\'s name', 'wp-qpaypro-woocommerce') . ':<span class="required">*</span></label>
                                <input type="text" class="input-text" id="_ccnum" name="qpaypro_ccname" required="" maxlength="60">
                        </p>
                          <div class="clear"></div>
                        <p class="form-row ">
                                <label for="is_ccnum">' . __('Credit Card Number', 'wp-qpaypro-woocommerce') . ':<span class="required">*</span></label>
                                <input id="qpaypro_ccnum" class="input-text wc-credit-card-form-card-number" inputmode="numeric" autocomplete="cc-number" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="•••• •••• •••• ••••" name="qpaypro_ccnum" maxlength="20" required >
                        </p>

                        <div class="clear"></div>

                        <p class="form-row form-row-first"><label for="cc-expire-month">'
                                . __( 'Expiry (MM/YY)', 'wp-pagalo-woocommerce' ) . ' <span class="required">*</span>
                            </label>
                            <input name="qpaypro_expdate" id="qpaypro_expdate" class="input-text wc-credit-card-form-card-expiry" inputmode="numeric" autocomplete="cc-exp" autocorrect="no" autocapitalize="no" spellcheck="no" type="tel" placeholder="' . __('MM / YY', 'wp-pagalo-woocommerce') . '" />
                        </p>
                        
                        <div class="clear"></div>
						
                        <p class="form-row ">
                                <label for="is_cvv">CVV <span class="required">*</span></label>
                                <input type="number" class="input-text" id="qpaypro_cvv" name="qpaypro_cvv" max="9999" min="0" style="width:80px" required>
								<span style="padding-left: 10px;">' . __('3 or 4 digits', 'wp-qpaypro-woocommerce') . '</span>
                        </p>
						
						<div class="clear"></div>
						'.$divVisaEnCuotas;
						
                $src = 'https://h.online-metrix.net/fp/tags.js?org_id='.($this->get_option('Mode')=='Sandbox'?'1snn5n9w':'k8vif92e').'&amp;session_id='.$this->get_option('merchantid').$sessionID;

                wp_enqueue_script( 'dl-qpp-online-metrix', $src, array( 'jquery' ) , '1,0' , false );
						
				echo '<!-- DEVICE FINGERPRINT CODE -->
                        <noscript>
                        <iframe style="width: 100px; height: 100px; border: 0; position: absolute; top: -5000px;"
                        src="https://h.online-metrix.net/fp/tags?org_id='.($this->get_option('Mode')=='Sandbox'?'1snn5n9w':'k8vif92e').'&amp;session_id='.$this->get_option('merchantid').$sessionID.'" >
                        </iframe>
                        </noscript>
                        <!-- END DEVICE FINGERPRINT CODE -->
                        
                        <div class="clear"></div>
                 </fieldset>	';

            echo '
                 <script>
                 var cleave = new Cleave("#qpaypro_ccnum", {
                     creditCard: true,
                     onCreditCardTypeChanged: function (type) {
                         // update UI ...
                     }
                 });
                 var cleave2 = new Cleave("#qpaypro_expdate", {
                    date: true,
                    datePattern: ["m", "y"]
                });
                 </script>	';

        }
        

        public function init_form_fields() {

            $this->form_fields = apply_filters( 'wc_QPayPro_form_fields', array(

                'enabled' => array(
                        'title'   => __( 'Habilitar/Deshabilitar', 'wp-qpaypro-woocommerce' ),
                        'type'    => 'checkbox',
                        'label'   => __( 'Activar pasarela de pago', 'wp-qpaypro-woocommerce' ),
                        'default' => 'yes'
                ),
                'title' => array(
                        'title'       => __( 'Titulo', 'wp-qpaypro-woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'QPayPro', 'wp-qpaypro-woocommerce' ),
                        'default'     => __( 'QPayPro', 'wp-qpaypro-woocommerce' ),
                        'desc_tip'    => true,
                ),
                'publickey' => array(
                        'title'       => __( 'Public Key (x_login)', 'wp-qpaypro-woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'QPayPro Public Key.', 'wp-qpaypro-woocommerce' ),
                        'default'     => __( '', 'wp-qpaypro-woocommerce' ),
                        'desc_tip'    => true,
                ),

                'privatekey' => array(
                        'title'       => __( 'Private Key (x_private_key)', 'wp-qpaypro-woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'QPayPro Private Key.', 'wp-qpaypro-woocommerce' ),
                        'default'     => __( '', 'wp-qpaypro-woocommerce' ),
                        'desc_tip'    => true,
                ),

                'apisecret' => array(
                        'title'       => __( 'API Secret (x_api_secret)', 'wp-qpaypro-woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'QPayPro API Secret.', 'wp-qpaypro-woocommerce' ),
                        'default'     => __( '', 'wp-qpaypro-woocommerce' ),
                        'desc_tip'    => true,
                ),
                'merchantid' => array(
                        'title'       => __( 'Merchant ID', 'wp-qpaypro-woocommerce' ),
                        'type'        => 'text',
                        'description' => __( 'QPayPro Merchant ID.', 'wp-qpaypro-woocommerce' ),
                        'default'     => __( '', 'wp-qpaypro-woocommerce' ),
                        'desc_tip'    => true,
                ),
                'Mode' => array(
                    'title' => __('Mode', 'wp-qpaypro-woocommerce'),
                    'type' => 'select',
                    'description' => __('Modo QPayPro Live o Sandbox.', 'wp-qpaypro-woocommerce'),
                    'options' => array(
                        'Sandbox' => __('Sandbox', 'wp-qpaypro-woocommerce' ),
                        'Live' => __('Live', 'wp-qpaypro-woocommerce' )
                    )
                ),
                'visaencuotas' => array(
                    'title' => __('Visa Cuotas', 'wp-qpaypro-woocommerce'),
                    'type' => 'select',
                    'description' => __('QPayPro Visacuotas.', 'wp-qpaypro-woocommerce'),
					'desc_tip'    => true,
                    'options' => array(
                        'Yes' => __('Yes', 'wp-qpaypro-woocommerce' ),
                        'No' => __('No', 'wp-qpaypro-woocommerce' )
                    )
                )
            ) );
        }
       public function admin_options(){
            echo '<h3>'.__('QPayPro Payment Gateway', 'wp-qpaypro-woocommerce').'</h3>';
            echo '<p>'.__('QPayPro Payment Gateway', 'wp-qpaypro-woocommerce').'</p>';
            echo '<table class="form-table">';
            // Generate the HTML For the settings form.
            $this -> generate_settings_html();
            echo '</table>';
        }
        public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
            if ( ! $sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
				if ( $this->instructions ){
                    echo wpautop( wptexturize( $this->instructions ) ) . PHP_EOL;
				}
            }
        }
        /**
         * Output for the order received page.
         */
        public function thankyou_page() {
            if ( @$this->instructions ) {
                    echo wpautop( wptexturize( $this->instructions ) );
            }
        }

        public function process_payment( $order_id ) {

           

            global $woocommerce;
            
            $sessionID = WC()->session->get('sessionID');
            
            $order = wc_get_order( $order_id );
            
            if ($this->get_option('Mode') == 'Live') {
                    $url = 'https://payments.qpaypro.com/checkout/api_v1';
            } elseif ($this->get_option('Mode') == 'Sandbox') {
                    $url = 'https://sandbox.qpaypro.com/payment/api_v1';
            }
            if ( !$_POST['qpaypro_ccname']){
                wc_add_notice( __( 'Credit Card holder name is required.', 'wp-qpaypro-woocommerce' ), 'error' );
                return;
            }
            if ( !$_POST['qpaypro_ccnum']){
                wc_add_notice( __( 'Credit card number is required.', 'wp-qpaypro-woocommerce' ), 'error' );
                return;
            }
            if ( !$_POST['qpaypro_expdate']){
                wc_add_notice( __( 'Credit card expiry date is required.', 'wp-qpaypro-woocommerce' ), 'error' );
                return;
            }
            if ( !$_POST['qpaypro_cvv']){
                wc_add_notice( __( 'Credit card CVV is required.', 'wp-qpaypro-woocommerce' ), 'error' );
                return;
            }
            if ( $_POST['qpaypro_ccname'] &&  $_POST['qpaypro_ccnum'] &&  $_POST['qpaypro_expdate'] &&  $_POST['qpaypro_cvv']){
              $data = array();

              $products = WC()->cart->get_cart();
             // $productId = $products[0]['product_id'];
              if(count($products)>0){
                $x_line_item = '';
                foreach($products as $product){
                  $productId = $product['product_id'];
                  $WCproduct = wc_get_product($productId);
                  $x_line_item .=   $WCproduct->get_title()."<|>".$product['product_id']."<|><|>".$product['quantity']."<|>".$product['line_subtotal']."<|>";
                }
              } else {
                $x_line_item = 0;
              }
              
              $data['x_login'] = (string) $this->get_option('publickey');
              $data['x_private_key'] = (string) $this->get_option('privatekey');
              $data['x_api_secret'] = (string) $this->get_option('apisecret');
              $data['x_fp_sequence'] = $order_id;
              $data['x_fp_timestamp'] = time();
              $data['x_relay_response'] = 'false';
              $data['x_product_id'] = 0;
              $data['x_line_item'] = $x_line_item;
              $data['x_audit_number'] = str_pad($order_id, 6, "0", STR_PAD_LEFT);
              $data['x_relay_url'] = "none";
              $data['x_first_name'] = html_entity_decode($order->get_billing_first_name(), ENT_QUOTES, 'UTF-8');
              $data['x_last_name'] = html_entity_decode($order->get_billing_last_name(), ENT_QUOTES, 'UTF-8');
              $data['x_company'] = html_entity_decode($order->get_billing_company(), ENT_QUOTES, 'UTF-8');
              $data['x_company'] = strlen($data['x_company']) <= 0 ? "No Company Name Provided" : $data['x_company'];
              $data['x_address'] = html_entity_decode($order->get_billing_address_1(), ENT_QUOTES, 'UTF-8');
              $data['x_city'] = html_entity_decode($order->get_billing_city(), ENT_QUOTES, 'UTF-8');
              $data['x_state'] = html_entity_decode($order->get_billing_state(), ENT_QUOTES, 'UTF-8');
              $data['x_zip'] = html_entity_decode($order->get_billing_postcode(), ENT_QUOTES, 'UTF-8');
              $data['x_country'] = html_entity_decode($order->get_billing_country(), ENT_QUOTES, 'UTF-8');
              $data['x_phone'] = $order->get_billing_phone();
              $data['x_email'] = $order->get_billing_email();
              $data['x_description'] = html_entity_decode('QPayPro para pago de WooCommerce: ID de pedido de WooCommerce:'.$order_id.' para '.$data['x_email'] , ENT_QUOTES, 'UTF-8');
              //$data['x_amount'] = (float) $order->total - (float) $order->shipping_total;
              $data['x_amount'] = (float) $order->get_total();
              $data['x_freight'] = (float) $order->get_shipping_total();
              $data['x_currency_code'] = get_woocommerce_currency();
              $data['x_method'] = 'CC';
              $data['x_type'] = 'AUTH_ONLY';
              $data['cc_number'] = str_replace(' ', '', sanitize_text_field( $_POST['qpaypro_ccnum'] ) );
              $data['cc_exp'] = str_replace(' ', '', sanitize_text_field( $_POST['qpaypro_expdate'] ));
              $data['cc_cvv2'] = sanitize_text_field( $_POST['qpaypro_cvv'] );
              $data['cc_name'] = sanitize_text_field( $_POST['qpaypro_ccname'] );
              $data['x_invoice_num'] = $order_id;
              //$data['x_solution_id'] = 'A1000015';
              /* Customer Shipping Address Fields */
              $data['x_ship_to_first_name'] = html_entity_decode($order->get_shipping_first_name(), ENT_QUOTES, 'UTF-8');
              $data['x_ship_to_last_name'] = html_entity_decode($order->get_shipping_last_name(), ENT_QUOTES, 'UTF-8');
              $data['x_ship_to_company'] = html_entity_decode($order->get_shipping_company(), ENT_QUOTES, 'UTF-8');
              $data['x_ship_to_address'] = html_entity_decode($order->get_shipping_address_1(),  ENT_QUOTES, 'UTF-8');
              $data['x_ship_to_city'] = html_entity_decode($order->get_shipping_city(), ENT_QUOTES, 'UTF-8');
              $data['x_ship_to_state'] = html_entity_decode($order->get_shipping_state(), ENT_QUOTES, 'UTF-8');
              $data['x_ship_to_zip'] = html_entity_decode($order->get_shipping_postcode(), ENT_QUOTES, 'UTF-8');
              $data['x_ship_to_country'] = html_entity_decode($order->get_shipping_country(), ENT_QUOTES, 'UTF-8');
              $data['device_fingerprint_id'] = $sessionID;
			  if ($this->get_option('visaencuotas')=='Yes')
                $data['visaencuotas'] = sanitize_text_field( $_POST['qpaypro_visaencuotas'] );
              else
                $data['visaencuotas'] = 0;

            $qpaypro = new QPayPro_Helper();
            $qpaypro->set_request_data($data);
            $qpaypro->set_api_url($url);
            $response = $qpaypro->make_payment();

              //var_dump($response->responseCode);
				
              if($response['result']){
                  $order->add_order_note( $response['message'] . __( 'QPayPro payment completed.', 'wp-qpaypro-woocommerce' ) );
                  // Mark order as Paid
                  $order->payment_complete();
                  // save transaction ID
                  $order->set_transaction_id($response['idTransaction']);

                  // save customer order
                    $order->save();



                  // Empty the cart (Very important step)
                  $woocommerce->cart->empty_cart();
                  // Redirect to thank you page
                  unset( $woocommerce->session->order_awaiting_payment );
                  return array(
                          'result'   => 'success',
                          'redirect' => $this->get_return_url( $order ),
                  );
              } else {
                  wc_add_notice( print_r($response['message'],true), 'error' );
                  // Add note to the order for your reference
                  $order->add_order_note( 'Error: '. print_r($response['message'],true)  );
              }
            }

        }
    } // end \WC_Gateway_Offline class