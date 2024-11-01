<?php

namespace Sukellos;

use Sukellos\Admin\WP_Sukellos_Image_Formats_Admin;
use Sukellos\WPFw\AdminBuilder\Admin_Builder;
use Sukellos\WPFw\AdminBuilder\Item_Type;
use Sukellos\WPFw\Singleton;
use Sukellos\WPFw\Utils\WP_Log;

defined( 'ABSPATH' ) or exit;

/**
 * This class is used to manage images sizes
 *
 * @since 1.0.0
 */
class Images_Manager {

    // Use Trait Singleton
    use Singleton;

    /**
     * Default init method called when instance created
     * This method can be overridden if needed.
     *
     * @since 1.0.0
     * @access protected
     */
    public function init() {

        // Manage images
        add_filter( 'intermediate_image_sizes_advanced', array( $this, 'filter_intermediate_image_sizes_advanced'), 10, 3 );
    }

    /**
     *          ===============
     *      =======================
     *  ============ HOOKS ===========
     *      =======================
     *          ===============
     */

    /**
     * Filters the image sizes automatically generated when uploading an image.
     *
     * @param $sizes (array) Associative array of image sizes to be created.
     * @param $image_meta (array) The image meta data: width, height, file, sizes, etc.
     * @param $attachment_id (int) The attachment post ID for the image.
     */
    public function filter_intermediate_image_sizes_advanced( $sizes, $image_meta, $attachment_id ) {

        WP_Log::debug('Images_Manager->filter_intermediate_image_sizes_advanced  ', ['$new_sizes' => $sizes, '$image_meta' => $image_meta, '$attachment_id' => $attachment_id]);

        // Get supported images sizes from config
        $supported_images_sizes = Admin_Builder::get_option( WP_Sukellos_Image_Formats_Loader::instance()->get_options_suffix_param().'_images_sizes', Item_Type::MULTICHECK );
        if ( !is_array( $supported_images_sizes ) ) return $sizes;
        WP_Log::debug('Images_Manager->filter_intermediate_image_sizes_advanced  ', ['$supported_images_sizes' => $supported_images_sizes]);
        $new_sizes = array();
        foreach($sizes as $clabel => $csize) {
            if ( in_array(WP_Sukellos_Image_Formats_Admin::IMAGE_SIZE_PREFIX.$clabel, $supported_images_sizes)) {
                $new_sizes[$clabel] = $csize;
            }
        }
        WP_Log::debug('Images_Manager->filter_intermediate_image_sizes_advanced  ', ['$new_sizes' => $new_sizes]);

        return $new_sizes;
    }
}
