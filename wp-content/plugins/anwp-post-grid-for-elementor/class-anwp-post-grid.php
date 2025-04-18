<?php
/**
 * AnWP_Post_Grid :: Main Class
 *
 * @since   0.1.0
 * @package AnWP_Post_Grid
 */

/**
 * Autoload files with classes when needed.
 *
 * @param string $class_name Name of the class being requested.
 *
 * @since  0.1.0
 */
function anwp_post_grid_autoload_classes( $class_name ) {

	// If our class doesn't have our prefix, don't load it.
	if ( 0 !== strpos( $class_name, 'AnWP_Post_Grid_' ) ) {
		return;
	}

	// Set up our filename.
	$filename = strtolower( str_replace( '_', '-', substr( $class_name, strlen( 'AnWP_Post_Grid_' ) ) ) );

	// Include our file.
	AnWP_Post_Grid::include_file( 'includes/class-anwp-post-grid-' . $filename );
}

spl_autoload_register( 'anwp_post_grid_autoload_classes' );

/**
 * Main initiation class.
 *
 * @property-read AnWP_Post_Grid_Elements $elements
 * @property-read AnWP_Post_Grid_Settings $settings
 * @property-read AnWP_Post_Grid_Template $template
 * @property-read string                  $path     Path of plugin directory
 *
 * @since  0.1.0
 */
final class AnWP_Post_Grid {

	/**
	 * Current version.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	const VERSION = '1.3.3';

	/**
	 * URL of plugin directory.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $url = '';

	/**
	 * Path of plugin directory.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $path = '';

	/**
	 * Plugin basename.
	 *
	 * @var    string
	 * @since  0.1.0
	 */
	protected $basename = '';

	/**
	 * Singleton instance of plugin.
	 *
	 * @var    AnWP_Post_Grid
	 * @since  0.1.0
	 */
	protected static $single_instance = null;

	/**
	 * Instance of AnWP_Post_Grid_Template
	 *
	 * @since 0.1.0
	 * @var AnWP_Post_Grid_Template
	 */
	protected $template;

	/**
	 * Instance of AnWP_Post_Grid_Settings
	 *
	 * @since 0.7.1
	 * @var AnWP_Post_Grid_Settings
	 */
	protected $settings;

	/**
	 * Instance of AnWP_Post_Grid_Elements
	 *
	 * @since 0.1.0
	 * @var AnWP_Post_Grid_Elements
	 */
	protected $elements;

	/**
	 * Creates or returns an instance of this class.
	 *
	 * @since   0.1.0
	 * @return  AnWP_Post_Grid A single instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$single_instance ) {
			self::$single_instance = new self();
		}

		return self::$single_instance;
	}

	/**
	 * Sets up our plugin.
	 *
	 * @since  0.1.0
	 */
	protected function __construct() {
		$this->basename = plugin_basename( self::dir( 'anwp-post-grid.php' ) );
		$this->url      = plugin_dir_url( __FILE__ );
		$this->path     = plugin_dir_path( __FILE__ );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 0.8.7
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification
		}

		$message = sprintf(
			'"%1$s" requires "%2$s" version %3$s or greater.',
			'<strong>AnWP Post Grid</strong>',
			'<strong>Elementor</strong>',
			'3.1.0'
		);

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Register AnWP ID Selector
	 *
	 * Include control file and register control class.
	 *
	 * @since 0.8.7
	 * @param Elementor\Controls_Manager $controls_manager Elementor controls manager.
	 * @return void
	 */
	public function register_an_wp_post_grid_control_id_selector( $controls_manager ) {

		require_once __DIR__ . '/includes/class-anwp-post-grid-control-id-selector.php';

		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			$controls_manager->register( new AnWP_Post_Grid_Control_Id_Selector() );
		}
	}

	/**
	 * Add hooks and filters.
	 * Priority needs to be
	 * < 10 for CPT_Core,
	 * < 5 for Taxonomy_Core,
	 * and 0 for Widgets because widgets_init runs at init priority 1.
	 *
	 * @since  0.1.0
	 */
	public function hooks() {

		// Check for required Elementor version
		if ( defined( 'ELEMENTOR_VERSION' ) && ! version_compare( ELEMENTOR_VERSION, '3.1.0', '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

			return false;
		}

		if ( ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) ) {
			add_action( 'elementor/controls/register', [ $this, 'register_an_wp_post_grid_control_id_selector' ] );
		} else {
			add_action( 'elementor/controls/controls_registered', [ $this, 'register_an_wp_post_grid_control_id_selector' ] );
		}

		/**
		 * Bump init actions
		 *
		 * @since 0.1.0
		 */
		add_action( 'init', [ $this, 'init' ], 0 );

		/**
		 * Add theme name to body classes
		 *
		 * @since 0.1.0
		 */
		add_filter( 'body_class', [ $this, 'add_body_classes' ] );

		/**
		 * Renders notice if Elementor not installed.
		 *
		 * @since 0.1.0
		 */
		add_action( 'admin_notices', [ $this, 'notice_elementor_not_installed' ] );

		/**
		 * Add Elementor category.
		 *
		 * @since 0.1.0
		 */
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_category' ] );

		/**
		 * Enqueue Styles
		 *
		 * @since 0.1.0
		 */
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ], 9 );

		add_action( 'elementor/frontend/after_enqueue_scripts', [ $this, 'public_enqueue_scripts' ] );

		/**
		 * Add svg icons to the public side
		 *
		 * @since 0.1.0
		 */
		add_action( 'wp_footer', [ $this, 'include_public_svg_icons' ], 99 );

		/**
		 * Add svg icons to the admin footer
		 *
		 * @since 0.8.0
		 */
		add_action( 'admin_footer', [ $this, 'include_public_svg_icons' ], 99 );

		/**
		 * Register menu pages.
		 *
		 * @since  0.7.0
		 */
		add_action( 'admin_menu', [ $this, 'register_menus' ], 5 );

		/**
		 * Add redirect to the premium page.
		 *
		 * @since 0.7.0
		 */
		add_action( 'admin_init', [ $this, 'premium_page_redirect' ] );

		/**
		 * Add redirect after activation
		 *
		 * @since 0.7.0
		 */
		add_action( 'admin_init', [ $this, 'activation_redirect' ] );

		// Add plugin meta links.
		add_filter( 'plugin_row_meta', [ $this, 'add_plugin_meta_links' ], 10, 2 );
	}

	/**
	 * Add plugin meta links.
	 *
	 * @param array  $links       An array of the plugin's metadata,
	 *                            including the version, author,
	 *                            author URI, and plugin URI.
	 * @param string $file        Path to the plugin file, relative to the plugins directory.
	 *
	 * @since 0.7.0
	 * @return array
	 */
	public function add_plugin_meta_links( $links, $file ) {

		if ( false !== strpos( $file, 'anwp-post-grid.php' ) ) {
			$new_links = [
				'changelog'       => '<a href="https://anwppro.userecho.com/knowledge-bases/52-anwp-post-grid-for-elementor-changelog/categories/131-basic-version/articles" target="_blank">' . esc_html__( 'Changelog', 'anwp-post-grid' ) . '</a>',
				'premium_version' => '<a href="https://anwp.pro/anwp-post-grid-for-elementor-premium/" target="_blank">' . esc_html__( 'Premium Version', 'anwp-post-grid' ) . '</a>',
			];

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	/**
	 * Init hooks
	 *
	 * @since  0.1.0
	 */
	public function init() {

		// Load translated strings for plugin.
		load_plugin_textdomain( 'anwp-post-grid', false, dirname( $this->basename ) . '/languages/' );

		// Include Gamajo_Template_Loader - http://github.com/GaryJones/Gamajo-Template-Loader
		require_once self::dir( 'vendor/class-gamajo-template-loader.php' );

		// Initialize plugin classes.
		$this->plugin_classes();
	}

	/**
	 * Attach other plugin classes to the base plugin class.
	 *
	 * @since  0.1.0
	 */
	public function plugin_classes() {

		// Settings
		$this->settings = new AnWP_Post_Grid_Settings( $this );

		// Others
		$this->template = new AnWP_Post_Grid_Template( $this );
		$this->elements = new AnWP_Post_Grid_Elements( $this );

	} // END OF PLUGIN CLASSES FUNCTION

	/**
	 * Add body classes.
	 *
	 * @param array $classes
	 *
	 * @return array
	 * @since 0.1.0
	 */
	public function add_body_classes( $classes ) {
		global $is_IE;

		// If it's IE, add a class.
		if ( $is_IE ) {
			$classes[] = 'ie';
		}

		$classes[] = 'theme--' . wp_get_theme()->get_template();

		return $classes;
	}

	/**
	 * Add Elementor categories.
	 *
	 * @param Elementor\Elements_Manager $elements_manager
	 *
	 * @since 0.1.0
	 */
	public function add_elementor_category( $elements_manager ) {

		$elements_manager->add_category(
			'anwp-pg',
			[
				'title' => __( 'AnWP Post Grid', 'anwp-post-grid' ),
				'icon'  => 'fa fa-plug',
			]
		);
	}

	/**
	 * Add SVG definitions to the public footer.
	 *
	 * @since 0.1.0
	 */
	public function include_public_svg_icons() {

		// Define SVG sprite file.
		$svg_icons = self::dir( 'public/img/svg-icons.svg' );

		// If it exists, include it.
		if ( file_exists( $svg_icons ) ) {
			require_once $svg_icons;
		}
	}

	/**
	 * Load public scripts and styles
	 *
	 * @since 0.5.2
	 */
	public function public_enqueue_scripts() {
		/*
		|--------------------------------------------------------------------------
		| Plugin Scripts
		|--------------------------------------------------------------------------
		*/
		wp_enqueue_script( 'anwp-pg-scripts', self::url( 'public/js/plugin.min.js' ), [ 'jquery', 'elementor-frontend' ], self::VERSION, false );

		wp_localize_script(
			'anwp-pg-scripts',
			'anwpPostGridElementorData',
			[
				'ajax_url'       => admin_url( 'admin-ajax.php' ),
				'public_nonce'   => wp_create_nonce( 'anwp-pg-public-nonce' ),
				'premium_active' => self::is_premium_active() ? 'yes' : '',
				'loader'         => includes_url( 'js/tinymce/skins/lightgray/img/loader.gif' ),
			]
		);
	}

	/**
	 * Load admin scripts and styles
	 *
	 * @since 0.1.0
	 */
	public function enqueue_styles() {

		// Load styles
		if ( is_rtl() ) {
			wp_enqueue_style( 'anwp-pg-styles-rtl', self::url( 'public/css/styles-rtl.min.css' ), [], self::VERSION );
		} else {
			wp_enqueue_style( 'anwp-pg-styles', self::url( 'public/css/styles.min.css' ), [], self::VERSION );
		}
	}

	/**
	 * Magic getter for our object.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $field Field to get.
	 *
	 * @throws Exception     Throws an exception if the field is invalid.
	 * @return mixed         Value of the field.
	 */
	public function __get( $field ) {
		switch ( $field ) {
			case 'version':
				return self::VERSION;
			case 'basename':
			case 'url':
			case 'path':
			case 'template':
			case 'elements':
			case 'settings':
				return $this->$field;
			default:
				throw new Exception( 'Invalid ' . __CLASS__ . ' property: ' . $field );
		}
	}

	/**
	 * Include a file from the includes directory.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $filename Name of the file to be included.
	 * @return boolean          Result of include call.
	 */
	public static function include_file( $filename ) {
		$file = self::dir( $filename . '.php' );
		if ( file_exists( $file ) ) {
			return include_once $file;
		}

		return false;
	}

	/**
	 * This plugin's directory.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $path (optional) appended path.
	 *
	 * @return string       Directory and path.
	 */
	public static function dir( $path = '' ) {
		static $dir;
		$dir = $dir ? $dir : trailingslashit( dirname( __FILE__ ) );

		return $dir . $path;
	}

	/**
	 * This plugin's url.
	 *
	 * @since  0.1.0
	 *
	 * @param  string $path (optional) appended path.
	 *
	 * @return string       URL and path.
	 */
	public static function url( $path = '' ) {
		static $url;
		$url = $url ? $url : trailingslashit( plugin_dir_url( __FILE__ ) );

		return $url . $path;
	}

	/**
	 * Load template partial.
	 * Proxy for template rendering class method.
	 *
	 * @param array|object $atts
	 * @param string       $slug
	 * @param string       $layout
	 *
	 * @since 0.6.1
	 * @return string
	 */
	public function load_partial( $atts, $slug, $layout = '' ) {

		$layout = empty( $layout ) ? '' : ( '-' . sanitize_key( $layout ) );
		return $this->template->set_template_data( $atts )->get_template_part( $slug, $layout );
	}

	/**
	 * Renders notice if Elementor not installed.
	 *
	 * @since 0.1.0
	 */
	public function notice_elementor_not_installed() {

		if ( ! did_action( 'elementor/loaded' ) && current_user_can( 'install_plugins' ) ) {

			// Check Elementor installed
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$all_plugins = get_plugins();

			$elementor_installed = isset( $all_plugins['elementor/elementor.php'] );
			?>
			<div class="notice notice-error">
				<img alt="plugin image" style="float: left; width: 75px; margin-right: 15px; margin-top: 10px;" src="<?php echo esc_url( self::url( 'public/img/anwp-post-grid.png' ) ); ?>">
				<p>
					<?php echo wp_kses_post( __( "<strong>AnWP Post Grid for Elementor</strong> doesn't work without <strong>Elementor Page Builder</strong> plugin.", 'anwp-post-grid' ) ); ?><br>
					<?php echo $elementor_installed ? esc_html__( 'Please activate Elementor to continue.', 'anwp-post-grid' ) : esc_html__( 'Please install Elementor to continue.', 'anwp-post-grid' ); ?>
				</p>

				<?php if ( $elementor_installed && current_user_can( 'activate_plugins' ) ) : ?>
					<a href="<?php echo esc_url( wp_nonce_url( 'plugins.php?action=activate&plugin=' . rawurlencode( 'elementor/elementor.php' ), 'activate-plugin_elementor/elementor.php' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Activate Elementor', 'anwp-post-grid' ); ?></a>
				<?php elseif ( current_user_can( 'install_plugins' ) ) : ?>
					<a href="<?php echo esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' ) ); ?>" class="button button-primary"><?php echo esc_html__( 'Install Elementor', 'anwp-post-grid' ); ?></a>
				<?php endif; ?>

				<p style="clear: both; margin-bottom: 5px;"></p>
			</div>
			<?php
		}
	}

	/**
	 * Converts a string to a bool.
	 * From WOO
	 *
	 * @param string $string String to convert.
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public static function string_to_bool( $string ) {
		return is_bool( $string ) ? $string : ( 1 === $string || 'yes' === $string || 'true' === $string || '1' === $string );
	}

	/**
	 * Function checks if Post Views Counter plugin is active
	 *
	 * @return bool
	 * @since 0.1.0
	 */
	public static function is_pvc_active() {
		static $is_active = null;

		if ( null === $is_active ) {
			$is_active = function_exists( 'pvc_get_post_views' );
		}

		return $is_active;
	}

	/**
	 * Register menu pages.
	 *
	 * @since 0.7.0
	 */
	public function register_menus() {

		add_menu_page(
			esc_html_x( 'AnWP Post Grid', 'admin page title', 'anwp-post-grid' ),
			esc_html_x( 'AnWP Post Grid', 'admin menu title', 'anwp-post-grid' ),
			'manage_options',
			'anwp-post-grid',
			[ $this, 'render_welcome_page' ],
			'dashicons-screenoptions',
			32
		);

		/*
		|--------------------------------------------------------------------------
		| Prepare submenu pages
		|--------------------------------------------------------------------------
		*/
		$submenu_pages = [
			'tutorials'        => [
				'parent_slug' => 'anwp-post-grid',
				'page_title'  => esc_html__( 'Welcome', 'anwp-post-grid' ),
				'menu_title'  => esc_html__( 'Welcome', 'anwp-post-grid' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwp-post-grid',
				'output_func' => '',
			],
			'support'          => [
				'parent_slug' => 'anwp-post-grid',
				'page_title'  => esc_html__( 'Support', 'anwp-post-grid' ),
				'menu_title'  => esc_html__( 'Support', 'anwp-post-grid' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwp-post-grid-support',
				'output_func' => [ $this, 'render_support_page' ],
			],
			'premium'          => [
				'parent_slug' => 'anwp-post-grid',
				'page_title'  => '',
				'menu_title'  => '<span style="color: #fd7e14">' . esc_html__( 'Go Premium', 'anwp-post-grid' ) . '</span>',
				'capability'  => 'manage_options',
				'menu_slug'   => 'redirect-anwp-pg-elementor-premium',
				'output_func' => [ $this, 'premium_page_redirect' ],
			],
			'anwp-pg-settings' => [
				'parent_slug' => 'anwp-post-grid',
				'page_title'  => esc_html__( 'Settings', 'anwp-post-grid' ),
				'menu_title'  => esc_html__( 'Settings', 'anwp-post-grid' ),
				'capability'  => 'manage_options',
				'menu_slug'   => 'anwp_pg_settings',
				'output_func' => [ $this, 'render_settings_page' ],
			],
		];

		/**
		 * Filter submenu pages.
		 *
		 * @since 0.7.0
		 *
		 * @param array Array of submenus
		 */
		$submenu_pages = apply_filters( 'anwp-pg-el/admin/submenu_pages', $submenu_pages );

		foreach ( $submenu_pages as $m ) {
			add_submenu_page( $m['parent_slug'], $m['page_title'], $m['menu_title'], $m['capability'], $m['menu_slug'], $m['output_func'] );
		}
	}

	/**
	 * Rendering Welcome page
	 *
	 * @since 0.1.0
	 */
	public function render_welcome_page() {

		// Must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-post-grid' ) );
		}

		self::include_file( 'admin/views/welcome' );
	}

	/**
	 * Rendering Tutorials page
	 *
	 * @since 0.7.0
	 */
	public function render_support_page() {

		// Must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-post-grid' ) );
		}

		self::include_file( 'admin/views/support' );
	}

	/**
	 * Rendering Settings page
	 *
	 * @since 0.7.1
	 */
	public function render_settings_page() {

		// Must check that the user has the required capability
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'anwp-post-grid' ) );
		}

		self::include_file( 'admin/views/settings' );
	}

	/**
	 * Return localized menu prefix.
	 *
	 * @return string
	 * @since 0.7.0
	 */
	public function get_l10n_menu_prefix() {
		return sanitize_title( _x( 'AnWP Post Grid', 'admin menu title', 'anwp-post-grid' ) );
	}

	/**
	 * Rendering Premium page
	 *
	 * @since 0.7.0
	 */
	public function premium_page_redirect() {

		// phpcs:ignore WordPress.Security.NonceVerification
		if ( isset( $_GET['page'] ) && 'redirect-anwp-pg-elementor-premium' === $_GET['page'] ) {
			// phpcs:ignore WordPress.Security.SafeRedirect
			wp_redirect( 'https://anwp.pro/anwp-post-grid-for-elementor-premium/' );
			die;
		}
	}

	/**
	 * Activation redirect
	 *
	 * @since 0.7.0
	 */
	public function activation_redirect() {

		if ( get_option( 'anwp_pg_elementor_do_activation_redirect', false ) && ! isset( $_GET['activate-multi'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification

			// Remove option
			delete_option( 'anwp_pg_elementor_do_activation_redirect' );

			// Init redirect
			wp_safe_redirect( admin_url( '/admin.php?page=anwp-post-grid' ) );
			exit;
		}
	}

	/**
	 * Activate the plugin.
	 *
	 * @since  0.7.0
	 */
	public function activate() {
		if ( ! get_option( 'anwp_pg_elementor_do_activation_redirect', false ) ) {
			add_option( 'anwp_pg_elementor_do_activation_redirect', true );
		}
	}

	/**
	 * Check if it is Widget Block
	 *
	 * @since  0.7.0
	 */
	public function is_widget_block() {
		return 'anwp_pg_widget' === get_post_type();
	}

	/**
	 * Check if premium version is active
	 *
	 * @since  0.7.0
	 */
	public static function is_premium_active() {
		return defined( 'ANWP_POST_GRID_PREMIUM_VERSION' );
	}
}
