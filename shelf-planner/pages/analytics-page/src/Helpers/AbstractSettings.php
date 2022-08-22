<?php // -*- coding: utf-8 -*-

namespace QuickAssortments\COG\Helpers;

if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Class AbstractSettings.
 *
 * @package QuickAssortments\COG\Helpers
 *
 * @since   1.0.0
 */
abstract class AbstractSettings
{
    /**
     * Setting page base prefix.
     *
     * @var string
     */
    protected static $nonce_key   = 'qa-cog-settings';
    protected static $hook_prefix = 'qa_cog_';
    /**
     * Error messages.
     *
     * @var array
     */
    private static $errors   = [];
    /**
     * Update messages.
     *
     * @var array
     */
    private static $messages   = [];
    protected $default_section = '';
    /**
     * Setting page id.
     *
     * @var string
     */
    protected $id = '';
    /**
     * Setting page label.
     *
     * @var string
     */
    protected $label = '';

    /**
     * Output admin fields.
     *
     * Loops though the woocommerce options array and outputs each field.
     *
     * @param array $options Opens array to output
     */
    public static function output_fields($options)
    {
        // Save settings if data has been posted
        if (! empty($_POST)) { // WPCS: sanitization ok.
            self::save();
        }

        // Add any posted messages
        if (! empty($_GET['wc_error'])) {  // WPCS: sanitization ok.
            self::add_error(stripslashes(sanitize_textarea_field($_GET['wc_error'])));
        }

        if (! empty($_GET['wc_message'])) { // WPCS: sanitization ok.
            self::add_message(stripslashes(sanitize_textarea_field($_GET['wc_message'])));
        }

        self::show_messages();

        foreach ($options as $value) {
            if (! isset($value['type'])) {
                continue;
            }
            if (! isset($value['id'])) {
                $value['id'] = '';
            }
            if (! isset($value['title'])) {
                $value['title'] = isset($value['name']) ? $value['name'] : '';
            }
            if (! isset($value['class'])) {
                $value['class'] = '';
            }
            if (! isset($value['css'])) {
                $value['css'] = '';
            }
            if (! isset($value['default'])) {
                $value['default'] = '';
            }
            if (! isset($value['desc'])) {
                $value['desc'] = '';
            }
            if (! isset($value['desc_tip'])) {
                $value['desc_tip'] = false;
            }
            if (! isset($value['placeholder'])) {
                $value['placeholder'] = '';
            }

            // Custom attribute handling
            $custom_attributes = [];

            if (! empty($value['custom_attributes']) && is_array($value['custom_attributes'])) {
                foreach ($value['custom_attributes'] as $attribute => $attribute_value) {
                    $custom_attributes[] = esc_attr($attribute) . '="' . esc_attr($attribute_value) . '"';
                }
            }

            // Description handling
            $field_description = self::get_field_description($value);
            extract($field_description);

            // Switch based on type
            switch ($value['type']) {
                // Section Titles
                case 'title':
                    if (! empty($value['title'])) {
                        echo '<h2>' . esc_html($value['title']) . '</h2>';
                    }
                    if (! empty($value['desc'])) {
                        echo wpautop(wptexturize(wp_kses_post($value['desc'])));
                    }
                    echo '<table class="form-table">' . "\n\n";
                    if (! empty($value['id'])) {
                        do_action(self::$hook_prefix . 'settings_' . sanitize_title($value['id']));
                    }
                    break;

                // Section Ends
                case 'sectionend':
                    if (! empty($value['id'])) {
                        do_action(self::$hook_prefix . 'settings_' . sanitize_title($value['id']) . '_end');
                    }
                    echo '</table>';
                    if (! empty($value['id'])) {
                        do_action(self::$hook_prefix . 'settings_' . sanitize_title($value['id']) . '_after');
                    }
                    break;

                // Standard text inputs and subtypes like 'number'
                case 'text':
                case 'email':
                case 'number':
                case 'color':
                case 'password':

                    $type         = $value['type'];
                    $option_value = self::get_option($value['id'], $value['default']);

                    if ($value['type'] == 'color') {
                        $type = 'text';
                        $value['class'] .= 'colorpick';
                        $description .= '<div id="colorPickerDiv_' . esc_attr($value['id']) . '" class="colorpickdiv" style="z-index: 100;background:#eee;border:1px solid #ccc;position:absolute;display:none;"></div>';
                    }

                    ?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
							<?php echo esc_html( $tooltip_html ); ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title($value['type']) ?>">
							<?php
                            if ('color' == $value['type']) {
                                echo '<span class="colorpickpreview" style="background: ' . esc_attr($option_value) . ';"></span>';
                            }
                            ?>
							<input
								name="<?php echo esc_attr($value['id']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								type="<?php echo esc_attr($type); ?>"
								style="<?php echo esc_attr($value['css']); ?>"
								value="<?php echo esc_attr($option_value); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								placeholder="<?php echo esc_attr($value['placeholder']); ?>"
								<?php echo implode(' ', $custom_attributes); ?>
								/> <?php echo esc_html( $description ); ?>
						</td>
					</tr><?php
                    break;

                // Textarea
                case 'textarea':

                    $option_value = self::get_option($value['id'], $value['default']);

                    ?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
							<?php echo esc_html( $tooltip_html ); ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title($value['type']) ?>">
							<?php echo esc_html( $description ); ?>

							<textarea
								name="<?php echo esc_attr($value['id']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								style="<?php echo esc_attr($value['css']); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								placeholder="<?php echo esc_attr($value['placeholder']); ?>"
								<?php echo implode(' ', $custom_attributes); ?>
								><?php echo esc_textarea($option_value); ?></textarea>
						</td>
					</tr><?php
                    break;

                // Select boxes
                case 'select':
                case 'multiselect':

                    $option_value = self::get_option($value['id'], $value['default']);

                    ?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
							<?php echo esc_html( $tooltip_html ); ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title($value['type']) ?>">
							<select
								name="<?php echo esc_attr($value['id']); ?><?php if ($value['type'] == 'multiselect') {
                        echo '[]';
                    } ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								style="<?php echo esc_attr($value['css']); ?>"
								class="<?php echo esc_attr($value['class']); ?>"
								<?php echo implode(' ', $custom_attributes); ?>
								<?php echo ('multiselect' == $value['type']) ? 'multiple="multiple"' : ''; ?>
								>
								<?php
                                    foreach ($value['options'] as $key => $val) {
                                        ?>
										<option value="<?php echo esc_attr($key); ?>" <?php

                                            if (is_array($option_value)) {
                                                selected(in_array($key, $option_value), true);
                                            } else {
                                                selected($option_value, $key);
                                            } ?>><?php echo esc_html( $val ); ?></option>
										<?php
                                    }
                                ?>
							</select> <?php echo esc_html( $description ); ?>
						</td>
					</tr><?php
                    break;

                // Radio inputs
                case 'radio':

                    $option_value = self::get_option($value['id'], $value['default']);

                    ?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
							<?php echo esc_html( $tooltip_html ); ?>
						</th>
						<td class="forminp forminp-<?php echo sanitize_title($value['type']) ?>">
							<fieldset>
								<?php echo esc_html( $description ); ?>
								<ul>
								<?php
                                    foreach ($value['options'] as $key => $val) {
                                        ?>
										<li>
											<label><input
												name="<?php echo esc_attr($value['id']); ?>"
												value="<?php echo esc_attr( $key ); ?>"
												type="radio"
												style="<?php echo esc_attr($value['css']); ?>"
												class="<?php echo esc_attr($value['class']); ?>"
												<?php echo implode(' ', $custom_attributes); ?>
												<?php checked($key, $option_value); ?>
												/> <?php echo esc_html( $val ); ?></label>
										</li>
										<?php
                                    }
                                ?>
								</ul>
							</fieldset>
						</td>
					</tr><?php
                    break;

                // Checkbox input
                case 'checkbox':

                    $option_value    = self::get_option($value['id'], $value['default']);
                    $visbility_class = [];

                    if (! isset($value['hide_if_checked'])) {
                        $value['hide_if_checked'] = false;
                    }
                    if (! isset($value['show_if_checked'])) {
                        $value['show_if_checked'] = false;
                    }
                    if ('yes' == $value['hide_if_checked'] || 'yes' == $value['show_if_checked']) {
                        $visbility_class[] = 'hidden_option';
                    }
                    if ('option' == $value['hide_if_checked']) {
                        $visbility_class[] = 'hide_options_if_checked';
                    }
                    if ('option' == $value['show_if_checked']) {
                        $visbility_class[] = 'show_options_if_checked';
                    }

                    if (! isset($value['checkboxgroup']) || 'start' == $value['checkboxgroup']) {
                        ?>
							<tr valign="top" class="<?php echo esc_attr(implode(' ', $visbility_class)); ?>">
								<th scope="row" class="titledesc">
								    <label for="<?php echo esc_attr( $value['id'] ); ?>"><?php echo esc_html($value['title']) ?></label>
								    <?php echo esc_html( $tooltip_html ); ?>
								</th>
								<td class="forminp forminp-checkbox">
									<fieldset>
						<?php
                    } else {
                        ?>
							<fieldset class="<?php echo esc_attr(implode(' ', $visbility_class)); ?>">
						<?php
                    }

                    if (! empty($value['title'])) {
                        ?>
							<legend class="screen-reader-text"><span><?php echo esc_html($value['title']) ?></span></legend>
						<?php
                    }

                    ?>
						<label for="<?php echo esc_attr( $value['id'] ); ?>">
							<input
								name="<?php echo esc_attr($value['id']); ?>"
								id="<?php echo esc_attr($value['id']); ?>"
								type="checkbox"
                                class="<?php echo 'qa-checkbox' . esc_attr(isset($value['class']) ? ' ' . $value['class'] : ''); ?>"
								value="1"
								<?php checked($option_value, 'yes'); ?>
								<?php echo esc_attr( implode(' ', $custom_attributes) ); ?>
							/> <?php echo esc_html( $description ); ?>
                            <div class="qa-toggle-switch"></div>
						</label>
					<?php

                    if (! isset($value['checkboxgroup']) || 'end' == $value['checkboxgroup']) {
                        ?>
									</fieldset>
								</td>
							</tr>
						<?php
                    } else {
                        ?>
							</fieldset>
						<?php
                    }
                    break;

                // Image width settings
                case 'image_width':

                    $image_size       = str_replace('_image_size', '', $value['id']);
                    $size             = wc_get_image_size($image_size);
                    $width            = isset($size['width']) ? $size['width'] : $value['default']['width'];
                    $height           = isset($size['height']) ? $size['height'] : $value['default']['height'];
                    $crop             = isset($size['crop']) ? $size['crop'] : $value['default']['crop'];
                    $disabled_attr    = '';
                    $disabled_message = '';

                    if (has_filter(self::$hook_prefix . 'get_image_size_' . $image_size)) {
                        $disabled_attr    = 'disabled="disabled"';
                        $disabled_message = '<p><small>' . __('The settings of this image size have been disabled because its values are being overwritten by a filter.', 'woocommerce') . '</small></p>';
                    }

                    ?><tr valign="top">
						<th scope="row" class="titledesc"><?php echo esc_html($value['title']) ?> <?php echo esc_html( $tooltip_html ); echo esc_html( $disabled_message ); ?></th>
						<td class="forminp image_width_settings">

							<input name="<?php echo esc_attr($value['id']); ?>[width]" <?php echo esc_attr( $disabled_attr ); ?> id="<?php echo esc_attr($value['id']); ?>-width" type="text" size="3" value="<?php echo esc_attr( $width ); ?>" /> &times; <input name="<?php echo esc_attr($value['id']); ?>[height]" <?php echo esc_attr( $disabled_attr ); ?> id="<?php echo esc_attr($value['id']); ?>-height" type="text" size="3" value="<?php echo esc_attr( $height ); ?>" />px

							<label><input name="<?php echo esc_attr($value['id']); ?>[crop]" <?php echo esc_attr( $disabled_attr ); ?> id="<?php echo esc_attr($value['id']); ?>-crop" type="checkbox" value="1" <?php checked(1, $crop); ?> /> <?php _e('Hard Crop?', 'woocommerce'); ?></label>

							</td>
					</tr><?php
                    break;

                // Single page selects
                case 'single_select_page':

                    $args = [
                        'name'             => $value['id'],
                        'id'               => $value['id'],
                        'sort_column'      => 'menu_order',
                        'sort_order'       => 'ASC',
                        'show_option_none' => ' ',
                        'class'            => $value['class'],
                        'echo'             => false,
                        'selected'         => absint(self::get_option($value['id'])),
                    ];

                    if (isset($value['args'])) {
                        $args = wp_parse_args($value['args'], $args);
                    }

                    ?><tr valign="top" class="single_select_page">
						<th scope="row" class="titledesc"><?php echo esc_html($value['title']) ?> <?php echo esc_html( $tooltip_html ); ?></th>
						<td class="forminp">
							<?php echo esc_html( str_replace(' id=', " data-placeholder='" . esc_attr__('Select a page&hellip;', 'woocommerce') . "' style='" . $value['css'] . "' class='" . $value['class'] . "' id=", wp_dropdown_pages($args)) ); ?> <?php echo esc_html( $description ); ?>
						</td>
					</tr><?php
                    break;

                // Single country selects
                case 'single_select_country':
                    $country_setting = (string) self::get_option($value['id']);

                    if (strstr($country_setting, ':')) {
                        $country_setting = explode(':', $country_setting);
                        $country         = current($country_setting);
                        $state           = end($country_setting);
                    } else {
                        $country = $country_setting;
                        $state   = '*';
                    }
                    ?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
							<?php echo esc_html( $tooltip_html ); ?>
						</th>
						<td class="forminp"><select name="<?php echo esc_attr($value['id']); ?>" style="<?php echo esc_attr($value['css']); ?>" data-placeholder="<?php esc_attr_e('Choose a country&hellip;', 'woocommerce'); ?>" title="<?php esc_attr_e('Country', 'woocommerce') ?>" class="wc-enhanced-select">
							<?php WC()->countries->country_dropdown_options($country, $state); ?>
						</select> <?php echo esc_html( $description ); ?>
						</td>
					</tr><?php
                    break;

                // Country multiselects
                case 'multi_select_countries':

                    $selections = (array) self::get_option($value['id']);

                    if (! empty($value['options'])) {
                        $countries = $value['options'];
                    } else {
                        $countries = WC()->countries->countries;
                    }

                    asort($countries);
                    ?><tr valign="top">
						<th scope="row" class="titledesc">
							<label for="<?php echo esc_attr($value['id']); ?>"><?php echo esc_html($value['title']); ?></label>
							<?php echo esc_html( $tooltip_html ); ?>
						</th>
						<td class="forminp">
							<select multiple="multiple" name="<?php echo esc_attr($value['id']); ?>[]" style="width:350px" data-placeholder="<?php esc_attr_e('Choose countries&hellip;', 'woocommerce'); ?>" title="<?php esc_attr_e('Country', 'woocommerce') ?>" class="wc-enhanced-select">
								<?php
                                    if (! empty($countries)) {
                                        foreach ($countries as $key => $val) {
                                            echo '<option value="' . esc_attr($key) . '" ' . selected(in_array($key, $selections), true, false) . '>' . esc_html( $val ) . '</option>';
                                        }
                                    }
                                ?>
							</select> <?php echo esc_html( ($description) ? $description : '' ); ?> <br /><a class="select_all button" href="#"><?php _e('Select all', 'woocommerce'); ?></a> <a class="select_none button" href="#"><?php _e('Select none', 'woocommerce'); ?></a>
						</td>
					</tr><?php
                    break;
                case 'submit_button':
                    ?>
                    <p class="submit">
                        <input name="<?php echo esc_attr($value['name']); ?>" class="components-button is-button is-primary <?php echo esc_attr($value['class']); ?>" type="submit" value="<?php esc_attr_e($value['display_text'], 'woocommerce'); ?>" />
                        <?php  ?>
                    </p>
                    <?php
                    break;
                case 'nonce':
                    wp_nonce_field($value['nonce_key']);
                    break;
                // Default: run an action
                default:
                    do_action(self::$hook_prefix . $value['type'], $value);
                    break;
            }
        }
    }

    /**
     * Save the settings.
     */
    public static function save()
    {
        $current_tab = ( new static() )->id;

        if (empty($_REQUEST['_wpnonce']) || ! wp_verify_nonce($_REQUEST['_wpnonce'], self::$nonce_key)) {
            die(__('Action failed. Please refresh the page and retry.', 'woocommerce'));
        }

        // qa_cog_settings_save_qa_cog_settings
        // qa_cog_update_options_qa_cog_settings
        // qa_cog_update_options
        // Trigger actions
        do_action(self::$hook_prefix . 'settings_save_' . $current_tab);
        do_action(self::$hook_prefix . 'update_options_' . $current_tab);
        do_action(self::$hook_prefix . 'update_options');

        self::add_message(__('Your settings have been saved.', 'woocommerce'));

        // Clear any unwanted data and flush rules
        delete_transient(self::$hook_prefix . 'cache_excluded_uris');
        WC()->query->init_query_vars();
        WC()->query->add_endpoints();
        flush_rewrite_rules();

        do_action(self::$hook_prefix . 'settings_saved');
    }

    /**
     * Add a message.
     *
     * @param string $text
     */
    public static function add_message($text)
    {
        self::$messages[] = $text;
    }

    /**
     * Add an error.
     *
     * @param string $text
     */
    public static function add_error($text)
    {
        self::$errors[] = $text;
    }

    /**
     * Output messages + errors.
     *
     * @return string
     */
    public static function show_messages()
    {
        if (sizeof(self::$errors) > 0) {
            foreach (self::$errors as $error) {
                echo '<div id="message" class="error inline"><p><strong>' . esc_html($error) . '</strong></p></div>';
            }
        } elseif (sizeof(self::$messages) > 0) {
            foreach (self::$messages as $message) {
                echo '<div id="message" class="updated inline"><p><strong>' . esc_html($message) . '</strong></p></div>';
            }
        }
    }

    /**
     * Helper function to get the formated description and tip HTML for a
     * given form field. Plugins can call this when implementing their own
     * custom settings types.
     *
     * @param array $value The form field value array
     *
     * @return array The description and tip as a 2 element array
     */
    public static function get_field_description($value)
    {
        $description  = '';
        $tooltip_html = '';

        if (true === $value['desc_tip']) {
            $tooltip_html = $value['desc'];
        } elseif (! empty($value['desc_tip'])) {
            $description  = $value['desc'];
            $tooltip_html = $value['desc_tip'];
        } elseif (! empty($value['desc'])) {
            $description  = $value['desc'];
        }

        if ($description && in_array($value['type'], ['textarea', 'radio'])) {
            $description = '<p style="margin-top:0">' . wp_kses_post($description) . '</p>';
        } elseif ($description && in_array($value['type'], ['checkbox'])) {
            $description = wp_kses_post($description);
        } elseif ($description) {
            $description = '<span class="description">' . wp_kses_post($description) . '</span>';
        }

        $tooltip_html = wc_help_tip($tooltip_html);

        return [
            'description'  => $description,
            'tooltip_html' => $tooltip_html,
        ];
    }

    /**
     * Get a setting from the settings API.
     *
     * @param mixed  $option_name
     * @param string $default
     *
     * @return string
     */
    public static function get_option($option_name, $default = '')
    {
        // Array value
        if (strstr($option_name, '[')) {
            parse_str($option_name, $option_array);

            // Option name is first key
            $option_name = current(array_keys($option_array));

            // Get value
            $option_values = get_option($option_name, '');

            $key = key($option_array[$option_name]);

            if (isset($option_values[$key])) {
                $option_value = $option_values[$key];
            } else {
                $option_value = null;
            }

            // Single value
        } else {
            $option_value = get_option($option_name, null);
        }

        if (is_array($option_value)) {
            $option_value = array_map('stripslashes', $option_value);
        } elseif (! is_null($option_value)) {
            $option_value = stripslashes($option_value);
        }

        return $option_value === null ? $default : $option_value;
    }

    /**
     * Save admin fields.
     *
     * Loops though the woocommerce options array and outputs each field.
     *
     * @param array $options Options array to output
     *
     * @return bool
     */
    public static function save_fields($options)
    {
        if (empty($_POST)) { // WPCS: sanitization ok.
            return false;
        }

        // Options to update will be stored here and saved later.
        $update_options = [];

        // Loop options and get values to save.
        foreach ($options as $option) {
            if (! isset($option['id']) || ! isset($option['type'])) {
                continue;
            }

            // Get posted value.
            if (strstr($option['id'], '[')) {
                parse_str($option['id'], $option_name_array);
                $option_name  = current(array_keys($option_name_array));
                $setting_name = key($option_name_array[$option_name]);
                $raw_value    = isset($_POST[$option_name][$setting_name]) ? wp_unslash($_POST[$option_name][$setting_name]) : null;
            } else {
                $option_name  = $option['id'];
                $setting_name = '';
                $raw_value    = isset($_POST[$option['id']]) ? wp_unslash($_POST[$option['id']]) : null;
            }

            // Format the value based on option type.
            switch ($option['type']) {
                case 'checkbox':
                    $value = is_null($raw_value) ? 'no' : 'yes';
                    break;
                case 'textarea':
                    $value = wp_kses_post(trim($raw_value));
                    break;
                case 'multiselect':
                case 'multi_select_countries':
                    $value = array_filter(array_map('wc_clean', (array) $raw_value));
                    break;
                case 'image_width':
                    $value = [];
                    if (isset($raw_value['width'])) {
                        $value['width']  = wc_clean($raw_value['width']);
                        $value['height'] = wc_clean($raw_value['height']);
                        $value['crop']   = isset($raw_value['crop']) ? 1 : 0;
                    } else {
                        $value['width']  = $option['default']['width'];
                        $value['height'] = $option['default']['height'];
                        $value['crop']   = $option['default']['crop'];
                    }
                    break;
                default:
                    $value = wc_clean($raw_value);
                    break;
            }

            /**
             * Fire an action when a certain 'type' of field is being saved.
             *
             * @deprecated 2.4.0 - doesn't allow manipulation of values!
             */
            if (has_action(self::$hook_prefix . 'update_option_' . sanitize_title($option['type']))) {
                _deprecated_function('Theself::self::$$hook_prefix .  update_option_X action', '2.4.0', self::$hook_prefix . 'admin_settings_sanitize_option filter');
                do_action(self::$hook_prefix . 'update_option_' . sanitize_title($option['type']), $option);
                continue;
            }

            /**
             * Sanitize the value of an option.
             *
             * @since 2.4.0
             */
            $value = apply_filters(self::$hook_prefix . 'admin_settings_sanitize_option', $value, $option, $raw_value);

            /**
             * Sanitize the value of an option by option name.
             *
             * @since 2.4.0
             */
            $value = apply_filters(self::$hook_prefix . "admin_settings_sanitize_option_$option_name", $value, $option, $raw_value);

            if (is_null($value)) {
                continue;
            }

            // Check if option is an array and handle that differently to single values.
            if ($option_name && $setting_name) {
                if (! isset($update_options[$option_name])) {
                    $update_options[$option_name] = get_option($option_name, []);
                }
                if (! is_array($update_options[$option_name])) {
                    $update_options[$option_name] = [];
                }
                $update_options[$option_name][$setting_name] = $value;
            } else {
                $update_options[$option_name] = $value;
            }

            /**
             * Fire an action before saved.
             *
             * @deprecated 2.4.0 - doesn't allow manipulation of values!
             */
            do_action(self::$hook_prefix . 'update_option', $option);
        }

        // Save all options in our array.
        foreach ($update_options as $name => $value) {
            update_option($name, $value);
        }

        return true;
    }

    public function tabs_array($tabs)
    {
        $tabs[$this->id] = $this->label;
        return (array)$tabs;
    }

    /**
     * Output sections.
     */
    public function output_sections()
    {
        if (isset($_GET['section'])) {  // WPCS: sanitization ok.
            $current_section = sanitize_key($_GET['section']);
        } else {
            $current_section = $this->default_section;
        }

        $sections = $this->get_sections();

        if (empty($sections) || 1 === sizeof($sections)) {
            return;
        }

        echo '<ul class="subsubsub">';

        $array_keys = array_keys($sections);

        foreach ($sections as $id => $label) {
            echo esc_html( '<li><a href="' . admin_url('admin.php?page=' . self::$nonce_key . '&tab=' . $this->id . '&section=' . sanitize_title($id)) . '" class="' . ($current_section == $id ? 'current' : '') . '">' . $label . '</a> ' . (end($array_keys) == $id ? '' : '|') . ' </li>' );
        }

        echo '</ul><br class="clear" />';
    }

    /**
     * Get sections.
     *
     * @return array
     */
    public function get_sections()
    {
        return apply_filters(self::$hook_prefix . 'get_sections_' . $this->id, []);
    }
}
