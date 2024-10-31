<?php

namespace RuvuvElementorExpand\Modules\Particle;

use Elementor\Core\Responsive\Responsive;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Elementor\Core\Files\CSS\Post;
use Elementor\Element_Base;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use Elementor\Scheme_Typography;
use Elementor\Element_Column;
use Elementor\Element_Section;
use Elementor\Widget_Base;
use RuvuvElementorExpand\Base\Module_Base;
use RuvuvElementorExpand\Ruvuv_Extension;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public $brand = '<img src="'.RUVUV_EXPAND_ASSETS_URL.'image/logo.png'.'" style="max-height: 12px; margin-right: 10px; vertical-align: middle;">';

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'particle';
	}

	public function bg_particle_register_controls(Controls_Stack $element) {
		$element->start_controls_section(
			'eg_particle_tab',
			[
				'label' => $this->brand . __( 'Background Particle', 'ruvuv-extension' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$element->add_control(
			'eg_section_particles_on',
			[
				'label'        => esc_html__( 'Enable', 'ruvuv-extension' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => '',
				'return_value' => 'yes',
				'render_type' => 'template',
				'description'  => __( 'Switch on to enable Particles options!', 'ruvuv-extension' ),
			]
		);

		$element->add_control(
			'eg_section_particles_style',
			[
				'label' => __( 'Select Style', 'ruvuv-extension' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'custom' => __( 'Custom', 'ruvuv-extension' ),
					'1' => __( 'Style One', 'ruvuv-extension' ),
					'2' => __( 'Style Two', 'ruvuv-extension' ),
					'3' => __( 'Style Three', 'ruvuv-extension' ),
					'4' => __( 'Style Four', 'ruvuv-extension' ),
					'5' => __( 'Style Five', 'ruvuv-extension' ),
					'6' => __( 'Style Six', 'ruvuv-extension' ),
				],
				'render_type' => 'template',
				'default'	=> 'custom',
				'condition'    => [
					'eg_section_particles_on' => 'yes',
				],
			]
		);
		
		$element->add_control(
			'eg_section_particles_js',
			[
				'label' => esc_html__( 'Particles JSON', 'ruvuv-extension' ),
				'type' => Controls_Manager::TEXTAREA,
				'condition' => [
					'eg_section_particles_on' => 'yes',
					'eg_section_particles_style' => 'custom',
				],
				'description' => __( 'Paste your particles JSON code here - Generate it from <a href="http://vincentgarreau.com/particles.js/#default" target="_blank">Here</a>.', 'ruvuv-extension' ),
				'default' => '',
				'render_type' => 'template',
			]
		);

		$element->add_control(
			'eg_section_particle_zindex',
			[
				'label' => __( 'Z-Index', 'ruvuv-extension' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [
					'eg_section_particles_on' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}.ruvuv-particles .particles-js-canvas-el' => 'z-index: {{SIZE}}',
				],
				'render_type' => 'template',
			]
		);

		$element->end_controls_section();
	}

	public function eg_particles_before_render($section) {    		
		$settings = $section->get_settings();
		if( $section->get_settings( 'eg_section_particles_on' ) == 'yes' ) {
			$section->add_render_attribute( '_wrapper', 'class', 'ruvuv-particles' );
			$element_id = $section->get_settings( '_element_id' );
			if (empty($element_id)) {
				$section->add_render_attribute( '_wrapper', 'id', 'ruvuv-particles-' . $section->get_id() );
			}
		}
	}

	public function particles_after_render($section) {
		$settings = $section->get_settings();
		$element_id = $section->get_settings( '_element_id' );
		if (empty($element_id)) {
			$id = 'ruvuv-particles-'.$section->get_id();
		} else {
			$id = $section->get_settings( '_element_id' );
		}
		if( $section->get_settings( 'eg_section_particles_on' ) == 'yes' ) {
			if ($settings['eg_section_particles_style'] == 'custom') {	
				if ( ! empty( $settings['eg_section_particles_js'] ) ) { ?>
					<script type="text/javascript">
						document.addEventListener("DOMContentLoaded", evt => {
							particlesJS("<?php echo esc_attr($id); ?>", <?php echo $settings['eg_section_particles_js']; ?> );
						});
					</script>
				<?php }
			} else if($settings['eg_section_particles_style'] == '1') { ?>
				<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", evt => {
						particlesJS("<?php echo esc_attr($id); ?>", {"particles":{"number":{"value":200,"density":{"enable":true,"value_area":800}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"star","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.15728691040806816,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":10.782952832645451,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":500,"color":"#ffffff","opacity":0.4,"width":2},"move":{"enable":true,"speed":5,"direction":"bottom-right","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":0.5}},"bubble":{"distance":400,"size":4,"duration":0.3,"opacity":1,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true} );
					});
				</script>
			<?php } else if($settings['eg_section_particles_style'] == '2') { ?>
				<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", evt => {
						particlesJS("<?php echo esc_attr($id); ?>", {"particles":{"number":{"value":16,"density":{"enable":false,"value_area":2367.442924896818}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.1431318113305818,"random":false,"anim":{"enable":false,"speed":3.233766233766234,"opacity_min":0.1,"sync":false}},"size":{"value":15.782952832645451,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":561.194221302933,"color":"#ffffff","opacity":0.14430708547789706,"width":3.0464829156444933},"move":{"enable":true,"speed":3.206824121731046,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":3286.994724774322,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"repulse"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true} );
					});
				</script>
			<?php } else if($settings['eg_section_particles_style'] == '3') { ?>
				<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", evt => {
						particlesJS("<?php echo esc_attr($id); ?>", {"particles":{"number":{"value":213,"density":{"enable":false,"value_area":1341.5509907748635}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"triangle","stroke":{"width":0,"color":"#ffffff"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.25654592973848367,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":0,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":128.27296486924183,"color":"#ffffff","opacity":0.49705773886831206,"width":0.9620472365193136},"move":{"enable":true,"speed":8.017060304327615,"direction":"top-left","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":481.0236182596568,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":false,"mode":"remove"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":475.0651691962869,"size":4.060386061506726,"duration":4.060386061506725,"opacity":0.6983864025791567,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true} );
					});
				</script>
			<?php } else if($settings['eg_section_particles_style'] == '4') { ?>
				<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", evt => {
						particlesJS("<?php echo esc_attr($id); ?>", {"particles":{"number":{"value":10,"density":{"enable":true,"value_area":200}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":6},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.07034120608655228,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":45.96902595506592,"random":true,"anim":{"enable":true,"speed":10,"size_min":20,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":4.734885849793636,"direction":"none","random":true,"straight":false,"out_mode":"bounce","bounce":false,"attract":{"enable":false,"rotateX":1202.559045649142,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":200,"size":40,"duration":8,"opacity":0.6,"speed":3},"repulse":{"distance":100,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true} );
					});
				</script>
			<?php } else if($settings['eg_section_particles_style'] == '5') { ?>
				<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", evt => {
						particlesJS("<?php echo esc_attr($id); ?>", {"particles":{"number":{"value":10,"density":{"enable":true,"value_area":200}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":4},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.07034120608655228,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":30,"random":false,"anim":{"enable":true,"speed":20,"size_min":20,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":4.734885849793636,"direction":"bottom","random":false,"straight":true,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":1202.559045649142,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":100,"size":60,"duration":2,"opacity":8,"speed":4},"repulse":{"distance":100,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true} );
					});
				</script>
			<?php } else if($settings['eg_section_particles_style'] == '6') { ?>
				<script type="text/javascript">
					document.addEventListener("DOMContentLoaded", evt => {
						particlesJS("<?php echo esc_attr($id); ?>", {"particles":{"number":{"value":160,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.2,"width":1},"move":{"enable":true,"speed":1,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true} );
					});
				</script>
			<?php }
		}
	}

	private function add_actions() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'bg_particle_register_controls' ] );
		add_action( 'elementor/frontend/section/before_render', [ $this, 'eg_particles_before_render' ], 10, 2 );
		add_action( 'elementor/frontend/section/after_render', [ $this, 'particles_after_render' ], 10, 1 );
		
		add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_scripts' ), 9 );
	}

	public function enqueue_scripts() {
		wp_enqueue_script(
            'eg-particles',
            RUVUV_EXPAND_ASSETS_URL . 'lib/particles/particles.min.js',
            [
                'jquery'
            ], null, false
        );
	}

}