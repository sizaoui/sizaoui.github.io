<?php
global $wp_query, $ae_post_factory, $post, $user_ID;
// Get author data
$user_id = $post->post_author;

if ($user_id == $user_ID) {
    $seller_id = get_post_meta($post->ID, 'seller_id', true);
    if (!empty($seller_id)) {
        $user_id = $seller_id;
    }
}

$user = mJobUser::getInstance();
$user_data = $user->get($user_id);

// Convert profile
$profile_obj = $ae_post_factory->get('mjob_profile');
$profile_id = get_user_meta($user_id, 'user_profile_id', true);
if ($profile_id) {
    $profile = get_post($profile_id);
    if ($profile && !is_wp_error($profile)) {
        $profile = $profile_obj->convert($profile);
    }
}

// User profile information
$description = !empty($profile->profile_description) ? $profile->profile_description : "";
$display_name = isset($user_data->display_name) ? $user_data->display_name : '';
$country_name = isset($profile->tax_input['country'][0]) ? $profile->tax_input['country'][0]->name : '';
$languages = isset($profile->tax_input['language']) ? $profile->tax_input['language'] : '';
?>
<div class="box-aside box-shadow">
    <div class="personal-profile">
        <div class="float-center">
            <?php
            echo mje_avatar($user_id, 75);
            ?>
        </div>
        <h4 class="float-center"><?php echo $display_name; ?></h4>
        <div class="line">
            <span class="line-distance"></span>
        </div>
        <ul class="profile">
            <li class="location clearfix">
                <div class="pull-left">
                    <span><i class="fa fa-map-marker"></i><?php _e('From ', 'enginethemes') ?></span>
                </div>
                <div class="pull-right">
                    <?php echo $country_name; ?>
                </div>
            </li>

            <li class="language clearfix">
                <div class="pull-left">
                    <span><i class="fa fa-globe"></i><?php _e('Languages ', 'enginethemes'); ?></span>
                </div>
                <div class="pull-right">
                    <?php
                    if (!empty($languages)) {
                        foreach ($languages as $language) {
                    ?>
                            <p class="lang-item"><?php echo $language->name; ?></p>
                    <?php
                        }
                    }
                    ?>
                </div>
            </li>

            <?php mJobUser::showUserTimeZone($user_id); ?>

            <li class="bio clearfix">
                <span> <i class="fa fa-info-circle"></i><?php _e('Bio', 'enginethemes'); ?></span>
                <div class="content-bio">
                    <?php echo wp_trim_words($description, 50, '...'); ?>
                </div>
            </li>

            <?php
            /**
             * Show information for public profile
             */
            if (is_author()) {
            ?>
                <li class="clearfix">
                    <span> <i class="fa-solid fa-money-bill"></i><?php _e('Payment info', 'enginethemes'); ?></span>
                    <p>
                        <?php echo $payment_info; ?>
                    </p>
                </li>

                <li class="clearfix">
                    <span> <i class="fa fa-home"></i><?php _e('Billing info', 'enginethemes'); ?></span>
                    <ul>
                        <li>
                            <div class="cate-title"><?php _e('Business full name', 'enginethemes'); ?></div>
                            <p><?php echo $billing_full_name; ?></p>
                        </li>
                        <li>
                            <div class="cate-title"><?php _e('Full Address', 'enginethemes'); ?></div>
                            <p><?php echo $billing_full_address; ?></p>
                        </li>
                        <li>
                            <div class="cate-title"><?php _e('Country', 'enginethemes'); ?></div>
                            <?php
                            $country = get_term($billing_country);
                            echo '<p>' . $country->name . '</p>';
                            ?>
                        </li>
                        <li>
                            <div class="cate-title"><?php _e('VAT Number (USA)', 'enginethemes'); ?></div>
                            <p><?php echo $billing_vat; ?></p>
                        </li>
                    </ul>
                </li>
            <?php
            }
            ?>
        </ul>


        <div class="link-personal">
            <ul>
                <?php mje_show_contact_link($user_id); ?>
                <li><a href="<?php echo get_author_posts_url($user_id); ?>" class="profile-link"><?php _e('View my profile', 'enginethemes'); ?><i class="fa fa-user"></i></a></li>
            </ul>
        </div>
    </div>
</div>

<?php wp_reset_query(); ?>