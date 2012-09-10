<?php

class Iconfinder {

    const __WS = 'http://eduardostuart.com.br/iconfinder/socialicons.php?q=%s&min=%d&max=%d';

    const __MINDIMENSION = 12;

    const __MAXDIMENSION = 48;

    const __TIMEOUT = 13;

    public function __construct() {
        
    }

    public function get($q) {
        $url = sprintf(self::__WS, $q, self::__MINDIMENSION, self::__MAXDIMENSION);
        $response = wp_remote_get($url, array('timeout' => self::__TIMEOUT));
        return $response;
    }

    public function download($image) {

        $uploadimage = null;

        preg_match('/[a-z0-9;=_%\Q?&.-+[]\E]+\.(jpg|jpeg|gif|png)/i', $image, $uploadimage);



        if (sizeof($uploadimage) > 0 && isset($uploadimage[1])) {
            $file_name = strtolower(substr(md5(time() . 'socialicon_image_iconfinder'), 0, 10) . '.' . $uploadimage[1]);
            $upload = wp_upload_bits($file_name, 0, '');

            if ($upload['error']) {
                echo $upload['error'];
                return new WP_Error('upload_dir_error', $upload['error']);
            }

            //remove empty space url 
            if (preg_match("/\s/", $image)) {
                //http://cdn1.iconfinder.com/data/icons/aquaticus/48 X 48/facebook.png
                //to
                //http://cdn1.iconfinder.com/data/icons/aquaticus/48%20X%2048/facebook.png
                $image = str_replace(" ", "%20", $image);
            }

            $download = wp_get_http($image, $upload['file']);


            if (isset($download['response']) && in_array($download['response'], array(400, 500))) {
                return new WP_Error('download_iconfinder_error', __('Could not download selected image', SOCIALICONS_LANG));
            }

            if (!$download) {
                @unlink($upload['file']);
                return new WP_Error('download_iconfinder_error', __('Remote server did not respond', SOCIALICONS_LANG));
            }

            return array(
                'filename' => $upload['file'],
                'upload' => $upload,
                'download' => $download
            );
        }
        return new WP_Error('download_iconfinder_error', __('Could not find selected image.', SOCIALICONS_LANG));
    }

}