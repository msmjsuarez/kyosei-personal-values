<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @package    Kyosei_Personal_Values
 * @subpackage Kyosei_Personal_Values/public
 * @author     MJ of Kyosei <mjsuarez@kyoseicreative.com>
 * @since      1.0.0
 */

class Kyose_Personal_Values_Public
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // AJAX actions
        add_action('wp_ajax_get_card_description', [$this, 'get_card_description']);
        add_action('wp_ajax_nopriv_get_card_description', [$this, 'get_card_description']);

        add_action('wp_ajax_save_top_personal_values', [$this, 'save_top_personal_values']);
        add_action('wp_ajax_nopriv_save_top_personal_values', [$this, 'save_top_personal_values']);

        add_action('wp_ajax_get_personal_values_by_date', [$this, 'get_personal_values_by_date']);
        add_action('wp_ajax_nopriv_get_personal_values_by_date', [$this, 'get_personal_values_by_date']);

        add_action('wp_ajax_get_modality_names', [$this, 'get_modality_names_callback']);
        add_action('wp_ajax_nopriv_get_modality_names', [$this, 'get_modality_names_callback']);

        add_action('wp_ajax_get_long_description', [$this, 'get_long_description']);
        add_action('wp_ajax_nopriv_get_long_description', [$this, 'get_long_description']);

        add_action('wp_ajax_get_card_data', [$this, 'get_card_data']);
        add_action('wp_ajax_nopriv_get_card_data', [$this, 'get_card_data']);

        add_shortcode('display_personal_value_titles', [$this, 'display_personal_value_titles_shortcode']);
    }

    /**
     * Enqueue public-facing stylesheets.
     */
    public function enqueue_styles()
    {
        // Main plugin CSS
        wp_enqueue_style(
            $this->plugin_name,
            plugin_dir_url(__FILE__) . 'css/kyose-personal-values-public.css',
            [],
            $this->version,
            'all'
        );

        // Bootstrap 4.5.2 CSS
        wp_enqueue_style(
            'bootstrap-4',
            'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css',
            [],
            '4.5.2'
        );

        // Font Awesome 5.15.3
        wp_enqueue_style(
            'font-awesome-5',
            'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css',
            [],
            '5.15.3'
        );
    }

    /**
     * Enqueue public-facing JavaScript.
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script(
            'popper-js',
            'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js',
            ['jquery'],
            '1.16.0',
            true
        );

        // Bootstrap 4.5.2 JS
        wp_enqueue_script(
            'bootstrap-4-js',
            'https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js',
            ['jquery', 'popper-js'],
            '4.5.2',
            true
        );

        // Main plugin JS
        wp_enqueue_script(
            $this->plugin_name . '-public',
            plugin_dir_url(__FILE__) . 'js/kyose-personal-values-public.js',
            ['jquery'],
            $this->version,
            false
        );

        // Localize script for AJAX
        wp_localize_script(
            'kyose-personal-values-public',
            'kyose_ajax_object',
            array(
                'ajax_url'       => admin_url('admin-ajax.php'),
                'max_card_count' => get_option('personal_values_selected_card_count', 12),
                'user_id'        => get_current_user_id(),
            )
        );
    }

    public function get_card_description()
    {
        if (!isset($_POST['title'])) {
            wp_send_json_error('Title not provided.');
        }

        global $wpdb;
        $title = sanitize_text_field($_POST['title']);
        $table = $wpdb->prefix . 'kyosei_personal_values';

        $row = $wpdb->get_row($wpdb->prepare("SELECT description, long_description, image FROM $table WHERE title = %s", $title));

        if ($row) {
            wp_send_json([
                'title'            => $title,
                'description'      => $row->description,
                'long_description' => apply_filters('the_content', $row->long_description),
                'image_url'        => $row->image
            ]);
        }

        wp_send_json_error('Description not found.');
    }

    public function save_top_personal_values()
    {
        if (!$this->isUserLoggedIn() || !isset($_POST['user_id'], $_POST['titles'])) {
            wp_send_json_error('Missing data or user not logged in.');
        }

        global $wpdb;
        $table = $wpdb->prefix . 'kyosei_personal_values_user';
        $user_id = absint($_POST['user_id']);
        $titles = serialize($_POST['titles']);
        $created_at = current_time('mysql');

        $wpdb->insert(
            $table,
            ['user_id' => $user_id, 'title' => $titles, 'created_at' => $created_at],
            ['%d', '%s', '%s']
        );

        $redirect_url = home_url('/index.php/my-personal-values/');

        wp_send_json_success([
            'message' => 'Top personal values saved.',
            'redirect_url' => $redirect_url
        ]);
    }

    public function isUserLoggedIn()
    {
        return is_user_logged_in();
    }

    public function display_personal_value_titles_shortcode()
    {
        if (!is_user_logged_in()) {
            return 'Please log in to see your personal value cards result.';
        }

        global $wpdb;
        $user_id = get_current_user_id();
        $table = $wpdb->prefix . 'kyosei_personal_values_user';

        $rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE user_id = %d ORDER BY created_at DESC", $user_id));

        if (!$rows) {
            return 'No personal value card found.';
        }

        ob_start();
?>
        <div><select id="dateSelect">
                <?php foreach ($rows as $row): ?>
                    <option value="<?= esc_attr($row->id); ?>"><?= esc_html(date('F j, Y g:i A', strtotime($row->created_at))); ?></option>
                <?php endforeach; ?>
            </select></div><br>

        <div id="personalValueContainer"></div>

        <script>
            jQuery(document).ready(function($) {
                $('#dateSelect').change(function() {
                    var id = $(this).val();
                    $.post(kyose_ajax_object.ajax_url, {
                        action: 'get_personal_values_by_date',
                        id: id
                    }, function(response) {
                        $('#personalValueContainer').html(response);
                    });
                }).change();
            });
        </script>
<?php
        return ob_get_clean();
    }

    public function get_personal_values_by_date()
    {
        if (!isset($_POST['id'])) {
            wp_die();
        }

        global $wpdb;
        $user_id = get_current_user_id();
        $id = absint($_POST['id']);
        $user_table = $wpdb->prefix . 'kyosei_personal_values_user';
        $value_table = $wpdb->prefix . 'kyosei_personal_values';
        $tag_table = $wpdb->prefix . 'kyosei_personal_value_modality_tags';

        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $user_table WHERE user_id = %d AND id = %d", $user_id, $id));

        if (!$row) {
            echo '<div class="alert alert-info">No personal value card titles found for the selected date.</div>';
            wp_die();
        }

        $titles = unserialize($row->title);
        $output = '<div class="top-titles-wrapper">';

        foreach ($titles as $index => $title) {
            $value = $wpdb->get_row($wpdb->prepare("SELECT * FROM $value_table WHERE title = %s", $title));
            if ($value) {
                $tag_names = [];

                foreach (explode(',', $value->modality_tag_ids) as $tag_id) {
                    $tag_name = $wpdb->get_var($wpdb->prepare("SELECT name FROM $tag_table WHERE id = %d", $tag_id));
                    if ($tag_name) {
                        $tag_names[] = esc_html($tag_name);
                    }
                }

                $output .= '<div class="personal-value-card">';
                $output .= '<div class="card-text-content">';
                $output .= '<h3 class="card-title">' . esc_html($title) . '</h3>';

                if (!empty($value->long_description)) {
                    $output .= '<p class="card-description">' . wp_kses_post(stripslashes($value->long_description)) . '</p>';
                } else {
                    $output .= '<p class="card-description"><em>No description available for this card.</em></p>';
                }

                $output .= '<p class="card-tags"><strong>Tags:</strong> ' . implode(', ', $tag_names) . '</p>';
                $output .= '</div>'; // .card-text-content

                $output .= '<div class="card-image">';
                $output .= '<img src="' . esc_url($value->image) . '" alt="' . esc_attr($title) . '">';
                $output .= '</div>'; // .card-image

                $output .= '</div>'; // .personal-value-card
            }
        }

        $output .= '</div>'; // .top-titles-wrapper
        echo $output;
        wp_die();
    }


    public function get_modality_names_callback()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'kyosei_personal_value_modality_tags';
        $names = $wpdb->get_results("SELECT id, name FROM $table");

        wp_send_json($names);
        wp_die();
    }

    public function get_long_description()
    {
        global $wpdb;
        $title = sanitize_text_field($_POST['title']);
        $table = $wpdb->prefix . 'kyosei_personal_values';
        $desc = $wpdb->get_var($wpdb->prepare("SELECT long_description FROM $table WHERE title = %s", $title));

        wp_send_json(['long_description' => $desc]);
    }

    public function get_card_data()
    {
        if (!isset($_POST['title'])) {
            wp_send_json_error(['message' => 'Title parameter is missing.']);
        }

        global $wpdb;
        $title = sanitize_text_field($_POST['title']);
        $table = $wpdb->prefix . 'kyosei_personal_values';
        $row = $wpdb->get_row($wpdb->prepare("SELECT title, long_description FROM $table WHERE title = %s", $title));

        if ($row) {
            wp_send_json([
                'title' => $row->title,
                'long_description' => $row->long_description,
            ]);
        } else {
            wp_send_json_error(['message' => 'Card data not found.']);
        }
    }
}
