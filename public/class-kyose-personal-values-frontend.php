<?php

/**
 * The frontend functionality of the plugin.
 *
 * @package    Kyosei_Personal_Values
 * @subpackage Kyosei_Personal_Values/public
 * @author     MJ of Kyosei <mjsuarez@kyoseicreative.com>
 * @since      1.0.0
 */

class Kyose_Personal_Values_Frontend
{
    private $plugin_name;
    private $version;

    public function __construct($plugin_name = '', $version = '')
    {
        $this->plugin_name = $plugin_name;
        $this->version     = $version;
    }

    /**
     * Registers the [personal_value_cards] shortcode.
     *
     * @since 1.0.0
     */
    public function register_shortcodes()
    {
        add_shortcode('personal_value_cards', [$this, 'personal_value_cards_shortcode']);
    }

    /**
     * Shortcode callback to display personal value cards.
     *
     * @since 1.0.0
     * @param array $atts Shortcode attributes.
     * @return string HTML output.
     */
    public function personal_value_cards_shortcode($atts)
    {

        ob_start();

        global $wpdb;
        $table_name = $wpdb->prefix . 'kyosei_personal_values';
        $cards      = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);

        include plugin_dir_path(__FILE__) . 'partials/kyose-personal-values-public-display.php';

        return ob_get_clean();
    }
}

// Initialize and register the shortcode
$frontend = new Kyose_Personal_Values_Frontend();
$frontend->register_shortcodes();
