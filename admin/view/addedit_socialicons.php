<?php
$usedefault_css = 0;

if (isset($settings) && is_array($settings)) {
    $usedefault_css = isset($settings['usedefault_css']) ? $settings['usedefault_css'] : 0;
}
?>
<div class="wrap socialicons_wrap">
    <div class="icon32 icon32-socialicons"><br></div>
    <h2 class="title">SocialIcons<span><?php echo (isset($version) ? $version : ''); ?></span></h2>


    <div class="socialicons_news_about">
        <h3 class="socialicons_form_title"><?php _e('Wordpress Plugins? News about SocialIcons?', SOCIALICONS_LANG); ?></h3>
        <div class="form_item_green socialicons_form_item_combo socialicons_news_about_box">
            <p><?php _e('Subscribe to me on Facebook', SOCIALICONS_LANG); ?> </p>
            <iframe src="//www.facebook.com/plugins/subscribe.php?href=https%3A%2F%2Fwww.facebook.com%2Feduardostuart&amp;layout=button_count&amp;show_faces=false&amp;colorscheme=light&amp;font&amp;width=450&amp;appId=1239219039552300" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px;height:20px;" allowTransparency="true"></iframe>
            <p><?php _e('or follow me on Twitter', SOCIALICONS_LANG); ?></p>
            <a href="https://twitter.com/eduardostuart" class="twitter-follow-button" data-show-count="false" data-size="large">Follow @eduardostuart</a>
            <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
    </div>


    <form name="settings_socialicons" id="settings_socialicons" method="post">
        <h3 class="socialicons_form_title"><?php _e('Settings', SOCIALICONS_LANG); ?></h3>

        <input type="hidden" name="action" value="save_sociallinks_settings"/>

        <div class="socialicons_form_item labelinline">
            <input type="checkbox" name="socialicons_usecss" id="socialicons_usecss" <?php if ($usedefault_css == 1) echo ' checked '; ?>value="1"/> <label for="socialicons_usecss"><?php _e('Use SocialIcons default css style?', SOCIALICONS_LANG); ?></label>
        </div>
        <div class="socialicons_form_options_item">
            <input type="submit" class="button-primary"  id="socialicon-savesettings" value="<?php _e('Save settings', SOCIALICONS_LANG); ?>"/>
        </div>
        <input type="hidden" name="_ajax_wpnonce" value="<?php echo SOCIALICONS_WPNONCE; ?>"/>
    </form>


    <form name="addedit_socialicons" id="addedit_socialicons" method="post">
        <input type="hidden" name="action" value="save_sociallinks_newicon"/>
        <h3 class="socialicons_form_title"><?php _e('New Social Icon', SOCIALICONS_LANG); ?></h3>
        <div class="socialicons_form_item_combo form_item_blue">
            <div>
                <label for="socialicons_name"><?php _e('Name', SOCIALICONS_LANG); ?></label>
                <input type="input" class="regular-text" type="text" name="socialicons_name" id="socialicons_name"/>
                <p class="helper-text"><?php _e('Example: Facebook', SOCIALICONS_LANG); ?></p>
            </div>
            <div>
                <label for="socialicons_url"><?php _e("Link (<strong>don't forget the http://</strong>)", SOCIALICONS_LANG); ?></label>
                <input type="input"  class="regular-text"   type="text" name="socialicons_url" id="socialicons_url"/>
                <p class="helper-text"><?php _e('Example: http://facebook.com/eduardostuart', SOCIALICONS_LANG); ?></p>
            </div>
            <div>
                <label><?php _e('Link target', SOCIALICONS_LANG); ?></label>
                <ul>
                    <li><input type="radio" name="socialicons_target" checked value="_blank"/> _blank - <?php _e('new window or tab.', SOCIALICONS_LANG); ?></li>
                    <li><input type="radio" name="socialicons_target"  value="_top"/> _top - <?php _e('current window or tab, with no frames.', SOCIALICONS_LANG); ?></li>
                    <li><input type="radio" name="socialicons_target"  value="_none"/> _none - <?php _e('same window or tab.', SOCIALICONS_LANG); ?></li>
                </ul>
            </div>
            <div>
                <label for="socialicons_iconfile"><?php _e('Icon file', SOCIALICONS_LANG); ?></label>
                <input type="button" id="findiconfinderbutton" class="button" value="<?php _e('Browser icons - Iconfinder.com', SOCIALICONS_LANG); ?>" />
            </div>
            <div>
                <span><?php _e('Add a url', SOCIALICONS_LANG); ?>: </span>
                <input type="input"  class="regular-text"   type="text" name="socialicons_iconurl" id="socialicons_iconurl"/>
            </div>

        </div>
        <div class="socialicons_form_options_item">
            <input type="submit" id="add_new_socialicon" class="button-primary" value="<?php _e('add link', SOCIALICONS_LANG); ?>" />
        </div>

        <h3 class="socialicons_form_title"><?php _e('Your icons', SOCIALICONS_LANG); ?></h3>
        <div class="socialicons_form_item">
            <ul class="socialicons_list_of_links">
                <li><?php _e('No icons found :(', SOCIALICONS_LANG); ?></li>
            </ul>
        </div>

        <input type="hidden" name="_ajax_wpnonce" value="<?php echo SOCIALICONS_WPNONCE; ?>"/>

    </form>



    <div id="socialicons-modal-iconfinder" title="<?php _e('Find icons in Iconfinder', SOCIALICONS_LANG); ?>">
        <p></p>
        <div class="socialicons_form_item">
            <label for="socialicons_iconfinder_q"><?php _e('Search for?', SOCIALICONS_LANG); ?></label>
            <input type="input" class="regular-text" type="text" name="socialicons_iconfinder_q" value="twitter" id="socialicons_iconfinder_q"/>
            <input type="button" id="find-socialicons-iconfinder-ok" class="button-secondary" value="<?php _e('ok', SOCIALICONS_LANG); ?>" />
        </div>

        <div id="iconsresult" class="clearfix">

        </div>

    </div>

</div>