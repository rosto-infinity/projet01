<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Icons_Manager;

/**
 * AnWP Post Grid Elements :: Classic Blog
 *
 * @since   0.1.0
 * @package AnWP_Post_Grid
 */

class AnWP_Post_Grid_Element_Classic_Blog extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget name.
	 * @since  0.1.0
	 * @access public
	 *
	 */
	public function get_name() {
		return 'anwp-pg-classic-blog';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since  0.1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __( 'Classic Blog', 'anwp-post-grid' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since  0.1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'anwp-pg-element anwp-pg-classic-blog__admin-icon';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'grid', 'posts', 'post', 'post grid', 'posts grid', 'blog post', 'blog posts', 'masonry', 'anwp' ];
	}

	/**
	 * Get widget categories.
	 *
	 * Retrieve the list of categories the oEmbed widget belongs to.
	 *
	 * @return array Widget categories.
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function get_categories() {
		return [ 'anwp-pg' ];
	}

	/**
	 * Register widget controls.
	 *
	 * @since  0.1.0
	 * @access protected
	 */
	protected function register_controls() { // phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		/**
		 * Before start of controls.
		 *
		 * @param AnWP_Post_Grid_Element_Classic_Blog $this The element.
		 *
		 * @since 0.8.3
		 */
		do_action( 'anwp-pg-el/element-classic-blog/before_controls_start', $this );

		/**
		 * Load Section - Query
		 *
		 * @param AnWP_Post_Grid_Element_Classic_Blog $this The element.
		 *
		 * @since 0.8.3
		 */
		do_action( 'anwp-pg-el/general/section_query', $this );

		/**
		 * Load Section - Header.
		 *
		 * @param AnWP_Post_Grid_Element_Classic_Blog $this The element.
		 *
		 * @since 0.7.0
		 */
		do_action( 'anwp-pg-el/general/section_header', $this );

		/**
		 * After Control Section - Header.
		 *
		 * @param AnWP_Post_Grid_Element_Classic_Blog $this The element.
		 *
		 * @since 0.7.0
		 */
		do_action( 'anwp-pg-el/element-classic-blog/after_control_section_header', $this );

		/*
		|--------------------------------------------------------------------
		| Post Icons
		|--------------------------------------------------------------------
		*/
		$this->start_controls_section(
			'section_anwp_grid_post_icon_layout',
			[
				'label' => __( 'Post Icon', 'anwp-post-grid' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_post_icon',
			[
				'label'        => __( 'Show Post Icon', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'post_icon_size',
			[
				'label'        => __( 'Icon Size', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					'16' => __( '16 px', 'anwp-post-grid' ),
					'24' => __( '24 px', 'anwp-post-grid' ),
					'36' => __( '36 px', 'anwp-post-grid' ),
				],
				'default'      => '16',
				'prefix_class' => 'anwp-pg-post-teaser__post-icon--size-',
			]
		);

		$this->add_control(
			'post_icon_position',
			[
				'label'        => __( 'Icon Position', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SELECT,
				'options'      => [
					''             => __( 'top right', 'anwp-post-grid' ),
					'center'       => __( 'center', 'anwp-post-grid' ),
					'bottom-right' => __( 'bottom right', 'anwp-post-grid' ),
				],
				'default'      => '',
				'prefix_class' => 'anwp-pg-post-teaser__post-icon--position-',
			]
		);

		$this->end_controls_section();

		/*
		|--------------------------------------------------------------------
		| Styles and Layout Section
		|--------------------------------------------------------------------
		*/
		$this->start_controls_section(
			'section_anwp_grid_style_layout',
			[
				'label' => __( 'Styles and Layout', 'anwp-post-grid' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'post_image_width',
			[
				'label'   => __( 'Post Image Width', 'anwp-post-grid' ),
				'type'    => Controls_Manager::SELECT,
				'options' => [
					'1_2' => '1/2',
					'1_3' => '1/3',
				],
				'default' => '1_3',
			]
		);

		$this->add_control(
			'show_category',
			[
				'label'        => __( 'Show Category', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'category_limit',
			[
				'label'     => __( 'Category Limit', 'anwp-post-grid' ),
				'type'      => Controls_Manager::NUMBER,
				'min'       => 1,
				'max'       => 7,
				'step'      => 1,
				'default'   => 1,
				'condition' => [
					'show_category' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_date',
			[
				'label'        => __( 'Show Post Date', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_comments',
			[
				'label'        => __( 'Show Comments & Views', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'show_author',
			[
				'label'        => __( 'Show Author', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'hr_style_1',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'label'   => __( 'Image Size', 'anwp-post-grid' ),
				'name'    => 'grid_thumbnail',
				'exclude' => [ 'custom' ],
				'default' => 'medium',
			]
		);

		$this->add_control(
			'hr_style_108',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'pg_border_radius',
			[
				'label'      => esc_html__( 'Border Radius', 'anwp-post-grid' ),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range'      => [
					'px' => [
						'max' => 200,
					],
				],
				'default'    => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors'  => [
					'{{WRAPPER}} .anwp-pg-post-teaser__thumbnail' => 'border-radius: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => 'pg_parallax_border',
				'label'    => esc_html__( 'Border', 'anwp-post-grid' ),
				'selector' => '{{WRAPPER}} .anwp-pg-post-teaser__thumbnail',
			]
		);

		$this->add_control(
			'hr_style_2',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'category_background',
			[
				'label'     => __( 'Category Background Color', 'anwp-post-grid' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .anwp-pg-classic-blog .anwp-pg-category__wrapper-filled' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'category_text_color',
			[
				'label'     => __( 'Category Text Color', 'anwp-post-grid' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'alpha'     => false,
				'selectors' => [
					'{{WRAPPER}} .anwp-pg-classic-blog .anwp-pg-category__wrapper-filled' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'post_title_options_heading',
			[
				'label'     => __( 'Post Title', 'anwp-post-grid' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_teaser_title',
				'label'    => __( 'Typography', 'anwp-post-grid' ),
				'selector' => '{{WRAPPER}} .anwp-pg-post-teaser__title',
			]
		);

		$this->add_control(
			'post_title_color',
			[
				'label'     => __( 'Color', 'anwp-post-grid' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .anwp-pg-post-teaser__title a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_meta_options_heading',
			[
				'label'     => __( 'Post Meta', 'anwp-post-grid' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_teaser_meta',
				'label'    => __( 'Typography', 'anwp-post-grid' ),
				'selector' => '{{WRAPPER}} .anwp-pg-post-teaser__meta-comments, {{WRAPPER}} .anwp-pg-post-teaser__category-wrapper, {{WRAPPER}} .anwp-pg-post-teaser__meta-views, {{WRAPPER}} .anwp-pg-post-teaser__bottom-meta',
			]
		);

		$this->add_control(
			'post_meta_vertical_margin',
			[
				'label'     => __( 'Vertical Margin', 'anwp-post-grid' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => [
					'-5px' => '-5px',
					'0'    => '0',
					'5px'  => '5px',
					'10px' => '10px',
					'15px' => '15px',
					'20px' => '20px',
				],
				'default'   => '15px',
				'selectors' => [
					'{{WRAPPER}} .anwp-pg-post-teaser__bottom-meta' => 'margin-top: {{VALUE}}; margin-bottom: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'post_meta_background_color',
			[
				'label'     => __( 'Background Color', 'anwp-post-grid' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .anwp-pg-post-teaser__bottom-meta' => 'background-color: {{VALUE}}; padding: 3px 5px;',
				],
			]
		);

		$this->add_control(
			'post_meta_text_color',
			[
				'label'     => __( 'Text Color', 'anwp-post-grid' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .anwp-pg-post-teaser__bottom-meta'               => 'color: {{VALUE}} !important',
					'{{WRAPPER}} .anwp-pg-post-teaser__bottom-meta .anwp-pg-icon' => 'fill: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'post_excerpt_options_heading',
			[
				'label'     => __( 'Post Excerpt', 'anwp-post-grid' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'excerpt_num_words',
			[
				'label'       => __( 'Number of Words', 'anwp-post-grid' ),
				'label_block' => false,
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => 30,
			]
		);

		$this->add_control(
			'excerpt_source',
			[
				'label'       => __( 'Excerpt Source', 'anwp-post-grid' ),
				'type'        => Controls_Manager::SELECT,
				'description' => __( 'Use "Post Content" when you want to increase number of words or allow HTML', 'anwp-post-grid' ),
				'options'     => [
					''             => __( 'Default (excerpt)', 'anwp-post-grid' ),
					'post_content' => __( 'Post Content', 'anwp-post-grid' ),
				],
				'default'     => '',
			]
		);

		$this->add_control(
			'excerpt_html',
			[
				'label'        => __( 'Allow HTML', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_excerpt',
				'label'    => __( 'Typography', 'anwp-post-grid' ),
				'selector' => '{{WRAPPER}} .anwp-pg-post-teaser__excerpt',
			]
		);

		$this->add_control(
			'post_excerpt_text_color',
			[
				'label'     => __( 'Text Color', 'anwp-post-grid' ),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .anwp-pg-post-teaser__excerpt' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->end_controls_section();

		/*
		|--------------------------------------------------------------------
		| Read More button
		|--------------------------------------------------------------------
		*/
		$this->start_controls_section(
			'section_anwp_grid_read_more',
			[
				'label' => __( 'Read More', 'anwp-post-grid' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_read_more',
			[
				'label'        => __( 'Show "Read More" button', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'no',
			]
		);

		$this->add_control(
			'read_more_label',
			[
				'label'       => __( '"Read more" alternative text', 'anwp-post-grid' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			]
		);

		$this->add_control(
			'read_more_class',
			[
				'label'       => __( '"Read more" custom classes', 'anwp-post-grid' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
			]
		);

		$this->add_control(
			'read_more_class_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => 'You can use Bootstrap Button classes. <br>E.g.: "btn btn-sm btn-outline-primary"<br> More info <a target="_blank" href="https://getbootstrap.com/docs/4.5/components/buttons/">here</a>',
			]
		);

		$this->end_controls_section();

		/*
		|--------------------------------------------------------------------
		| Load More
		|--------------------------------------------------------------------
		*/
		$this->start_controls_section(
			'section_anwp_grid_load_more',
			[
				'label' => __( 'Load More', 'anwp-post-grid' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_load_more_important_note',
			[
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => __( '"Load more" button is not available if "Posts to Show" option is set to "custom"', 'anwp-post-grid' ),
				'condition' => [
					'posts_to_show' => 'custom',
				],
			]
		);

		$this->add_control(
			'show_load_more',
			[
				'label'        => __( 'Show "Load More" button', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'posts_to_show!' => 'custom',
				],
			]
		);

		$this->add_control(
			'posts_per_load',
			[
				'label'       => __( 'Number of Posts per Load', 'anwp-post-grid' ),
				'label_block' => false,
				'type'        => Controls_Manager::NUMBER,
				'min'         => 1,
				'step'        => 1,
				'default'     => 3,
				'condition'   => [
					'posts_to_show!' => 'custom',
				],
			]
		);

		$this->add_control(
			'load_more_label',
			[
				'label'       => __( '"Load more" button alternative text', 'anwp-post-grid' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => [
					'posts_to_show!' => 'custom',
				],
			]
		);

		$this->add_control(
			'load_more_class',
			[
				'label'       => __( '"Load more" button custom classes', 'anwp-post-grid' ),
				'label_block' => true,
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => [
					'posts_to_show!' => 'custom',
				],
			]
		);

		$this->end_controls_section();

		/*
		|--------------------------------------------------------------------
		| Pagination
		|--------------------------------------------------------------------
		*/
		$this->start_controls_section(
			'section_anwp_grid_pagination',
			[
				'label' => __( 'Pagination', 'anwp-post-grid' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'show_pagination_important_note',
			[
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => __( 'Pagination is not available if "Load More" option is active', 'anwp-post-grid' ),
				'condition' => [
					'show_load_more' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_pagination',
			[
				'label'        => __( 'Show Pagination', 'anwp-post-grid' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'anwp-post-grid' ),
				'label_off'    => __( 'No', 'anwp-post-grid' ),
				'return_value' => 'yes',
				'default'      => 'no',
				'condition'    => [
					'show_load_more!' => 'yes',
				],
			]
		);

		$this->add_control(
			'show_pagination_page_note',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw'  => __( 'Posts per page are equal to "Posts Limit" option from "Query" section', 'anwp-post-grid' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Before end of controls.
		 *
		 * @param AnWP_Post_Grid_Element_Classic_Blog $this The element.
		 *
		 * @since 0.7.0
		 */
		do_action( 'anwp-pg-el/element-classic-blog/before_controls_end', $this );

		/**
		 * Before end of controls.
		 *
		 * @param Widget_Base $this The element.
		 *
		 * @since 0.7.0
		 */
		do_action( 'anwp-pg-el/element/before_controls_end', $this );
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @since  0.1.0
	 * @access protected
	 */
	protected function render() {

		/*
		|--------------------------------------------------------------------
		| Merge arguments into defaults array
		|--------------------------------------------------------------------
		*/
		$data = (object) wp_parse_args(
			$this->get_settings_for_display(),
			[
				'posts_to_show'   => 'latest',
				'show_load_more'  => false,
				'limit'           => 3,
				'header_icon'     => '',
				'show_pagination' => '',
			]
		);

		/*
		|--------------------------------------------------------------------
		| Get Posts
		|--------------------------------------------------------------------
		*/
		$show_load_more = 'yes' === $data->show_load_more;

		if ( $show_load_more && 'custom' === $data->posts_to_show ) {
			$show_load_more = false;
		}

		// Pre getting grid posts
		if ( $show_load_more ) {
			$data->limit ++;
		}

		$data->grid_posts = anwp_post_grid()->elements->get_grid_posts( $data );

		// Post getting grid posts
		if ( $show_load_more ) {

			$data->limit --;

			$show_load_more = count( $data->grid_posts ) > $data->limit;

			if ( $show_load_more ) {
				array_pop( $data->grid_posts );
			}
		}

		// Update "load more"
		$data->show_load_more = $show_load_more;

		// Pagination
		if ( 'yes' === $data->show_pagination && ! $show_load_more ) {
			$data->total_posts = count( anwp_post_grid()->elements->get_grid_posts( $data, 'ids' ) );
		}

		// Icon
		if ( ! empty( $data->header_icon ) ) {
			ob_start();

			Icons_Manager::render_icon( $data->header_icon, [ 'class' => 'anwp-pg-widget-header__icon' ], 'span' );
			$data->header_icon = ob_get_clean();
		}

		/*
		|--------------------------------------------------------------------
		| Render
		|--------------------------------------------------------------------
		*/
		anwp_post_grid()->load_partial( $data, 'classic-blog' );
	}
}

