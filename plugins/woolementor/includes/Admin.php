<?php
/**
 * All admin facing functions
 */

namespace codexpert\Woolementor;

/**
 * if accessed directly, exit.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @package Plugin
 * @subpackage Admin
 * @author Nazmul Ahsan <n.mukto@gmail.com>
 */
class Admin extends Hooks {

	/**
	 * Constructor function
	 *
	 * @since 1.0
	 */
	public function __construct( $plugin ) {
		$this->slug = $plugin['TextDomain'];
		$this->name = $plugin['Name'];
		$this->version = $plugin['Version'];
		$this->assets = WOOLEMENTOR_ASSETS;
	}

	/**
	 * Installer. Runs once when the plugin in activated.
	 *
	 * @since 1.0
	 */
	public function install() {
		/**
		 * Schedule an event to sync help docs
		 */
		if ( !wp_next_scheduled ( 'woolementor_daily' )) {
		    wp_schedule_event( time(), 'daily', 'woolementor_daily' );
		}


		/**
		 * Sync docs & others
		 */
		self::daily();
	}

	/**
	 * Uninstaller. Runs once when the plugin in deactivated.
	 *
	 * @since 1.0
	 */
	public function uninstall() {
		/**
		 * Remove scheduled hooks
		 */
		wp_clear_scheduled_hook( 'woolementor_daily' );
	}

	public function settigs_page_redirect(){
    	if( get_option( 'woolementor-activated' ) != 1 ) {
			update_option( 'woolementor-activated', 1 );
    		wp_safe_redirect( admin_url( 'admin.php?page=woolementor' ) );
			exit();
    	}
	}

	/**
	 * Setup the instance
	 *
	 * @since 1.0
	 */
	public function setup() {
		add_image_size( 'woolementor-thumb', 400, 400, true );
	}
	
	/**
	 * Enqueue JavaScripts and stylesheets
	 *
	 * @since 1.0
	 */
	public function enqueue_scripts( $screen ) {
		// Are we in debug mode?
		$min = defined( 'WOOLEMENTOR_DEBUG' ) && WOOLEMENTOR_DEBUG ? '' : '.min';
		
		// vendors
		if( $screen == 'toplevel_page_woolementor' ) {
			wp_enqueue_style( 'gogole-fonts', 'https://fonts.googleapis.com/css?family=Nunito&display=swap', '', $this->version, 'all' );

			// enqueue stylesheet
			wp_enqueue_style( "{$this->slug}-pro-features", "{$this->assets}/css/pro-features{$min}.css", '', $this->version, 'all' );
			wp_enqueue_style( "{$this->slug}-widgets-settings", "{$this->assets}/css/widgets-settings{$min}.css", '', $this->version, 'all' );

			// enqueue JavaScript
			wp_enqueue_script( "{$this->slug}-widgets-settings", "{$this->assets}/js/widgets-settings{$min}.js", array( 'jquery' ), $this->version, true );
			wp_enqueue_script( $this->slug, "{$this->assets}/js/admin{$min}.js", array( 'jquery' ), $this->version, true );
		}

		wp_enqueue_style( $this->slug, "{$this->assets}/css/admin{$min}.css", '', $this->version, 'all' );
	}

	/**
	 * Add some script to head
	 *
	 * @since 1.0
	 */
	public function head() {}

	/**
	 * Adds a widget in /wp-admin/ page
	 *
	 * @since 1.0
	 */
	public function dashboard_widget() {
		wp_add_dashboard_widget( 'wl-overview', __( 'Woolementor Docs & FAQs', 'woolementor' ), [ $this, 'callback_dashboard_widget' ] );

		// Move our widget to top.
		global $wp_meta_boxes;

		$dashboard = $wp_meta_boxes['dashboard']['normal']['core'];
		$ours = [
			'wl-overview' => $dashboard['wl-overview'],
		];

		$wp_meta_boxes['dashboard']['normal']['core'] = array_merge( $ours, $dashboard );
	}

	/**
	 * Call back for dashboard widget in /wp-admin/
	 *
	 * @see dashboard_widget()
	 *
	 * @since 1.0
	 */
	public function callback_dashboard_widget() {
		$docs = get_option( 'woolementor-docs-json', [] );
		
		if( count( $docs ) > 0 ) :
		
		$docs = array_slice( $docs, 0, 5 );

		echo '<ul id="wl-docs-wrapper">';
		
		foreach ( $docs as $doc ) {
			echo "
			<li>
				<a href='{$doc['link']}' target='_blank'><span aria-hidden='true' class='wl-doc-title-icon dashicons dashicons-external'></span> <span class='wl-doc-title'>{$doc['title']['rendered']}</span></a>
				" . wpautop( wp_trim_words( $doc['content']['rendered'], 10 ) ) . "
			</li>";
		}
		
		echo '</ul>';
		endif; // count( $docs ) > 0

		$_links = apply_filters( 'woolementor-widget_links', [
			'help'	=> [
				'url'		=> 'https://help.codexpert.io/docs/woolementor/',
				'label'		=> __( 'Help', 'woolementor' ),
				'target'	=> '_blank',
			],
			'gopro'	=> [
				'url'		=> 'https://codexpert.io/product/woolementor',
				'label'		=> __( 'Go Pro', 'woolementor' ),
				'target'	=> '_blank',
			],
			'settings'	=> [
				'url'		=> admin_url( 'admin.php?page=woolementor' ),
				'label'		=> __( 'Settings', 'woolementor' ),
				'target'	=> '',
			],
		] );

		$footer_links = [];
		foreach ( $_links as $id => $link ) {
			$_has_icon = ( $link['target'] == '_blank' ) ? '<span class="screen-reader-text">' . __( '(opens in a new tab)', 'woolementor' ) . '</span><span aria-hidden="true" class="dashicons dashicons-external"></span>' : '';

			$footer_links[] = "<a href='{$link['url']}' target='{$link['target']}'>{$link['label']}{$_has_icon}</a>";
		}

		echo '<p class="community-events-footer">' . implode( ' | ', $footer_links ) . '</p>';
	}

	/**
	 * Daily events
	 */
	public static function daily() {
		/**
		 * Sync docs from https://help.codexpert.io daily
		 *
		 * @since 1.0
		 */
	    $json_url = 'https://help.codexpert.io/wp-json/wp/v2/docs/?parent=1960&per_page=20';
	    if( !is_wp_error( $data = wp_remote_get( $json_url ) ) ) {
	        update_option( 'woolementor-docs-json', json_decode( $data['body'], true ) );
	    }

	    // checks pro
	    woolementor_check_pro();
	}

	public function action_links( $links ) {
		$links['wl-settings'] = '<a href="' . add_query_arg( 'page', $this->slug, admin_url( 'admin.php' ) ) . '">' . __( 'Settings', 'woolementor' ) . '</a>';
		$links['wl-help'] = '<a href="https://wordpress.org/support/plugin/woolementor/" target="_blank" class="wl-plugins-help">' . __( 'Help', 'woolementor' ) . '</a>';

		if( !woolementor_is_pro() ) {
			$links['wl-go_pro'] = '<a href="https://woolementor.com" target="_blank" class="wl-plugins-gopro">' . __( 'Go Pro', 'woolementor' ) . '</a>';
		}

		return $links;
	}
}