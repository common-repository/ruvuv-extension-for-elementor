<?php
add_action( 'admin_menu', 'ruvuv_expand_sub_menu', 1000 );

function ruvuv_expand_sub_menu() {
	add_submenu_page( 'elementor', '', 'Ruvuv Elementor Extension', 'manage_options', 'ruvuv-elementor-extension', 'ruvuv_expand_submenu_form' );
	add_action( 'admin_init', 'register_ruvuv_expand_settings' );
}

function register_ruvuv_expand_settings() {
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-customcss', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-background-color-changing', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-sticky', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-tooltip', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-schedule', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-particle', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-image-moving', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-media-slider', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-relax-parallax', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-column-order', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-heading', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-section-link', array('default' => 'on') );
	register_setting( 'ruvuv-extension-settings-group', 'ruvuv-max-width', array('default' => 'on') );
}

function ruvuv_expand_submenu_form() { ?>
    <style>
        /* The switch - the box around the slider */
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        /* Hide default HTML checkbox */
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        /* The slider */
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked + .slider {
            background-color: #66bb6a;
        }

        input:focus + .slider {
            box-shadow: 0 0 1px #66bb6a;
        }

        input:checked + .slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        /* Rounded sliders */
        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
        .wrap {
            display: flex;
        }
        .ruvuv-form-wrapper {
            width: 66%;
        }
        .ruvuv-add-wrapper {
            width: 34%;
        }
        .ruvuv-form-wrapper .form-table tr:nth-child(odd) {
            margin-right: 4%;
        }
        .ruvuv-form-wrapper .form-table tr {
            width: 48%;
            display: inline-block;
            border: 1px solid #d9e0e6;
            margin-bottom: 15px;
            padding: 0 15px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
        }
        .ruvuv-form-wrapper .form-table tr th {
            float: left;
        }
        .ruvuv-form-wrapper .form-table tr td {
            text-align: right;
            float: right;
            margin-bottom: 0;
        }
        .ruvuv-form-wrapper .submit {
            text-align: right;
        }
        .ruvuv-form-wrapper .submit input.button {
            width: 200px;
            height: 50px;
            border-radius: 4px;
            background-color: #66bb6a;
            border: none;
            box-shadow: none;
            color: #fff;
            text-shadow: none;
            font-size: 15px;
            font-weight: 600;
            text-transform: uppercase;
        }
        @media (max-width: 991px) {
            .wrap {
                flex-direction: column;
            }
            .ruvuv-form-wrapper, .ruvuv-add-wrapper, .ruvuv-form-wrapper .form-table tr {
                width: 100%;
            }
            .ruvuv-form-wrapper .form-table tr:nth-child(odd) {
                margin-right: 0;
            }
        }
    </style>
	<div class="wrap">
        <div class="ruvuv-form-wrapper">
            <h1><?php echo __('Ruvuv Extension for Elementor', 'ruvuv-extension'); ?></h1>
            <p style="margin-bottom: 30px;">Here is the list of our all extensions. You can enable or disable extensions from here to optimize loading speed and Elementor editor experience. After <b>enabling or disabling</b> any widget make sure to click the <b>SAVE CHANGES</b> button.</p>
            <form method="post" action="options.php">
                <?php settings_fields( 'ruvuv-extension-settings-group' ); ?>
                <?php do_settings_sections( 'ruvuv-extension-settings-group' ); ?>
                <?php submit_button(__('Save Changes', 'ruvuv-extension')); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><?php _e('Background Image Moving', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-image-moving" <?php checked( get_option('ruvuv-image-moving', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Multi Color Motion', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-background-color-changing" <?php checked( get_option('ruvuv-background-color-changing', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Background Media Slider', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch"><input type="checkbox" name="ruvuv-media-slider" <?php checked( get_option('ruvuv-media-slider', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Responsive Column Order', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-column-order" <?php checked( get_option('ruvuv-column-order', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Section/Column Link', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-section-link" <?php checked( get_option('ruvuv-section-link', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Widget Max Width', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-max-width" <?php checked( get_option('ruvuv-max-width', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Heading Expand', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-heading" <?php checked( get_option('ruvuv-heading', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Relax Parallax', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-relax-parallax" <?php checked( get_option('ruvuv-relax-parallax', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Background Particle', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-particle" <?php checked( get_option('ruvuv-particle', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Sticky', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-sticky" <?php checked( get_option('ruvuv-sticky', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Tooltip', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-tooltip" <?php checked( get_option('ruvuv-tooltip', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Content Schedule', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-schedule" <?php checked( get_option('ruvuv-schedule', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th scope="row"><?php _e('Custom CSS', 'ruvuv-extension') ; ?></th>
                        <td>
                            <label class="switch">
                                <input type="checkbox" name="ruvuv-customcss" <?php checked( get_option('ruvuv-customcss', 'on' ) , 'on' ); ?> />
                                <span class="slider round"></span>
                            </label>
                        </td>
                    </tr>
                </table>
                <?php submit_button(__('Save Changes', 'ruvuv-extension')); ?>
            </form>
        </div>
        <div class="ruvuv-add-wrapper"></div>
	</div>
<?php }