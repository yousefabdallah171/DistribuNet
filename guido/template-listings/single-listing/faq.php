<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
global $post;

$meta_obj = WP_Listings_Directory_Listing_Meta::get_instance($post->ID);

if ( $meta_obj->check_post_meta_exist('faq') && ($faq = $meta_obj->get_post_meta('faq')) ) {
    ?>
    <div id="listing-detail-faq" class="listing-detail-faq">
        <h3 class="title"><?php echo trim($meta_obj->get_post_meta_title('faq')); ?></h3>
        <div class="inner">
            <div class="panel-group" id="listing-faq-accordion" role="tablist" aria-multiselectable="true">
                <?php $i = 0; foreach ($faq as $key => $item) {
                    if ( !empty($item['question']) && !empty($item['answer']) ) {
                    ?>
                        <div class="accordion-item accordion-item-faq">
                            <h4 class="accordion-header">
                                <a class="accordion-button <?php echo esc_attr($i !== 0 ? 'collapsed' : ''); ?>" role="button" data-bs-toggle="collapse" data-bs-parent="#listing-faq-accordion" href="#listing-faq-collapse-<?php echo esc_attr( $key ); ?>" aria-expanded="true" aria-controls="listing-faq-collapse-<?php echo esc_attr( $key ); ?>">
                                    <?php echo esc_html($item['question']); ?>
                                </a>
                            </h4>
                            <div id="listing-faq-collapse-<?php echo esc_attr( $key ); ?>" class="accordion-collapse collapse <?php echo esc_attr($i == 0 ? 'show' : ''); ?>" role="tabpanel" aria-labelledby="heading-<?php echo esc_attr( $key ); ?>">
                                <div class="accordion-body">
                                    <div class="description"><?php echo wpautop($item['answer']); ?></div>
                                </div>
                            </div>

                        </div>
                    <?php $i++; } ?>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php
}