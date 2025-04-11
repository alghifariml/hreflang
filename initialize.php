<?php

/**
 * Plugin Name: Hreflang URL Meta Box
 * Description: Adds meta boxes for x-default, English, and Indonesian hreflang URLs for all public post types.
 * Version: 1.0.10
 * Author: M Luthfi Al Ghifari
 */

/**
 * 1. Add meta boxes for all public post types and taxonomies
 */
add_action('add_meta_boxes', 'hreflang_add_meta_boxes');
function hreflang_add_meta_boxes()
{
    // Get all public post types
    $post_types = get_post_types(['public' => true], 'names');

    // Add meta box for all public post types
    foreach ($post_types as $post_type) {
        add_meta_box(
            'hreflang_meta_box',
            'Hreflang URLs',
            'hreflang_meta_box_callback',
            $post_type,
            'normal',
            'high'
        );
    }
}

/**
 * 2. Render the Meta Box fields
 */
function hreflang_meta_box_callback($object)
{
    // Security nonce
    wp_nonce_field('save_hreflang_meta_box_data', 'hreflang_meta_box_nonce');

    // Check if we're dealing with a post or term
    $is_term = isset($object->term_id);

    if ($is_term) {
        // For terms
        $hreflang_en = get_term_meta($object->term_id, '_hreflang_en', true);
        $hreflang_id = get_term_meta($object->term_id, '_hreflang_id', true);
    } else {
        // For posts
        $hreflang_en = get_post_meta($object->ID, '_hreflang_en', true);
        $hreflang_id = get_post_meta($object->ID, '_hreflang_id', true);
    }
?>
    <p>
        <label for="hreflang_en">English URL (hreflang="en"):</label><br />
        <input
            type="text"
            name="hreflang_en"
            id="hreflang_en"
            value="<?php echo esc_attr($hreflang_en); ?>"
            style="width: 100%;" />
    </p>
    <p>
        <label for="hreflang_id">Indonesian URL (hreflang="id"):</label><br />
        <input
            type="text"
            name="hreflang_id"
            id="hreflang_id"
            value="<?php echo esc_attr($hreflang_id); ?>"
            style="width: 100%;" />
    </p>
<?php
}

/**
 * 3. Save the Meta Box Data
 */
add_action('save_post', 'hreflang_save_post_meta_box_data');
add_action('edited_term', 'hreflang_save_term_meta_box_data', 10, 3);

// Function to save post meta
function hreflang_save_post_meta_box_data($post_id)
{
    // Check nonce
    if (
        !isset($_POST['hreflang_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['hreflang_meta_box_nonce'], 'save_hreflang_meta_box_data')
    ) {
        return;
    }

    // Check for autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save English URL
    if (isset($_POST['hreflang_en'])) {
        $value = esc_url_raw($_POST['hreflang_en']);
        update_post_meta($post_id, '_hreflang_en', $value);
    }

    // Save Indonesian URL
    if (isset($_POST['hreflang_id'])) {
        $value = esc_url_raw($_POST['hreflang_id']);
        update_post_meta($post_id, '_hreflang_id', $value);
    }
}

// Function to save term meta
function hreflang_save_term_meta_box_data($term_id, $tt_id, $taxonomy)
{
    // Check nonce
    if (
        !isset($_POST['hreflang_meta_box_nonce']) ||
        !wp_verify_nonce($_POST['hreflang_meta_box_nonce'], 'save_hreflang_meta_box_data')
    ) {
        return;
    }

    // Check user permissions
    if (!current_user_can('edit_term', $term_id)) {
        return;
    }

    // Save English URL
    if (isset($_POST['hreflang_en'])) {
        $value = esc_url_raw($_POST['hreflang_en']);
        update_term_meta($term_id, '_hreflang_en', $value);
    }

    // Save Indonesian URL
    if (isset($_POST['hreflang_id'])) {
        $value = esc_url_raw($_POST['hreflang_id']);
        update_term_meta($term_id, '_hreflang_id', $value);
    }
}

/**
 * 4. Automatically output the Hreflang links in the <head>
 */
add_action('wp_head', 'add_hreflang_links');
function add_hreflang_links()
{
    if (is_singular() || is_tax()) {
        // Get the post or term ID
        if (is_singular()) {
            $post_id = get_the_ID();
        } elseif (is_tax()) {
            $term = get_queried_object();
            $term_id = $term->term_id; // Use term ID for taxonomy pages
        }

        // Retrieve the stored values for posts
        $hreflang_en = get_post_meta($post_id, '_hreflang_en', true);
        $hreflang_id = get_post_meta($post_id, '_hreflang_id', true);

        // Retrieve the stored values for taxonomy terms
        if (isset($term_id)) {
            $hreflang_en = get_term_meta($term_id, '_hreflang_en', true);
            $hreflang_id = get_term_meta($term_id, '_hreflang_id', true);
        }

        // If we have an English URL, output both "en" and "x-default"
        if ($hreflang_en) {
            echo '<link rel="alternate" href="'
                . esc_url($hreflang_en) . '" hreflang="x-default" />' . "\n";

            echo '<link rel="alternate" href="'
                . esc_url($hreflang_en) . '" hreflang="en" />' . "\n";
        }

        // Output Indonesian if defined
        if ($hreflang_id) {
            echo '<link rel="alternate" href="'
                . esc_url($hreflang_id) . '" hreflang="id" />' . "\n";
        }
    }
}
