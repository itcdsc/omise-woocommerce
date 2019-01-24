<?php
defined( 'ABSPATH' ) or die( 'No direct script access allowed.' );

function register_omise_installment() {
	require_once dirname( __FILE__ ) . '/class-omise-payment.php';

	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	if ( class_exists( 'Omise_Payment_Installment' ) ) {
		return;
	}

	/**
	 * @since 3.3
	 */
	class Omise_Payment_Installment extends Omise_Payment {
		public function __construct() {
			parent::__construct();

			$this->id                 = 'omise_installment';
			$this->has_fields         = true;
			$this->method_title       = __( 'Omise Installment', 'omise' );
			$this->method_description = wp_kses(
				__( 'Accept payment through <strong>Installment</strong> via Omise payment gateway.', 'omise' ),
				array( 'strong' => array() )
			);

			$this->init_form_fields();
			$this->init_settings();

			$this->title       = $this->get_option( 'title' );
			$this->description = $this->get_option( 'description' );
		}

		/**
		 * @see WC_Settings_API::init_form_fields()
		 * @see woocommerce/includes/abstracts/abstract-wc-settings-api.php
		 */
		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'   => __( 'Enable/Disable', 'omise' ),
					'type'    => 'checkbox',
					'label'   => __( 'Enable Omise Installment Payment', 'omise' ),
					'default' => 'no'
				),

				'title' => array(
					'title'       => __( 'Title', 'omise' ),
					'type'        => 'text',
					'description' => __( 'This controls the title which the user sees during checkout.', 'omise' ),
					'default'     => __( 'Installment', 'omise' ),
				),

				'description' => array(
					'title'       => __( 'Description', 'omise' ),
					'type'        => 'textarea',
					'description' => __( 'This controls the description which the user sees during checkout.', 'omise' )
				),
			);
		}
	}

	if ( ! function_exists( 'add_omise_installment' ) ) {
		/**
		 * @param  array $methods
		 *
		 * @return array
		 */
		function add_omise_installment( $methods ) {
			$methods[] = 'Omise_Payment_Installment';
			return $methods;
		}

		add_filter( 'woocommerce_payment_gateways', 'add_omise_installment' );
	}
}