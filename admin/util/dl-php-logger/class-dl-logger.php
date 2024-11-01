<?php

namespace DigitalLabs_util;

if( !class_exists('DigitalLabs_util\DL_Logger') ) {

class DL_Logger {

    private $log_file;
    private $log_dir;

    function __construct($log_file = 'dl-debug', $log_dir = '' ) {
        if( !defined( "DL_DEBUG" ) || !DL_DEBUG ) {
            return;
        }
        $date = new \DateTime('now');
        $date->sub(new \DateInterval('PT6H'));
        $this->log_file = $log_file . "-" . $date->format('Ymd') . '.log';
        $this->log_dir = WP_CONTENT_DIR . "/logs/";

        if( defined( "DL_DEBUG_LOG" ) && is_string( DL_DEBUG_LOG ) ) {
            $this->log_dir = DL_DEBUG_LOG;

            $log_dir_lastchar = $this->log_dir[-1];
            if( strcmp($log_dir_lastchar, "/") !== 0 ) {
                $this->log_dir .= "/";
            }
        }

        if( $log_dir != '' ) {
            $this->log_dir = $log_dir;
        }

        if( !file_exists( $this->log_dir ) ) {
            mkdir( $this->log_dir, 0770, true );
        }

        if( !file_exists($this->log_dir . $this->log_file ) ) {
            $fp = fopen($this->log_dir . $this->log_file, 'w');
            $content = "Log file created on " . $date->format('D M d, Y G:i:s') . "\n";
            fwrite($fp, $content);
            fclose($fp);
            chmod($this->log_dir . $this->log_file, 0620);
        }
    }

    public function log($message ) {
        // echo $message . '<br><br>';
        if( !defined( 'DL_DEBUG' ) || !DL_DEBUG ) {
            return;
        }
        $date = new \DateTime('now');
        $date->sub(new \DateInterval('PT6H'));
        $message = '[' . $date->format('D M d, Y G:i:s') . "] [INFO] " . $message . "\n";
        error_log( $message , 3, $this->log_dir . $this->log_file );
    }

    public function error($message ) {
        // echo $message . '<br><br>';
        if( defined( 'DL_DEBUG' ) || !DL_DEBUG ) {
            return;
        }
        $date = new \DateTime('now');
        $date->sub(new \DateInterval('PT6H'));
        $message = '[' . $date->format('D M d, Y G:i:s') . "] [ERROR] " . $message . "\n";
        error_log( $message , 3, $this->log_dir . $this->log_file );
    }
}

}
