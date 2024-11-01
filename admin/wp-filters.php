<?php

namespace qpaypro_woocommerce\Admin;

function dl_qpp_add_to_gateways( $gateways ) {
    $gateways[] = 'qpaypro_woocommerce\Admin\Helpers\WC_Gateway_QPayPro';
    return $gateways;
}
add_filter( 'woocommerce_payment_gateways', __NAMESPACE__ . '\\dl_qpp_add_to_gateways' );

function dl_qpp_gateway_plugin_links( $links ) {
    $plugin_links = array(
            '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=checkout&section=QPayPro' ) . '">' . __( 'Configure', 'wp-qpaypro-woocommerce' ) . '</a>'
    );
    return array_merge( $plugin_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), __NAMESPACE__ . '\\dl_qpp_gateway_plugin_links' );