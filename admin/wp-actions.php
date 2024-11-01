<?php

namespace qpaypro_woocommerce\Admin;

function dl_qpp_gateway_init() {
    include_once('helpers/wp-qpaypro-woocommerce-admin.php');
}
add_action( 'plugins_loaded', __NAMESPACE__ . '\\dl_qpp_gateway_init', 0 );



function dl_qpp_custom_scripts() {
    wp_enqueue_script('wc_qpp_cleave', plugin_dir_url( __DIR__ ) . 'dist/node_modules/cleave.js/dist/cleave.min.js');
    wp_enqueue_script( 'dl-qpp-custom-js', plugin_dir_url( __DIR__ ) . 'dist/assets/js/app.min.js', array( 'jquery'),filemtime( plugin_dir_path( __DIR__ ) . 'dist/assets/js/app.min.js' ), false );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\dl_qpp_custom_scripts' ); 