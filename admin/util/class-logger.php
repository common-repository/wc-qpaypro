<?php

namespace qpaypro_woocommerce\Admin\Util;


class Logger
{

    function __construct() {

    }

    public function log($message) {
        $date = new \DateTime('now');
        $message = $date->format('D M d, Y G:i') . ": " . $message . "\n";
        error_log( $message , 3, "wp-qpaypro-errors.log");
    }
}