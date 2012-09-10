<?php

class Install {

    private $wpdb;

    private $tb_prefix;

    public static $__TBLINKS = '
        CREATE TABLE  IF NOT EXISTS %s (
        `id` INT( 10 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
        `name` VARCHAR( 100 ) NOT NULL ,
        `url` VARCHAR( 255 ) NOT NULL ,
        `target` varchar(50) not null, 
        `icon_file` VARCHAR( 255 ) NOT NULL ,
        `status` tinyint(1) NOT NULL
    ) ';

    public function __construct() {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->tb_prefix = $wpdb->prefix;
    }

    public function init() {
        $table = $this->tb_prefix . 'socialicons_links';
        $sqlquery_socialicons_links = sprintf(self::$__TBLINKS, $table);
        $install_socialicons_links = $this->wpdb->query($sqlquery_socialicons_links);
        $this->createSettings();
        return ($install_socialicons_links > 0);
    }

    public function createSettings() {
        if (!get_option('socialicon_settings')) {
            $settings = array(
                'usedefault_css' => 1
            );
            add_option('socialicon_settings', $settings, '', true);
        }
    }

}