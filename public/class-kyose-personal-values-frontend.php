<?php

/**
 * The frontend functionality of the plugin.
 *
 * @see       https://https://kyoseicreative.com/
 * @since     1.0.0
 */

/**
 * The frontend functionality of the plugin.
 *
 * Defines the shortcode for displaying the personal value card list on the frontend.
 *
 * @author    MJ of Kyosei <mjsuarez@kyoseicreative.com>
 */
class Kyose_Personal_Values_Frontend
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
    /**
     * Register the shortcode for displaying the personal value card list.
     *
     * @since    1.0.0
     */
    public function register_shortcodes()
    {
        add_shortcode('personal_value_cards', [$this, 'personal_value_cards_shortcode']);
    }

    /**
     * Shortcode callback function for displaying the personal value card list.
     *
     * @param array $atts Shortcode attributes.
     * @return string HTML markup for the personal value card list.
     */
    public function personal_value_cards_shortcode($atts)
    {
        ob_start();

        // Retrieve the personal value cards
        global $wpdb;
        $table_name = $wpdb->prefix . 'kyosei_personal_values';
        $cards = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        // Display the personal value card list
        include_once plugin_dir_path(__FILE__) . 'personal_values_list_frontend.php';

        return ob_get_clean();
    }

}

$frontend = new Kyose_Personal_Values_Frontend();
$frontend->register_shortcodes();
