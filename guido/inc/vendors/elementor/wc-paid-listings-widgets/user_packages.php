<?php


if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Guido_Elementor_User_Packages extends Elementor\Widget_Base {

	public function get_name() {
        return 'apus_element_user_packages';
    }

	public function get_title() {
        return esc_html__( 'Apus User Packages', 'guido' );
    }
    
	public function get_categories() {
        return [ 'guido-elements' ];
    }

	protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'guido' ),
                'tab' => Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => esc_html__( 'Title', 'guido' ),
                'type' => Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

   		$this->add_control(
            'el_class',
            [
                'label'         => esc_html__( 'Extra class name', 'guido' ),
                'type'          => Elementor\Controls_Manager::TEXT,
                'placeholder'   => esc_html__( 'If you wish to style particular content element differently, please add a class name to this field and refer to it in your custom CSS file.', 'guido' ),
            ]
        );

        $this->end_controls_section();

    }

	protected function render() {
        $settings = $this->get_settings();

        extract( $settings );
        ?>
        <div class="box-dashboard-wrapper">
            <div class="inner-list">
                <?php if ($title!=''): ?>
                    <h2 class="title">
                        <?php echo esc_attr( $title ); ?>
                    </h2>
                <?php endif; ?>

                <?php if ( ! is_user_logged_in() ) {
                    ?>
                    <div class="box-list-2">
                        <div class="text-warning"><?php  esc_html_e( 'Please login to see this page.', 'guido' ); ?></div>
                    </div>  
                    <?php
                } else {
                    $packages = WP_Listings_Directory_Wc_Paid_Listings_Mixes::get_packages_by_user( get_current_user_id(), false );
                    if ( !empty($packages) ) {
                    ?>
                        <div class="widget-user-packages <?php echo esc_attr($el_class); ?>">
                            <div class="widget-content table-responsive">
                                <table class="user-packages">
                                    <thead>
                                        <tr>
                                            <td><?php esc_html_e('ID', 'guido'); ?></td>
                                            <td><?php esc_html_e('Package', 'guido'); ?></td>
                                            <td><?php esc_html_e('Package Type', 'guido'); ?></td>
                                            <td><?php esc_html_e('Package Info', 'guido'); ?></td>

                                            <td><?php esc_html_e('Status', 'guido'); ?></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($packages as $package) {
                                            $prefix = WP_LISTINGS_DIRECTORY_WC_PAID_LISTINGS_PREFIX;
                                            $package_type = get_post_meta($package->ID, $prefix. 'package_type', true);
                                            $package_types = WP_Listings_Directory_Wc_Paid_Listings_Post_Type_Packages::package_types();

                                        ?>
                                            <tr>
                                                <td><?php echo trim($package->ID); ?></td>
                                                <td class="title"><?php echo trim($package->post_title); ?></td>
                                                <td>
                                                    <?php
                                                        if ( !empty($package_types[$package_type]) ) {
                                                            echo esc_html($package_types[$package_type]);
                                                        } else {
                                                            echo '--';
                                                        }
                                                    ?>
                                                </td>
                                                <td>
                                                    <div class="package-info-wrapper">
                                                    <?php
                                                        switch ($package_type) {
                                                            case 'listing_package':
                                                            default:
                                                                $feature_listings = get_post_meta($package->ID, $prefix. 'feature_listings', true);
                                                                $package_count = get_post_meta($package->ID, $prefix. 'package_count', true);
                                                                $listing_limit = get_post_meta($package->ID, $prefix. 'listing_limit', true);
                                                                $listing_duration = get_post_meta($package->ID, $prefix. 'listing_duration', true);
                                                                ?>
                                                                <ul class="lists-info">
                                                                    <li>
                                                                        <span class="title"><?php esc_html_e('Featured:', 'guido'); ?></span>
                                                                        <span class="value">
                                                                            <?php
                                                                                if ( $feature_listings == 'on' ) {
                                                                                    esc_html_e('Yes', 'guido');
                                                                                } else {
                                                                                    esc_html_e('No', 'guido');
                                                                                }
                                                                            ?>
                                                                        </span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="title"><?php esc_html_e('Posted:', 'guido'); ?></span>
                                                                        <span class="value"><?php echo intval($package_count); ?></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="title"><?php esc_html_e('Limit Posts:', 'guido'); ?></span>
                                                                        <span class="value"><?php echo intval($listing_limit); ?></span>
                                                                    </li>
                                                                    <li>
                                                                        <span class="title"><?php esc_html_e('Listing Duration:', 'guido'); ?></span>
                                                                        <span class="value"><?php echo intval($listing_duration); ?></span>
                                                                    </li>
                                                                </ul>
                                                                <?php
                                                                break;
                                                        }
                                                    ?>
                                                    </div>
                                                </td>
                                                <td>

                                                    <?php
                                                        $valid = false;
                                                        $user_id = get_current_user_id();
                                                        switch ($package_type) {
                                                            case 'listing_package':
                                                            default:
                                                                $valid = WP_Listings_Directory_Wc_Paid_Listings_Mixes::package_is_valid($user_id, $package->ID);
                                                                break;
                                                        }
                                                        if ( !$valid ) {
                                                            echo '<span class="action finish">'.esc_html__('Finished', 'guido').'</span>';
                                                        } else {
                                                            echo '<span class="action active">'.esc_html__('Active', 'guido').'</span>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="not-found"><?php esc_html_e('Don\'t have any packages', 'guido'); ?></div>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    <?php }

}

if ( version_compare(ELEMENTOR_VERSION, '3.5.0', '<') ) {
    Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Guido_Elementor_User_Packages );
} else {
    Elementor\Plugin::instance()->widgets_manager->register( new Guido_Elementor_User_Packages );
}