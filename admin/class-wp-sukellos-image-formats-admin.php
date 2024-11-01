<?php

namespace Sukellos\Admin;

use Sukellos\WP_Sukellos_Image_Formats_Loader;
use Sukellos\WPFw\Singleton;
use Sukellos\WPFw\WP_Plugin_Admin;
use Sukellos\WPFw\Utils\WP_Log;
use Sukellos\WPFw\AdminBuilder\Item_Type;
use Sukellos\WPFw\Utils\WP_Helper;

defined( 'ABSPATH' ) or exit;

/**
 * Admin class.
 * Main admin is used as controller to init admin menu and all other admin pages
 *
 * @since 1.0.0
 */
class WP_Sukellos_Image_Formats_Admin extends WP_Plugin_Admin {

    // Use Trait Singleton
    use Singleton;

    const IMAGE_SIZE_PREFIX = 'image_size_';

    /**
     * Default init method called when instance created
     * This method can be overridden if needed.
     *
     * @since 1.0.0
     * @access protected
     */
    public function init() {

        parent::init();

        // Add action to delegate settings fields creation to Sukellos Fw Tools admin
        // Use priority to order Tools
        add_action( 'sukellos_fw/admin/create_tools_fields', array( $this, 'action_create_tools_fields' ), 11, 1 );

        WP_Log::info( 'WP_Sukellos_Image_Formats_Admin->init OK!',[], WP_Sukellos_Image_Formats_Loader::instance()->get_text_domain());
    }


    /**
     * Gets the plugin configuration URL
     * This is used to build actions list in plugins page
     * Leave blank ('') to disable
     *
     * @since 1.0.0
     *
     * @return string plugin settings URL
     */
    public function get_settings_url() {

        return admin_url( 'admin.php?page='.WP_Sukellos_Image_Formats_Loader::instance()->get_options_suffix_param().'_tools' );
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */


    /***
     * Adding CSS and JS into header
     * Default add assets/admin.css and assets/admin.js
     */
    public function admin_enqueue_scripts() {}


    /***
     * Admin page
     * Settings managed by main Sukellos Fw Tools admin
     */
    public function create_items() {}

    /**
     * Tools fields creation
     */
    public function action_create_tools_fields( $admin_page ) {

        // Admin page is a Tabs page
        $admin_tab = $admin_page->create_tab(
            array(
                'id' => WP_Sukellos_Image_Formats_Loader::instance()->get_options_suffix_param().'_images_formats_tab',
                'name' => WP_Helper::sk__('Images Formats' ),
                'desc' => '',
            )
        );

        // Create a header
        $admin_tab->create_header(
            array(
                'id' => WP_Sukellos_Image_Formats_Loader::instance()->get_options_suffix_param().'_header_images',
                'name' => WP_Helper::sk__('Images Formats' ),
                'desc' => WP_Helper::sk__( 'Easily manage the image formats supported by Wordpress' ),
            )
        );


        // Get images sizes and prepare options
        $image_sizes = WP_Helper::get_image_sizes();
        $options = array();
        foreach ($image_sizes as $label => $image_size) {

            $options[self::IMAGE_SIZE_PREFIX.$label] = $label.' - Taille: '.$image_size['width'].' x '.$image_size['height'].'px - '.(($image_size['crop']==1)?WP_Helper::sk__('Cropped' ):WP_Helper::sk__('Scaled proportionally' ));
        }

        // Create an multicheck option field
        $admin_tab->create_option(
            array(
                'type' => Item_Type::MULTICHECK,
                // Common
                'id' => WP_Sukellos_Image_Formats_Loader::instance()->get_options_suffix_param().'_images_sizes',
                'name' => WP_Helper::sk__('Image formats' ),
                'desc' => WP_Helper::sk__('Uncheck unused formats' ),
                'options' => $options,

                'default' => array_keys($options),
                'select_all' => WP_Helper::sk__('Select all' ), // Display a select all checkbox if true, or a string which define the label
            )
        );
    }

}