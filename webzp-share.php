<?php
/**
 * Plugin Name: Simple share
 * Plugin URI: https://github.com/Knyazevich/simple-share
 * Description: Simple share current page plugin
 * Text Domain: webzp_share
 * Domain Path: /languages
 * Author URI:  https://github.com/Knyazevich
 * Author:      Pavlo Knyazevich
 * Version:     1.0.5
 *
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'WEBZP_SHARE_VER' ) ) {
  define( 'WEBZP_SHARE_VER', '1.0.5' );
}

/**
 * Class Webzp_Share
 */
class Webzp_Share {

  /**
   * Static property to hold our singleton instance
   */
  static $instance = false;

  /**
   * This is our constructor
   *
   * @return void
   */
  private function __construct() {
    $is_enabled = get_option( 'webzp_share_settings' )['enabled'];

    if ( $is_enabled ) {
      add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 10 );
      add_action( 'wp_footer', array( $this, 'render_share_buttons' ) );
      add_action( 'wp_ajax_nopriv_webzp_share_shared', array( $this, 'ajax_share_handler' ) );
      add_action( 'wp_ajax_webzp_share_shared', array( $this, 'ajax_share_handler' ) );
    }

    add_action( 'plugins_loaded', array( $this, 'textdomain' ) );
    add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
    add_action( 'admin_init', array( $this, 'settings_init' ) );

    register_activation_hook( __FILE__, array( $this, 'add_basic_options' ) );
  }

  /**
   * If an instance exists, this returns it. If not, it creates one and
   * returns it.
   *
   * @return Webzp_Share
   */
  public static function getInstance() {

    if ( ! self::$instance ) {
      self::$instance = new self;
    }

    return self::$instance;

  }

  /**
   * Load textdomain
   *
   * @return void
   */
  public function textdomain() {
    load_plugin_textdomain(
      'webzp_share',
      false,
      dirname( plugin_basename( __FILE__ ) ) . '/languages/'
    );
  }

  /**
   * Call front-end CSS and JS
   *
   * @return void
   */

  public function front_scripts() {

    wp_enqueue_style(
      'webzp_share_styles',
      plugins_url( 'assets/css/style.css', __FILE__ ),
      array(),
      WEBZP_SHARE_VER,
      'all'
    );

    wp_enqueue_script(
      'webzp_share_common',
      plugins_url( 'assets/js/common.js', __FILE__ ),
      array(),
      WEBZP_SHARE_VER,
      true
    );

  }

  /**
   * Render HTML links
   *
   * @return void
   */
  public function render_share_buttons() {
    global $wp;
    $url = esc_url( home_url( add_query_arg( array(), $wp->request ) ) );
    $title = esc_html( get_the_title() );

    $options = get_option( 'webzp_share_settings' );
    $is_horizontal = $options['horizontal'];
    $enable_counter = $options['enable_counter'];
    $shares_count = esc_html( $options['shares_count'] );

    $shares_tooltip_text_color = esc_html( $options['shares_text_color'] );
    $shares_tooltip_bg_color = esc_html( $options['shares_bg_color'] );
  ?>

  <nav class="webzp-share-block <?php echo $is_horizontal ? 'webzp-share-block--mobile-horizontal ' : ''; ?>">
    <ul class="webzp-share-block__list">

      <li class="webzp-share-block__list-item">
        <a href="https://www.facebook.com/sharer.php?u=<?php echo $url; ?>/?_wsp_t=fb"
           target="_blank"
           data-popup="true"
           title="<?php _e( 'Share on Facebook', 'webzp_share' ); ?>"
           aria-label="<?php _e( 'Share on Facebook', 'webzp_share' ); ?>"
           class="webzp-share-block__link webzp-share-block__link--fb">
        </a>
      </li>

      <li class="webzp-share-block__list-item">
        <a href="https://twitter.com/share?url=<?php echo $url; ?>/?_wsp_t=twi"
           target="_blank"
           data-popup="true"
           title="<?php _e( 'Share on Twitter', 'webzp_share' ); ?>"
           aria-label="<?php _e( 'Share on Twitter', 'webzp_share' ); ?>"
           class="webzp-share-block__link webzp-share-block__link--twi">
        </a>
      </li>

      <li class="webzp-share-block__list-item">
        <a href="https://web.whatsapp.com/send?text=<?php echo $url; ?>/?_wsp_t=wa <?php echo $title; ?>"
           target="_blank"
           data-popup="true"
           title="<?php _e( 'Share on WhatsApp', 'webzp_share' ); ?>"
           aria-label="<?php _e( 'Share on WhatsApp', 'webzp_share' ); ?>"
           class="webzp-share-block__link webzp-share-block__link--wa">     
        </a>
      </li>

      <li class="webzp-share-block__list-item">
        <a href="https://telegram.me/share/url?url=<?php echo $url; ?>/?_wsp_t=tg&amp;text=<?php echo $title; ?>"
           target="_blank"
           data-popup="true"
           title="<?php _e( 'Share on Telegram', 'webzp_share' ); ?>"
           aria-label="<?php _e( 'Share on Telegram', 'webzp_share' ); ?>"
           class="webzp-share-block__link webzp-share-block__link--tg">     
        </a>
      </li>

      <li class="webzp-share-block__list-item">
        <a href="viber://forward?text=<?php echo $url; ?>/?_wsp_t=vb : <?php echo $title; ?>"
           target="_blank"
           data-popup="true"
           title="<?php _e( 'Share on Viber', 'webzp_share' ); ?>"
           aria-label="<?php _e( 'Share on Viber', 'webzp_share' ); ?>"
           class="webzp-share-block__link webzp-share-block__link--viber"></a>
      </li>

      <li class="webzp-share-block__list-item">
        <a href="mailto:?Subject=<?php echo $title; ?>&amp;Body=<?php echo $url; ?>/?_wsp_t=email"
           title="<?php _e( 'Send link in an Email', 'webzp_share' ); ?>"
           aria-label="<?php _e( 'Send link in an Email', 'webzp_share' ); ?>"
           class="webzp-share-block__link webzp-share-block__link--mail">     
        </a>
      </li>

    </ul>

    <?php if ( $enable_counter ) { ?>

    <div class="webzp-share-shares-tooltip">
        <span class="webzp-share-shares-tooltip-content"><?php echo $shares_count; ?></span>
    </div>

    <style>
      .webzp-share-shares-tooltip-content {
        color: <?php echo $shares_tooltip_text_color; ?> !important;
        background-color: <?php echo $shares_tooltip_bg_color; ?> !important;
      }

      .webzp-share-shares-tooltip-content::after {
        border-color: transparent transparent <?php echo $shares_tooltip_bg_color; ?> transparent !important;
      }
    </style>

    <?php } ?>
  </nav>

  <?php
  }

  /**
   * Add separate settings page
   *
   * @return void
   */

  public function add_admin_menu() { 
    add_menu_page( 'Simple share', 'Simple share', 'manage_options', 'simple_share', array( $this, 'options_page' ) );
  }

  /**
   * Register settings section and checkboxes
   *
   * @return void
   */

  public function settings_init() {
    register_setting( 'pluginPage', 'webzp_share_settings' );

    add_settings_section(
      'webzp_share_pluginPage_section', 
      __( 'Main settings', 'webzp_share' ), 
      '',
      'pluginPage'
    );

    add_settings_field( 
      'webzp_share_checkbox_field_0', 
      __( 'Show social buttons', 'webzp_share' ),
      array( $this, 'checkbox_enable_plugin_render' ),
      'pluginPage', 
      'webzp_share_pluginPage_section' 
    );

    add_settings_field( 
      'webzp_share_checkbox_field_1', 
      __( 'Show horizontal block on mobile', 'webzp_share' ),
      array( $this, 'checkbox_mobile_horizontal_render' ),
      'pluginPage', 
      'webzp_share_pluginPage_section' 
    );

    add_settings_field(
      'webzp_share_shares_counter',
      __( 'Show shares counter', 'webzp_share' ),
      array( $this, 'checkbox_shares_counter_render' ),
      'pluginPage',
      'webzp_share_pluginPage_section'
    );

    add_settings_field(
      'webzp_share_shares_counter_text_color',
      __( 'Shares counter text color', 'webzp_share' ),
      array( $this, 'checkbox_shares_counter_text_color_render' ),
      'pluginPage',
      'webzp_share_pluginPage_section'
    );

    add_settings_field(
      'webzp_share_shares_counter_bg_color',
      __( 'Shares counter background color', 'webzp_share' ),
      array( $this, 'checkbox_shares_counter_bg_color_render' ),
      'pluginPage',
      'webzp_share_pluginPage_section'
    );

    add_settings_field(
      'webzp_share_shares_quantity',
      __( 'Edit shares counter', 'webzp_share' ),
      array( $this, 'text_shares_quantity_render' ),
      'pluginPage',
      'webzp_share_pluginPage_section'
    );
  }

  /**
   * Setup enabling / disabling checkbox
   *
   * @return void
   */

  public function checkbox_enable_plugin_render() {
    $options = get_option( 'webzp_share_settings' );
    ?>

    <input type="checkbox"
           name="webzp_share_settings[enabled]"
           <?php checked( $options['enabled'] ); ?>
           value="1">

    <?php
  }

  /**
   * Setup "enable horizontal mode" checkbox
   *
   * @return void
   */
  public function checkbox_mobile_horizontal_render() {
    $options = get_option( 'webzp_share_settings' );
    ?>

    <input type="checkbox"
           name="webzp_share_settings[horizontal]"
           <?php checked( $options['horizontal'] ); ?>
           value="1">

    <?php
  }

  /**
   * Setup "enable shares counter" checkbox
   *
   * @return void
   */
  public function checkbox_shares_counter_render() {
    $options = get_option( 'webzp_share_settings' );
    ?>

    <input type="checkbox"
           name="webzp_share_settings[enable_counter]"
           <?php checked( $options['enable_counter'] ); ?>
           value="1">

    <?php
  }

  /**
   * Setup shares quantity text input
   *
   * @return void
   */
  public function text_shares_quantity_render() {
    $options = get_option( 'webzp_share_settings' );
    ?>

    <input type="text"
           name="webzp_share_settings[shares_count]"
           value="<?php echo esc_html( $options['shares_count'] ); ?>">

    <?php
  }

  /**
   * Setup shares tooltip text color
   *
   * @return void
   */
  public function checkbox_shares_counter_text_color_render() {
    $options = get_option( 'webzp_share_settings' );
    ?>

    <input type="color"
           name="webzp_share_settings[shares_text_color]"
           value="<?php echo esc_html( $options['shares_text_color'] ); ?>">

    <?php
  }

  /**
   * Setup shares tooltip background color
   *
   * @return void
   */
  public function checkbox_shares_counter_bg_color_render() {
    $options = get_option( 'webzp_share_settings' );
    ?>

    <input type="color"
           name="webzp_share_settings[shares_bg_color]"
           value="<?php echo esc_html( $options['shares_bg_color'] ); ?>">

    <?php
  }

  /**
   * Setup full settings form
   *
   * @return void
   */
  public function options_page() {
    ?>

    <form action="options.php" method="POST">

      <?php
      settings_fields( 'pluginPage' );
      do_settings_sections( 'pluginPage' );
      submit_button();
      ?>

    </form>

    <?php
  }

  /**
   * Setup basic settings options on first init
   *
   * @return void
   */
  public function add_basic_options() {

    $options = get_option( 'webzp_share_settings' );

    if ( ! $options ) {
        add_option( 'webzp_share_settings', array(
          'enabled' => 1,
          'horizontal' => 1,
          'enable_counter' => 1,
          'shares_count' => 0,
          'shares_text_color' => '#fff',
          'shares_bg_color' => '#000',
        ) );
    }

  }

  /**
   * AJAX handler on page share
   *
   * @return void
   */
  public function ajax_share_handler() {

    $options = get_option( 'webzp_share_settings' );
    $options['shares_count'] += 1;

    $upd_meta = update_site_option( 'webzp_share_settings', $options );

    if ( $upd_meta ) {
      wp_send_json_success( __( 'Counter successfully increased', 'webzp_share' ) );
    } else {
      wp_send_json_error( __( 'Increasing error', 'webzp_share' ) );
    }

    die();

  }

}

// Instantiate our class
$Webzp_Share = Webzp_Share::getInstance();