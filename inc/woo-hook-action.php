<?php

defined('ABSPATH') || exit;

add_action('wp_enqueue_scripts', 'colors_swatches_woo_by_preeti_palsra_styles');
function colors_swatches_woo_by_preeti_palsra_styles()
{
    if (is_product()) {
        wp_enqueue_style('custom-color-variation-styles', plugins_url('/css/style.css', __FILE__), true);

    }
}
// Add a new tab for Anagha-P Swatches in the product data section
add_filter('woocommerce_product_data_tabs', 'colors_swatches_woo_by_preeti_palsra_tab');
function colors_swatches_woo_by_preeti_palsra_tab($tabs)
{
    $tabs['anagha_p_swatches'] = array(
        'label' => __('Swatches(Anagha)', 'colors-swatches-woo-by-preeti-palsra'),
        'target' => 'anagha_p_swatches_data',
        'class' => array('show_if_variable'),
        'priority' => 60,
    );
    return $tabs;
}

add_action('woocommerce_product_data_panels', 'colors_swatches_woo_by_preeti_palsra_fields');

function colors_swatches_woo_by_preeti_palsra_fields() {
    global $post;

    // Get saved swatch data
    $anagha_swatches = get_post_meta($post->ID, '_anagha_p_swatches', true);
   
    $anagha_swatches = is_array($anagha_swatches) ? $anagha_swatches : array();

    echo '<div id="anagha_p_swatches_data" class="panel woocommerce_options_panel">';
    echo '<div class="options_group">';

    // Get the product object
    $product = wc_get_product($post->ID);

    // Get the attributes for the product
    $attributes = $product->get_attributes();
    $lowercase_attributes = array_change_key_case($attributes, CASE_LOWER);

    // Define possible color attribute keys
    $color_keys = ['color', 'colors', 'pa_color', 'pa_colors'];
    $color_attribute = null;
    $color_key = null;

    // Find the color attribute
    foreach ($color_keys as $key) {
        if (isset($lowercase_attributes[$key])) {
            $color_attribute = $lowercase_attributes[$key];
            $color_key = $key;
            break;
        }
    }

    // Check if the color attribute is found
    if (!empty($color_attribute)) {
        echo '<div class="product_custom">';
        echo '<table class="widefat">';
        echo '<thead>';
        echo '<tr>';
        echo '<th class="attribute_swatch_label">Attribute [Color]</th>';
        echo '<th class="attribute_swatch_type">Swatch Type</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';

        if ($color_key === 'pa_color' || $color_key === 'pa_colors') {
            // Get the attribute taxonomy name (e.g., pa_color)
            if($color_key === 'pa_color' ){
                $taxonomy = 'pa_color';
            }

            if($color_key === 'pa_colors' ){
                $taxonomy = 'pa_colors';
            }
           
            // Get terms for the attribute
            $terms = get_terms([
                'taxonomy' => $taxonomy,
                'hide_empty' => false
            ]);
            
            foreach ($terms as $term) {
                // Get existing swatch data
                $color_code = isset($anagha_swatches[$term->term_id]['color_code']) ? $anagha_swatches[$term->term_id]['color_code'] : '';
                $image_url = isset($anagha_swatches[$term->term_id]['image_url']) ? $anagha_swatches[$term->term_id]['image_url'] : '';

                // Output row for each term
                echo '<tr>';
                echo '<td class="attribute_swatch_label">';
                echo sprintf(
                    '<strong>' . esc_html__( 'Term: %s', 'colors-swatches-woo-by-preeti-palsra' ) . '</strong>',
                    esc_html( $term->name )
                );
                echo '</td>';
                echo '<td class="attribute_swatch_type">';
                echo '<select class="swatch-select" name="anagha_p_swatches[' . esc_attr($term->term_id,'colors-swatches-woo-by-preeti-palsra') . '][swatch_type]">';
                echo '<option value="">Select Option</option>';
                echo '<option value="color" ' . selected(!empty($color_code), true, false) . '>Color Code</option>';
                echo '<option value="image" ' . selected(!empty($image_url), true, false) . '>Image</option>';
                echo '</select>';
                echo '<div class="swatch-input color-input" style="display: ' . (!empty($color_code) ? 'block' : 'none') . ';">';
                echo '<input type="color" name="anagha_p_swatches[' . esc_attr($term->term_id, 'colors-swatches-woo-by-preeti-palsra') . '][color_code]" value="' . esc_attr($color_code,'colors-swatches-woo-by-preeti-palsra') . '" class="color-field">';
                echo '</div>';
                echo '<div class="swatch-input image-input" style="display: ' . (!empty($image_url) ? 'block' : 'none') . ';">';
                echo '<input type="text" class="image-url regular-text" name="anagha_p_swatches[' . esc_attr($term->term_id, 'colors-swatches-woo-by-preeti-palsra') . '][image_url]" value="' . esc_attr($image_url,'colors-swatches-woo-by-preeti-palsra') . '">';
                echo '<button type="button" class="button upload-image-button">Upload Image</button>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
        } else {
            // Handle non-pa_color attributes
            foreach ($color_attribute->get_options() as $color) {
                // Get existing swatch data
                $color_code = isset($anagha_swatches[$color]['color_code']) ? $anagha_swatches[$color]['color_code'] : '';
                $image_url = isset($anagha_swatches[$color]['image_url']) ? $anagha_swatches[$color]['image_url'] : '';

                // Output row for each color option
                echo '<tr>';
                echo '<td class="attribute_swatch_label">';
                echo sprintf(
                    '<strong>' . esc_html__( 'Color: %s', 'colors-swatches-woo-by-preeti-palsra' ) . '</strong>',
                    esc_html( $color )
                );
                
                echo '</td>';
                echo '<td class="attribute_swatch_type">';
                echo '<select class="swatch-select" name="anagha_p_swatches[' . esc_attr($color,'colors-swatches-woo-by-preeti-palsra') . '][swatch_type]">';
                echo '<option value="">Select Option</option>';
                echo '<option value="color" ' . selected(!empty($color_code), true, false) . '>Color Code</option>';
                echo '<option value="image" ' . selected(!empty($image_url), true, false) . '>Image</option>';
                echo '</select>';
                echo '<div class="swatch-input color-input" style="display: ' . (!empty($color_code) ? 'block' : 'none') . ';">';
                echo '<input type="color" name="anagha_p_swatches[' . esc_attr($color,'colors-swatches-woo-by-preeti-palsra') . '][color_code]" value="' . esc_attr($color_code,'colors-swatches-woo-by-preeti-palsra') . '" class="color-field">';
                echo '</div>';
                echo '<div class="swatch-input image-input" style="display: ' . (!empty($image_url) ? 'block' : 'none') . ';">';
                echo '<input type="text" class="image-url regular-text" name="anagha_p_swatches[' . esc_attr($color,'colors-swatches-woo-by-preeti-palsra') . '][image_url]" value="' . esc_attr($image_url,'colors-swatches-woo-by-preeti-palsra') . '">';
                echo '<button type="button" class="button upload-image-button">Upload Image</button>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
        }

        echo '</tbody>';
        echo '</table>';
        echo '</div>'; // Close .product_custom
    }else{
        echo 'Please save the product or referesh the page if data not loaded. ';
    }

    echo '</div>'; // Close .options_group
    echo '</div>'; // Close .panel

    ?>

    <?php
}



// Save the swatch data when the product is saved
add_action('woocommerce_process_product_meta', 'colors_swatches_woo_by_preeti_palsra_save');
function colors_swatches_woo_by_preeti_palsra_save($post_id)
{   
    if (isset($_POST['anagha_p_swatches'])) {
        if ( isset( $_POST['anagha_p_swatches'] ) && is_array( $_POST['anagha_p_swatches'] ) ) {
            // Sanitize each value in the array
            $sanitized_swatches = wp_unslash($_POST['anagha_p_swatches']) ;
        } else {
            $sanitized_swatches = array(); // Default to an empty array if not set or not an array
        }
        $sanitized_data = array();
      
        if(!empty($sanitized_swatches) && is_array($sanitized_swatches)){
            foreach ( $sanitized_swatches as $color => $swatch_data) {
                if (!empty($swatch_data)) {
                    if ($swatch_data['swatch_type'] == 'color') {
                        $sanitized_data[$color] = array(
                            'color_code' => sanitize_text_field($swatch_data['color_code'])
                        );
                    } else if ($swatch_data['swatch_type'] == 'image') {
                        $sanitized_data[$color] = array(
                            'image_url' => esc_url_raw($swatch_data['image_url'],'colors-swatches-woo-by-preeti-palsra')
                        );
                    }
                }

            }
         }

        update_post_meta($post_id, '_anagha_p_swatches', $sanitized_data);
    }
}


//display variation changes 
add_action('woocommerce_before_single_variation', 'colors_swatches_woo_by_preeti_palsra_single_variation', 20);
function colors_swatches_woo_by_preeti_palsra_single_variation()
{
    global $product;
    $id = $product->get_id();
    $product = wc_get_product($id);

    if ($product->is_type('variable')) {

        /***
         * add the swatched logic with layouts
         */
        $attributes = $product->get_variation_attributes();
        $lowercase_attributes = array_change_key_case($attributes, CASE_LOWER);
          // Define possible color attribute keys
        $color_keys = ['color', 'colors', 'pa_color', 'pa_colors'];
        $color_attribute = null;
        $color_key = null;

        // Find the color attribute
        foreach ($color_keys as $key) {
            if (isset($lowercase_attributes[$key])) {
                $color_attribute = $lowercase_attributes[$key];
                $color_key = $key;
                break;
            }
        }
 
        if ($color_attribute) {
            echo '<fieldset class="color-swatches js-color-swatches">';
            echo '<legend class="color-swatches__legend" aria-live="polite" aria-atomic="true">Pick Your Color</legend>';
            echo '<div class="swatches-container">';

            $swatch_datas = get_post_meta( $id, '_anagha_p_swatches', true);

            if ($color_key === 'pa_color' || $color_key === 'pa_colors') {
                // Get the attribute taxonomy name (e.g., pa_color)
                if($color_key === 'pa_color' ){
                    $taxonomy = 'pa_color';
                }
    
                if($color_key === 'pa_colors' ){
                    $taxonomy = 'pa_colors';
                }
               
                // Get terms for the attribute
                $terms = get_terms([
                    'taxonomy' => $taxonomy,
                    'hide_empty' => false
                ]);
                
                foreach ($terms as $term) {
                    $swatch_data = isset($swatch_datas[$term->term_id]) ? $swatch_datas[$term->term_id] : null;
                    $style = '';
                    $attrib = '';
                    if (!empty($swatch_data)) {
                        if (!empty($swatch_data['color_code'])) {
                            $style = 'background-color: ' . esc_attr($swatch_data['color_code'],'colors-swatches-woo-by-preeti-palsra') . ';';
                            $attrib = 'data-color = "' . esc_url($swatch_data['color_code'],'colors-swatches-woo-by-preeti-palsra') . '"';
                        } elseif (!empty($swatch_data['image_url'])) {
                            $style = 'background-image: url(' . esc_url($swatch_data['image_url'],'colors-swatches-woo-by-preeti-palsra') . '); background-size: cover;';
                            $attrib = 'data-url = "' . esc_url($swatch_data['image_url'],'colors-swatches-woo-by-preeti-palsra') . '"';
                        }
                    }

                    echo '<label class="swatch-label" style="' . esc_attr($style,'colors-swatches-woo-by-preeti-palsra') . '"  ' . esc_attr($attrib,'colors-swatches-woo-by-preeti-palsra') . ' >';
                    echo '<input type="radio" class="color-swatches-cust" name="attribute_pa_color" value="' . esc_attr($term->name,'colors-swatches-woo-by-preeti-palsra') . '" style="opacity: 0;" />';
                    echo '</label>';
                }
            } else{
                foreach ($color_attribute as $color_slug => $color_name) {

                    $swatch_data = isset($swatch_datas[$color_name]) ? $swatch_datas[$color_name] : null;
        
                    $style = '';
                    $attrib = '';
                    if (!empty($swatch_data)) {
                        if (!empty($swatch_data['color_code'])) {
                            $style = 'background-color: ' . esc_attr($swatch_data['color_code'],'colors-swatches-woo-by-preeti-palsra') . ';';
                            $attrib = 'data-color = "' . esc_url($swatch_data['color_code'],'colors-swatches-woo-by-preeti-palsra') . '"';
                        } elseif (!empty($swatch_data['image_url'])) {
                            $style = 'background-image: url(' . esc_url($swatch_data['image_url'],'colors-swatches-woo-by-preeti-palsra') . '); background-size: cover;';
                            $attrib = 'data-url = "' . esc_url($swatch_data['image_url'],'colors-swatches-woo-by-preeti-palsra') . '"';
                        }
                    }

                    echo '<label class="swatch-label" style="' . esc_attr($style,'colors-swatches-woo-by-preeti-palsra') . '"  ' . esc_attr($attrib,'colors-swatches-woo-by-preeti-palsra') . ' >';
                    echo '<input type="radio" class="color-swatches-cust" name="attribute_pa_color" value="' . esc_attr($color_name,'colors-swatches-woo-by-preeti-palsra') . '" style="opacity: 0;" />';
                    echo '</label>';
                }
            }

            echo '</div>';
            echo '</fieldset>';
        }
        wp_enqueue_script('custom-color-variation-scripts', plugins_url('/js/custom.js', __FILE__), array('jquery'), '1.0.0', true);

     
    }

}



function colors_swatches_woo_by_preeti_palsra_remove_dropdowns($html, $args) {
    $attributes_to_remove = array('Color', 'Size', 'pa_color', 'pa_size');

    if (in_array(strtolower($args['attribute']), $attributes_to_remove)) {
        return ''; // Remove dropdown
    }

    return $html;
}
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'colors_swatches_woo_by_preeti_palsra_remove_dropdowns', 10, 2);

function colors_swatches_woo_by_preeti_palsra_remove_labels($label, $name, $product) {
    $attributes_to_remove = array('Color', 'Size', 'pa_color', 'pa_size');

    if (in_array(strtolower($name), $attributes_to_remove)) {
        return ''; // Remove label
    }

    return $label;
}
add_filter('woocommerce_attribute_label', 'colors_swatches_woo_by_preeti_palsra_remove_labels', 10, 3);


