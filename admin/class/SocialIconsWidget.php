<?php

class SocialIconsWidget extends WP_Widget {

    public function __construct() {
        parent::__construct('socialicons_widget', 'SocialIcons Widget', array(
            'description' => __('SocialIcons Widget', SOCIALICONS_LANG)
        ));
    }

    public function widget($args, $instance) {

        $before_widget = $before_title = $after_title = $after_widget = '';

        extract($args);
        $title = apply_filters('widget_title', $instance['title']);

        echo $before_widget;
        if (!empty($title))
            echo $before_title . $title . $after_title;
        echo getSocialIconsHTML();
        echo $after_widget;
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }

    public function form($instance) {
        if (isset($instance['title'])) {
            $title = $instance['title'];
        } else {
            $title = '';
        }
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> 
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

}