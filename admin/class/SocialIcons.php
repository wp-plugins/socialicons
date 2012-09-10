<?php

class SocialIcons {

    private $wpdb;

    private $tb_socialicons_links;

    public function __construct($wpdb = null) {
        if ($wpdb) {
            $this->wpdb = $wpdb;
            $this->tb_socialicons_links = $wpdb->prefix . 'socialicons_links';
        }
    }

    public static function response($code, $message) {
        return array(
            'code' => $code,
            'message' => $message
        );
    }

    public function saveSettings($data) {
        $settings = array(
            'usedefault_css' => isset($data['socialicons_usecss']) ? $data['socialicons_usecss'] : 0
        );

        if (get_option('socialicon_settings')) {
            update_option('socialicon_settings', $settings);
        } else {
            add_option('socialicon_settings', $settings, '', true);
        }
    }

    public function getSettings() {
        return get_option('socialicon_settings');
    }

    public function AddNewSocialIconUrl($name, $url, $target, $icon_file, $status = 1) {
        $name = trim($name);
        $url = trim($url);
        $icon_file = trim($icon_file);
        $status = (int) $status;
        $target = trim($target);

        $sqlquery = $this->wpdb->prepare("
        insert into {$this->tb_socialicons_links} 
            (name,url,icon_file,target,status) 
            values
            (%s,%s,%s,%s,%d)", $name, $url, $icon_file, $target, $status);
        if ($this->wpdb->query($sqlquery) > 0) {
            return self::response(200, __('Link added', SOCIALICONS_LANG));
        } else {
            return new WP_Error('error_add_socialicon', 'Could not add social icon url');
        }
    }

    public function UpdateSocialIconUrl($id, $name, $url, $icon_file, $status = 1) {
        $name = trim($name);
        $url = trim($url);
        $icon_file = trim($icon_file);
        $status = (int) $status;
        $sqlquery = $this->wpdb->prepare("
            update 
                {$this->tb_socialicons_links}
            set 
                name = %s,
                url = %s,
                icon_file = %s,
                status = %d
            where 
                id = %d
            ", $name, $url, $icon_file, $status, $id);
        if ($this->wpdb->query($sqlquery) > 0) {
            return __('Link updated', SOCIALICONS_LANG);
        } else {
            return __('Link cannot be updated', SOCIALICONS_LANG);
        }
    }

    public function DeleteSocialIconUrl($id) {
        $sqlquery = $this->wpdb->prepare("
            delete from {$this->tb_socialicons_links}
                where id = %d
            ", $id);
        if ($this->wpdb->query($sqlquery) > 0) {
            return self::response(200, __('Link removed', SOCIALICONS_LANG));
        } else {
            return new WP_Error('delete_socialicon_error', __('Link cannot be removed', SOCIALICONS_LANG));
        }
    }

    public function ChangeStatusIconUrl($id, $status) {
        $sqlquery = $this->wpdb->prepare("
            update {$this->tb_socialicons_links}
                set status = %d
                where id = %d
            ", $status, $id);
        if ($this->wpdb->query($sqlquery) > 0) {
            return __('Status changed', SOCIALICONS_LANG);
        } else {
            return __('Error while update status', SOCIALICONS_LANG);
        }
    }

    public function getActiveSocialIconsLinks() {
        $results = $this->wpdb->get_results("select url,icon_file,target from {$this->tb_socialicons_links} where status = 1", ARRAY_A);
        return $results;
    }

    public function getAllSocialIcons() {
        $results = $this->wpdb->get_results("select * from {$this->tb_socialicons_links} order by id desc");
        return $results;
    }

}