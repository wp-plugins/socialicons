<?php

class SocialIconsView {

    private $data;

    public function __construct() {
        $this->data = array();
        $this->version = SOCIALICONS_VERSION;
    }

    public function __set($k, $v) {
        $this->data[$k] = $v;
    }

    public function __get($k) {
        if (isset($this->data[$k])) {
            return $this->data[$k];
        }
    }

    public function __isset($k) {
        return isset($k);
    }

    public static function getTemplateDir() {
        return SOCIALICONS_DIR . 'admin/view/';
    }

    public function render($filename) {
        $template = self::getTemplateDir() . $filename;
        if (file_exists($template)) {
            foreach ($this->data as $key => $value) {
                $$key = $value;
            }
            require $template;
        }
    }

}