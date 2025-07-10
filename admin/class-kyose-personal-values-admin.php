<?php

class Kyose_Personal_Values_Admin
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action('admin_menu', [$this, 'kyosei_personal_values_menu']);
        add_action('wp_ajax_delete_card', array($this, 'delete_card'));
        add_action('admin_menu', [$this, 'register_personal_values_submenu']);

        // AJAX action to fetch personal values based on search term
        add_action('wp_ajax_fetch_personal_values', [$this, 'fetch_personal_values_callback']);
        add_action('wp_ajax_nopriv_fetch_personal_values', [$this, 'fetch_personal_values_callback']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_wp_editor']);

        // Add the save function to the 'admin_post' action hook
        add_action('admin_post_personal_values_compare_page_text_save', [$this, 'save_compare_page_text_settings']);

        // Add the options page to the allowed options list
        add_action('admin_init', [$this, 'register_compare_page_text_settings']);

        add_action('show_user_profile', [$this, 'display_custom_user_profile_link']);
        add_action('edit_user_profile', [$this, 'display_custom_user_profile_link']);
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style(
            'bootstrap-admin',
            'https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css',
            [],
            '4.5.0'
        );

        wp_enqueue_style(
            $this->plugin_name . '-admin',
            plugin_dir_url(__FILE__) . 'css/kyose-personal-values-admin.css',
            ['bootstrap-admin'],
            $this->version,
            'all'
        );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/kyose-personal-values-admin.js', ['jquery'], $this->version, false);

        wp_enqueue_script(
            'kyose-personal-values-admin',
            plugin_dir_url(__FILE__) . 'js/kyose-personal-values-admin.js',
            array('jquery'),
            $this->version,
            true
        );

        wp_localize_script('kyose-personal-values-admin', 'kyose_personal_values_settings', array(
            'cards_per_row'   => get_option('personal_values_cards_per_row', 3),
            'current_page'    => max(1, get_query_var('paged', 1)),
            'delete_nonce'    => wp_create_nonce('delete_card'),
            'search_nonce'    => wp_create_nonce('fetch_personal_values'),
        ));

        // Pass the number of cards per row to the frontend
        $cards_per_row = get_option('personal_values_cards_per_row', 3);
        wp_localize_script($this->plugin_name, 'kyose_personal_values_settings', ['cards_per_row' => $cards_per_row]);
    }

    public function enqueue_wp_editor()
    {
        wp_enqueue_editor();
    }

    public function kyosei_personal_values_menu()
    {
        add_menu_page(
            'Personal Values',
            'Personal Values',
            'manage_options',
            'personal-values',
            [$this, 'render_personal_values']
        );
    }

    public function render_personal_values()
    {
        if (isset($_GET['action']) && $_GET['action'] === 'delete') {
            $this->delete_card();
        } elseif (isset($_POST['add_card'])) {
            self::addCard();
        } elseif (isset($_POST['update_card'])) {
            self::updateCard();
        } elseif (isset($_GET['action']) && $_GET['action'] === 'edit') {
            self::renderEditForm();
            return;
        }

        $this->renderCardList();
    }

    private static function addCard()
    {
        // Handle form submission to add a new Personal Value card
        if (isset($_POST['add_card'])) {
            $title = stripslashes(sanitize_text_field($_POST['title']));
            $description = stripslashes(sanitize_text_field($_POST['description']));
            $longDescription = stripslashes(wp_kses_post($_POST['long_description']));
            $image = $_FILES['image'];

            $unique_identifier = time();

            $upload_dir = wp_upload_dir();
            $image_name = basename($image['name']);

            $image_name_parts = pathinfo($image['name']);
            $image_name = $image_name_parts['filename'] . '_' . $unique_identifier . '.' . $image_name_parts['extension'];

            $image_path = $upload_dir['path'] . '/' . $image_name;
            move_uploaded_file($image['tmp_name'], $image_path);
            $image_url = $upload_dir['url'] . '/' . $image_name;

            // Retrieve the modality tag IDs
            $modalityTags = isset($_POST['modality_tags']) ? $_POST['modality_tags'] : array();
            $modalityTagIds = array_map('absint', $modalityTags); // Sanitize the modality tag IDs as integers

            if (!is_array($modalityTagIds)) {
                $modalityTagIds = array();
            }

            global $wpdb;
            $table_name = $wpdb->prefix . 'kyosei_personal_values';
            $wpdb->insert(
                $table_name,
                array(
                    'title' => $title,
                    'description' => $description,
                    'long_description' => $longDescription,
                    'image' => $image_url,
                    'modality_tag_ids' => implode(',', $modalityTagIds) // Store the modality tag IDs as comma-separated values
                ),
                array('%s', '%s', '%s', '%s', '%s')
            );

            echo '<div class="notice notice-success"><p>New Personal Value card added successfully!</p></div>';
        }
    }

    private static function updateCard()
    {
        $card_id = $_POST['card_id'];
        $title = stripslashes(sanitize_text_field($_POST['title']));
        $description = stripslashes(sanitize_text_field($_POST['description']));
        $longDescription = stripslashes(wp_kses_post($_POST['long_description']));
        $image = $_FILES['image'];

        global $wpdb;
        $table_name = $wpdb->prefix . 'kyosei_personal_values';
        $existing_card = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $card_id), ARRAY_A);
        if (!$existing_card) {
            return;
        }

        $existing_image_url = $existing_card['image'];

        if ($image['error'] === UPLOAD_ERR_OK) {
            $upload_dir = wp_upload_dir();
            $image_name = basename($image['name']);

            $unique_identifier = time();

            // Append the unique identifier to the image name before the extension
            $image_name_parts = pathinfo($image_name);
            $image_name = $image_name_parts['filename'] . '_' . $unique_identifier . '.' . $image_name_parts['extension'];

            $image_path = $upload_dir['path'] . '/' . $image_name;
            move_uploaded_file($image['tmp_name'], $image_path);

            // Get the image URL relative to the WordPress installation
            $image_relative_url = str_replace($upload_dir['basedir'], $upload_dir['baseurl'], $image_path);

            // Update the image path with the relative URL
            $image_path = $image_relative_url;

            // Delete the previous image file if it exists
            $previous_image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $existing_image_url);
            if (file_exists($previous_image_path)) {
                unlink($previous_image_path);
            }
        } else {
            // No new image uploaded, retain the existing image URL
            $image_path = $existing_image_url;
        }

        // Retrieve and sanitize the modality tags
        $modalityTags = isset($_POST['modality_tags']) ? $_POST['modality_tags'] : array();
        $modalityTagIds = array_map('sanitize_text_field', $modalityTags);

        error_log('Image Path: ' . $image_path);

        // Update the card in the database
        $wpdb->update(
            $table_name,
            array(
                'title' => $title,
                'description' => $description,
                'long_description' => $longDescription,
                'image' => $image_path,
                'modality_tag_ids' => implode(',', $modalityTagIds)
            ),
            array('id' => $card_id),
            array('%s', '%s', '%s', '%s'),
            array('%d')
        );
    }

    public function delete_card()
    {
        check_ajax_referer('delete_card', 'security');

        if (!current_user_can('delete_posts')) {
            wp_send_json_error(array('message' => 'Unauthorized'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . 'kyosei_personal_values';

        $card_id = intval($_POST['card_id']);

        // Retrieve the card from the database
        $card = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $card_id), ARRAY_A);

        // Delete the card from the database
        $result = $wpdb->delete($table_name, array('id' => $card_id));

        if ($result === false) {
            wp_send_json_error(array('message' => 'Error deleting card'));
        }

        // Remove the image file from the uploads folder
        $image_url = $card['image'];
        $upload_dir = wp_upload_dir();
        $image_path = str_replace($upload_dir['baseurl'], $upload_dir['basedir'], $image_url);
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        wp_send_json_success();
    }

    private function renderCardList()
    {
        include plugin_dir_path(__FILE__) . 'partials/kyose-personal-values-admin-display.php';
    }

    private function renderEditForm()
    {
        include_once plugin_dir_path(__FILE__) . 'partials/kyose-personal-values-admin-edit-card-form.php';
    }

    // Add submenu page to Personal Values menu
    public function register_personal_values_submenu()
    {
        add_submenu_page(
            'personal-values',
            'Modality Tags',
            'Modality Tags',
            'manage_options',
            'modality-tags',
            [$this, 'modality_tags_page']
        );

        add_submenu_page(
            'personal-values', // Parent slug
            'Settings', // Page title
            'Cards Per Row', // Menu title
            'manage_options', // Capability required to access the page
            'personal-values-settings', // Menu slug
            [$this, 'render_personal_values_settings'] // Callback function to render the page
        );

        // New submenu page for settings - Cards Selection
        add_submenu_page(
            'personal-values',
            'Cards Selection',
            'Cards Selection',
            'manage_options',
            'personal-values-cards-selection',
            [$this, 'render_cards_selection_settings']
        );

        // New submenu page for Compare Page Text settings
        add_submenu_page(
            'personal-values',
            'Compare Page Text',
            'Compare Page Text',
            'manage_options',
            'personal-values-compare-page-text',
            [$this, 'render_compare_page_text_settings']
        );
    }

    // Modality Tags page callback function
    public function modality_tags_page()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'kyosei_personal_value_modality_tags';

        // Handle form submission to add new Modality tag
        if (isset($_POST['add_modality_tag'])) {
            $name = sanitize_text_field($_POST['name']);
            $slug = sanitize_title($name); // Auto-generate the slug from the name
            $slug = str_replace(' ', '-', $slug); // Replace spaces with hyphens
            $slug = strtolower($slug); // Convert to lowercase

            global $wpdb;
            $table_name = $wpdb->prefix . 'kyosei_personal_value_modality_tags';

            $wpdb->insert(
                $table_name,
                array(
                    'name' => $name,
                    'slug' => $slug,
                )
            );

            // Show success message
            echo '<div class="notice notice-success"><p>New Modality tag added successfully!</p></div>';
        }

        // Retrieve all Modality tags from the database
        $modality_tags = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);
?>
        <div class="wrap">
            <h1>Modality Tags</h1>

            <h2>Add New Modality Tag</h2>
            <form method="post">
                <label for="name">Name:</label>
                <input type="text" name="name" required>
                <label for="slug">Slug:</label>
                <input type="text" name="slug" required>
                <input type="submit" name="add_modality_tag" value="Add Modality Tag">
            </form>

            <h2>Modality Tags List</h2>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($modality_tags as $tag) { ?>
                        <tr>
                            <td><?php echo esc_html($tag['name']); ?></td>
                            <td><?php echo esc_html($tag['slug']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php
    }


    // Render the settings page
    public function render_personal_values_settings()
    {
        // Check if form is submitted and update the option
        if (isset($_POST['personal_values_submit'])) {
            $cards_per_row = isset($_POST['cards_per_row']) ? absint($_POST['cards_per_row']) : 3;
            update_option('personal_values_cards_per_row', $cards_per_row);
            echo '<div class="notice notice-success"><p>Settings saved successfully.</p></div>';
        }

        // Retrieve the current value of cards per row option
        $cards_per_row = get_option('personal_values_cards_per_row', 3);
    ?>

        <div class="wrap">
            <h1>Cards Per Row Settings</h1>

            <form method="post">
                <table class="form-table">
                    <tr>
                        <th scope="row">Number of Cards Per Row</th>
                        <td>
                            <select name="cards_per_row">
                                <?php for ($i = 1; $i <= 4; $i++) : ?>
                                    <option value="<?php echo $i; ?>" <?php selected($cards_per_row, $i); ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="personal_values_submit" class="button-primary" value="Save Changes">
                </p>
            </form>
        </div>

    <?php
    }

    public function fetch_personal_values_callback()
    {
        // Verify the AJAX nonce for security
        check_ajax_referer('fetch_personal_values', 'security');

        global $wpdb;
        $table_name = $wpdb->prefix . 'kyosei_personal_values';
        $modality_tags_table = $wpdb->prefix . 'kyosei_personal_value_modality_tags';

        // Retrieve the search term from the AJAX request
        $searchTerm = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';

        // Build the SQL query based on the search term
        $query = "SELECT p.*, GROUP_CONCAT(t.name SEPARATOR ', ') AS modality_tags
                FROM $table_name AS p
                LEFT JOIN $modality_tags_table AS t
                ON FIND_IN_SET(t.id, p.modality_tag_ids)
                WHERE p.title LIKE '%$searchTerm%'
                GROUP BY p.id
                ORDER BY p.title ASC";

        // Retrieve the filtered cards
        $cards = $wpdb->get_results($query, ARRAY_A);

        // Prepare the HTML content for the table body
        ob_start();
        foreach ($cards as $card) {
            echo '<tr>';
            echo '<td>' . esc_html($card['title']) . '</td>';
            echo '<td>' . esc_html($card['description']) . '</td>';
            echo '<td><img src="' . esc_url($card['image']) . '" width="100" height="100"></td>';
            echo '<td>' . (!empty($card['modality_tags']) ? esc_html($card['modality_tags']) : '-') . '</td>';
            echo '<td>';
            echo '<a href="?page=personal-values&action=edit&card_id=' . esc_attr($card['id']) . '">Edit</a> | ';
            echo '<a href="#" class="delete-card" data-card-id="' . esc_attr($card['id']) . '">Delete</a>';
            echo '</td>';
            echo '</tr>';
        }
        $html = ob_get_clean();

        // Return the response as JSON
        wp_send_json_success(array('html' => $html));
    }

    public function render_cards_selection_settings()
    {
        // Check if form is submitted and update the option
        if (isset($_POST['cards_selection_submit'])) {
            $selected_card_count = isset($_POST['selected_card_count']) ? absint($_POST['selected_card_count']) : 5;
            update_option('personal_values_selected_card_count', $selected_card_count);
            echo '<div class="notice notice-success"><p>Card selection settings saved successfully.</p></div>';
        }

        // Retrieve the current value of cards selected by the admin
        $selected_card_count = get_option('personal_values_selected_card_count', 5);
    ?>

        <div class="wrap">
            <h1>Cards Selection Settings</h1>

            <form method="post">
                <table class="form-table">
                    <tr>
                        <th scope="row">Number of Cards to Be Selected</th>
                        <td>
                            <select name="selected_card_count">
                                <option value="4" <?php selected($selected_card_count, 4); ?>>4</option>
                                <option value="8" <?php selected($selected_card_count, 8); ?>>8</option>
                                <option value="12" <?php selected($selected_card_count, 12); ?>>12</option>
                                <option value="15" <?php selected($selected_card_count, 15); ?>>15</option>
                                <option value="20" <?php selected($selected_card_count, 20); ?>>20</option>
                            </select>
                        </td>
                    </tr>
                </table>

                <p class="submit">
                    <input type="submit" name="cards_selection_submit" class="button-primary" value="Save Changes">
                </p>
            </form>
        </div>

    <?php
    }

    public function render_compare_page_text_settings()
    {
        // Output the settings fields and sections for the options page
    ?>
        <div class="wrap">
            <h1>Compare Page Text Settings</h1>
            <form method="post" action="options.php">
                <?php settings_fields('personal_values_compare_page_text_group'); ?>
                <?php do_settings_sections('personal-values-compare-page-text'); ?>

                <h2>Top Text</h2>
                <?php
                // Create WYSIWYG editor for 'Top' text field
                wp_editor(
                    get_option('personal_values_compare_page_text_top', ''), // Retrieve the saved value
                    'personal_values_compare_page_text_top',
                    array(
                        'textarea_name' => 'personal_values_compare_page_text_top',
                        'textarea_rows' => 10,
                        'editor_height' => 200,
                    )
                );
                ?>

                <h2>Bottom Text</h2>
                <?php
                // Create WYSIWYG editor for 'Bottom' text field
                wp_editor(
                    get_option('personal_values_compare_page_text_bottom', ''), // Retrieve the saved value
                    'personal_values_compare_page_text_bottom',
                    array(
                        'textarea_name' => 'personal_values_compare_page_text_bottom',
                        'textarea_rows' => 10,
                        'editor_height' => 200,
                    )
                );
                ?>
                <?php submit_button(); ?>
            </form>
        </div>
<?php
    }


    public function save_compare_page_text_settings()
    {
        // Check if the form is submitted and the user has the capability to save options
        if (isset($_POST['submit']) && current_user_can('manage_options')) {
            // Save the values from the WYSIWYG editors to the options table
            $compare_page_text_top = isset($_POST['personal_values_compare_page_text_top']) ? $_POST['personal_values_compare_page_text_top'] : '';
            $compare_page_text_bottom = isset($_POST['personal_values_compare_page_text_bottom']) ? $_POST['personal_values_compare_page_text_bottom'] : '';

            update_option('personal_values_compare_page_text_top', $compare_page_text_top);
            update_option('personal_values_compare_page_text_bottom', $compare_page_text_bottom);

            // Redirect back to the settings page with a success message
            wp_redirect(admin_url('admin.php?page=personal-values-compare-page-text&settings-updated=true'));
            exit;
        }
    }

    public function register_compare_page_text_settings()
    {
        // Add the options page to the allowed options list
        register_setting('personal_values_compare_page_text_group', 'personal_values_compare_page_text_top');
        register_setting('personal_values_compare_page_text_group', 'personal_values_compare_page_text_bottom');
    }

    public function display_custom_user_profile_link($user_id)
    {
        // Get the current user's last personal value test result
        global $wpdb;
        $table_name = $wpdb->prefix . 'kyosei_personal_values_user';

        $last_test_result = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table_name WHERE user_id = %d ORDER BY created_at DESC LIMIT 1",
            $user_id->ID
        ));

        if ($last_test_result) {
            // Extract and decode the 'title' value
            $title_data = unserialize($last_test_result->title);
            $created_at = $last_test_result->created_at;
            $timestamp = strtotime($created_at);
            $formatted_date = date('F j, Y', $timestamp);

            // Check if title_data is an array and not empty
            if (is_array($title_data) && !empty($title_data)) {
                echo '<h3>Personal Values:</h3>';
                echo $formatted_date . ': ';
                foreach ($title_data as $title) {
                    echo ' ' . esc_html($title) . ', ';
                }
            } else {
                echo '<p>No personal value test data found for this user.</p>';
            }
        } else {
            echo '<p>No personal value test data found for this user.</p>';
        }

        $top_values_link = '<a href="/my-top-values/">see details</a>';

        // Output the custom link
        echo $top_values_link;
    }
}
