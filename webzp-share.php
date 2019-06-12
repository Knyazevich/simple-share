<?php
/**
 * Plugin Name: Simple share
 * Plugin URI: https://github.com/Knyazevich/simple-share
 * Description: Simple share current page plugin
 * Text Domain: webzp_share
 * Domain Path: /languages
 * Author URI:  https://github.com/Knyazevich
 * Author:      Pavlo Knyazevich
 * Version:     1.0.0
 *
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined( 'WEBZP_SHARE_VER' ) ) {
  define( 'WEBZP_SHARE_VER', '1.0.0' );
}

class Webzp_Share {

  /**
   * Static property to hold our singleton instance
   *
   */
  static $instance = false;

  /**
   * This is our constructor
   *
   * @return void
   */
  private function __construct() {
    add_action( 'plugins_loaded', array( $this, 'textdomain' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'front_scripts' ), 10 );
    add_action( 'wp_footer', array( $this, 'render_share_buttons' ) );
  }

  /**
   * If an instance exists, this returns it.  If not, it creates one and
   * retuns it.
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
    load_plugin_textdomain( 'webzp_share', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  }

  /**
   * Call front-end CSS and JS
   *
   * @return void
   */

  public function front_scripts() {

    wp_enqueue_style( 'webzp_share_styles', plugins_url( 'assets/css/style.css', __FILE__ ), array(), WEBZP_SHARE_VER, 'all' );
    wp_enqueue_script( 'webzp_share_common', plugins_url( 'assets/js/common.js', __FILE__ ), array(), WEBZP_SHARE_VER, true );

  }

  /**
   * Render HTML links
   *
   * @return void
   */

  public function render_share_buttons() {
    global $wp;
    $url = home_url( add_query_arg( array(), $wp->request ) );
    $title = get_the_title();
  ?>

  <nav class="webzp-share-block">
    <ul class="webzp-share-block__list">

      <li class="webzp-share-block__list-item">
        <a href="http://www.facebook.com/sharer.php?u=<?php echo $url; ?>/?_wsp_t=fb"
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
  </nav>
  
  <?php
  }

}

// Instantiate our class
$Webzp_Share = Webzp_Share::getInstance();