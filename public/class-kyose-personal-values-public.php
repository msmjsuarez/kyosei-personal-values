<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @see       https://https://kyoseicreative.com/
 * @since      1.0.0
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @author     MJ of Kyosei <mjsuarez@kyoseicreative.com>
 */
class Kyose_Personal_Values_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the ID of this plugin
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the current version of this plugin
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param string $plugin_name the name of the plugin
     * @param string $version     the version of this plugin
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        // Add the AJAX action hook for get_card_description
        add_action('wp_ajax_get_card_description', array($this, 'get_card_description'));
        add_action('wp_ajax_nopriv_get_card_description', array($this, 'get_card_description'));

        // Add the AJAX action to save the top 5 personal values
        add_action('wp_ajax_save_top_personal_values', array($this, 'save_top_personal_values'));
        add_action('wp_ajax_nopriv_save_top_personal_values', array($this, 'save_top_personal_values')); // Allow non-logged-in users to access the AJAX endpoint as well

        add_shortcode('display_personal_value_titles', array($this, 'display_personal_value_titles_shortcode'));

        add_action('wp_ajax_get_personal_values_by_date', array($this, 'get_personal_values_by_date'));
        add_action('wp_ajax_nopriv_get_personal_values_by_date', array($this, 'get_personal_values_by_date'));

        add_action('wp_ajax_get_modality_names', array($this, 'get_modality_names_callback'));
        add_action('wp_ajax_nopriv_get_modality_names', array($this, 'get_modality_names_callback')); // Allow non-logged-in users to access this action

        add_action('wp_ajax_get_long_description', array($this, 'get_long_description'));
        add_action('wp_ajax_nopriv_get_long_description', array($this, 'get_long_description'));
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Kyose_Personal_Values_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Kyose_Personal_Values_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/kyose-personal-values-public.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Kyose_Personal_Values_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Kyose_Personal_Values_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name . '-jquery-3.6.0.min', plugin_dir_url(__FILE__) . 'js/jquery-3.6.0.min.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->plugin_name . '-jquery-migrate-1.4.1.min', plugin_dir_url(__FILE__) . 'js/jquery-migrate-1.4.1.min.js', ['jquery'], $this->version, false);
        wp_enqueue_script($this->plugin_name . '-kyose-personal-values-public', plugin_dir_url(__FILE__) . 'js/kyose-personal-values-public.js', ['jquery'], $this->version, false);

        // Localize the script with the admin-ajax URL
        wp_localize_script(
            $this->plugin_name . '-kyose-personal-values-public', // Handle of the script to be localized
            'kyose_ajax_object', // Name of the variable to use in JavaScript
            array('ajax_url' => admin_url('admin-ajax.php'))
        );
    }

    public function get_card_description()
    {
        if (isset($_POST['title'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'kyosei_personal_values';

            $title = sanitize_text_field($_POST['title']);
            $description = '';
            $long_description = '';
            $image_url = '';

            // Prepare and execute the database query
            $query = $wpdb->prepare("SELECT description, long_description, image FROM $table_name WHERE title = %s", $title);
            $result = $wpdb->get_row($query);

            if ($result) {
                $description = $result->description;
                $long_description = apply_filters('the_content', $result->long_description); // Apply the_content filter to display the WYSIWYG content properly
                $image_url = $result->image;
            }

            // Return the description and long_description in JSON format
            wp_send_json([
                'title' => $title,
                'description' => $description,
                'long_description' => $long_description,
                'image_url' => $image_url
            ]);
        }

        // Return an error response if the title is not provided or the query fails
        wp_send_json_error('Title not provided or description not found.');
    }

    public function save_top_personal_values()
    {
        if ($this->isUserLoggedIn() && isset($_POST['user_id'], $_POST['titles'])) {
            global $wpdb;
            $table_name = $wpdb->prefix . 'kyosei_personal_values_user';

            $user_id = absint($_POST['user_id']);
            $titles = $_POST['titles']; // Array of selected card titles

            // Serialize the array of titles
            $serialized_titles = serialize($titles);

            // Get the current timestamp
            $current_timestamp = current_time('mysql');

            // Check if a row already exists for the user
            $existing_row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $table_name WHERE user_id = %d",
                    $user_id
                )
            );

            $wpdb->insert(
                $table_name,
                array(
                    'user_id' => $user_id,
                    'title' => $serialized_titles,
                    'created_at' => $current_timestamp
                ),
                array('%d', '%s', '%s')
            );

            // Send a success response
            wp_send_json_success('Top personal value card titles saved successfully.');
        }

        // Send an error response if the required data is not provided
        wp_send_json_error('Error saving top personal value card titles.');
    }

    public function isUserLoggedIn()
    {
        return is_user_logged_in();
    }

    public function display_personal_value_titles_shortcode()
    {
        if (is_user_logged_in()) {
            global $wpdb;
            $table_name_user = $wpdb->prefix . 'kyosei_personal_values_user';
            $table_name_values = $wpdb->prefix . 'kyosei_personal_values';
            $table_name_modality_tags = $wpdb->prefix . 'kyosei_personal_value_modality_tags';

            $user_id = get_current_user_id();

            // Get the rows from the database based on user and created_at
            $rows = $wpdb->get_results(
                $wpdb->prepare(
                    "SELECT * FROM $table_name_user WHERE user_id = %d ORDER BY created_at DESC",
                    $user_id
                )
            );

            if ($rows) {
                $output = '';

                // Create the dropdown select
                $output .= '<div><select id="dateSelect">';
                foreach ($rows as $row) {
                    $created_at_formatted = date('F j, Y g:i A', strtotime($row->created_at));
                    $output .= '<option value="' . $row->id . '">' . esc_html($created_at_formatted) . '</option>';
                }
                $output .= '</select></div><br>';

                // Create a container for displaying personal value cards
                $output .= '<div id="personalValueContainer"></div>';

                $output .= '<script>
					jQuery(document).ready(function($) {
						$("#dateSelect").change();
						$("#dateSelect").change(function() {
							var id = $(this).val();
							$.ajax({
								type: "POST",
								url: "' . admin_url('admin-ajax.php') . '",
								data: {
									action: "get_personal_values_by_date",
									id: id
								},
								success: function(response) {
									$("#personalValueContainer").html(response);
								}
							});
						});
					});
				</script>';

                return $output;
            } else {
                return 'No personal value card found.';
            }
        } else {
            return 'Please log in to see your personal value cards result.';
        }
    }

    public function get_personal_values_by_date()
    {
        if (isset($_POST['id'])) {
            global $wpdb;
            $id = sanitize_text_field($_POST['id']);
            // Convert the selected date to timestamp
            $selected_timestamp = strtotime($selected_date);

            $table_name_user = $wpdb->prefix . 'kyosei_personal_values_user';
            $table_name_values = $wpdb->prefix . 'kyosei_personal_values';
            $table_name_modality_tags = $wpdb->prefix . 'kyosei_personal_value_modality_tags';

            $user_id = get_current_user_id();

            // Calculate the start and end timestamps for the selected day
            $start_timestamp = strtotime(date('Y-m-d 00:00:00', $selected_timestamp));
            $end_timestamp = strtotime(date('Y-m-d 23:59:59', $selected_timestamp));

            $row = $wpdb->get_row(
                $wpdb->prepare(
                    "SELECT * FROM $table_name_user WHERE user_id = %d AND id = %d",
                    $user_id,
                    $id,
                )
            );

            if ($row) {
                // Define the $created_at_formatted variable
                $created_at_formatted = date('F j, Y g:i A', $selected_timestamp);

                $serialized_titles = $row->title;
                // Unserialize the titles
                $titles = unserialize($serialized_titles);

                $output = '';

                //$output .= '<div class="top-titles"><h2>' . esc_html($created_at_formatted) . '</h2>';
                $output .= '<div class="top-titles">';
                $card = 0;
                foreach ($titles as $title) {
                    // Fetch additional data based on title
                    $personal_value_data = $wpdb->get_row(
                        $wpdb->prepare("SELECT * FROM $table_name_values WHERE title = %s", $title)
                    );

                    if ($personal_value_data) {
                        // Fetch modality tag names based on tag IDs
                        $modality_tag_ids = explode(',', $personal_value_data->modality_tag_ids);
                        $modality_tag_names = array();
                        foreach ($modality_tag_ids as $tag_id) {
                            $tag_name = $wpdb->get_var(
                                $wpdb->prepare("SELECT name FROM $table_name_modality_tags WHERE id = %d", $tag_id)
                            );
                            if ($tag_name) {
                                $modality_tag_names[] = esc_html($tag_name);
                            }
                        }

                        // Build the output for each personal value
                        $output .= '<div class="pvc-card-' . $card . ' personal-value">';
                        $output .= '<div class="pvc-content-container"><h2>' . esc_html($title) . '</h2>';

                        if (!empty($personal_value_data->long_description)) {
                            $output .= '<p>' . wp_kses_post(stripslashes($personal_value_data->long_description)) . '</p>';
                        } else {
                            $output .= '<h3>What is Lorem Ipsum?</h3>' .
                                '<strong>Lorem Ipsum</strong> is simply dummy text of the printing and typesetting industry. ' .
                                '<p>Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer ' .
                                'took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, ' .
                                'but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the ' .
                                '1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop ' .
                                'publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>';
                        }

                        // $output .= '<p>' . wp_kses_post(stripslashes($personal_value_data->long_description)) . '</p>';

                        $output .= '<p>Modality Tag Names: ' . implode(', ', $modality_tag_names) . '</p></div>';
                        $output .= '<div class="background-image-container"><img src="' . esc_url($personal_value_data->image) . '" alt="' . esc_attr($title) . '"></div>';
                        $output .= '</div>';
                    }
                    $card++;
                }
                $output .= '</div>';
                echo $output;
            } else {
                echo 'No personal value card titles found for the selected date.';
            }
        }

        wp_die();
    }

    // Callback function for 'get_modality_names'
    public function get_modality_names_callback()
    {
        global $wpdb;

        $table_name_modality_tags = $wpdb->prefix . 'kyosei_personal_value_modality_tags';
        $modality_names = $wpdb->get_results("SELECT id, name FROM $table_name_modality_tags");

        // Send the modality names as a JSON response
        wp_send_json($modality_names);

        wp_die();
    }

    public function get_long_description()
    {
        global $wpdb;

        // Get the card title from the AJAX request
        $card_title = sanitize_text_field($_POST['title']);

        // Prepare the table name
        $table_name = $wpdb->prefix . 'kyosei_personal_values';

        // Query the database to get the long_description based on the card title
        $query = $wpdb->prepare("SELECT long_description FROM $table_name WHERE title = %s", $card_title);
        $long_description = $wpdb->get_var($query);

        // Prepare the response data
        $response = array(
            'long_description' => $long_description,
        );

        // Send the response as JSON
        wp_send_json($response);
    }
}
