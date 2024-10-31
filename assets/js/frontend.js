( function( $, elementor ) {
	jQuery( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/widget', function($scope)  {
			var editMode = Boolean( elementorFrontend.isEditMode() ),
				rellax = null;

			if (! editMode) {
				var settings = $scope.data('settings');
				if ($scope.hasClass('ruvuv-rellax')) {
					jQuery($scope).attr('data-rellax-speed', settings['rellax_parallax_speed']['size']);
					jQuery($scope).attr('data-rellax-percentage', settings['rellax_parallax_percentage']['size']);
					jQuery($scope).attr('data-rellax-zindex', settings['rellax_parallax_zindex']);
					rellax = new Rellax($scope[0]);
				}
			}

			var EgFrontendStickyHandler = elementorFrontend.Module.extend( {

				isActive: function() {
					return undefined !== this.$element.data( 'eg_sticky' );
				},

				activate: function(elementSettings) {
					var editMode = Boolean( elementorFrontend.isEditMode() ),
						widgetId = $scope.data( 'id' );
					if (!editMode) {
						var elementSettings = this.$element.data('settings') ? this.$element.data('settings') : {};
					} else {
						var elementSettings = RuvuvElementorExpandTools.widgetEditorSettings( widgetId );
					}
					var stickyOptions = {
						to: elementSettings.eg_sticky,
						offset: elementSettings.eg_sticky_offset,
						effectsOffset: elementSettings.eg_sticky_effects_offset,
						classes: {
							sticky: 'elementor-sticky',
							stickyActive: 'elementor-sticky--active elementor-section--handles-inside',
							stickyEffects: 'elementor-sticky--effects',
							spacer: 'elementor-sticky__spacer'
						}
					},
					$wpAdminBar = elementorFrontend.getElements( '$wpAdminBar' );

					if ( elementSettings.eg_sticky_parent ) {
						stickyOptions.parent = '.elementor-widget-wrap';
					}

					if ( $wpAdminBar.length && 'top' === elementSettings.eg_sticky && 'fixed' === $wpAdminBar.css( 'position' ) ) {
						stickyOptions.offset += $wpAdminBar.height();
					}

					this.$element.sticky( stickyOptions );
				},

				deactivate: function() {
					if ( ! this.isActive() ) {
						return;
					}

					this.$element.sticky( 'destroy' );
				},

				run: function( elementSettings, refresh ) {
					if ( ! elementSettings.eg_sticky ) {
						this.deactivate();

						return;
					}

					var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
						activeDevices = elementSettings.eg_sticky_on;

					if ( -1 !== activeDevices.indexOf( currentDeviceMode ) ) {
						if ( refresh ) {
							this.reactivate();
						} else if ( ! this.isActive() ) {
							this.activate(elementSettings);
						}
					} else {
						this.deactivate();
					}
				},

				reactivate: function() {
					this.deactivate();

					this.activate();
				},

				onElementChange: function( settingKey ) {
					var editMode = Boolean( elementorFrontend.isEditMode() ),
						widgetId = $scope.data( 'id' );
					if (!editMode) {
						var elementSettings = this.$element.data('settings') ? this.$element.data('settings') : {};
					} else {
						var elementSettings = RuvuvElementorExpandTools.widgetEditorSettings( widgetId );
					}
					if ( -1 !== [ 'eg_sticky', 'eg_sticky_on' ].indexOf( settingKey ) ) {
						this.run( elementSettings, true );
					}

					if ( -1 !== [ 'eg_sticky_offset', 'eg_sticky_effects_offset', 'eg_sticky_parent' ].indexOf( settingKey ) ) {
						this.reactivate();
					}
				},

				onInit: function() {
					elementorFrontend.Module.prototype.onInit.apply( this, arguments );

					var editMode = Boolean( elementorFrontend.isEditMode() ),
						widgetId = $scope.data( 'id' );
					if (!editMode) {
						var elementSettings = this.$element.data('settings') ? this.$element.data('settings') : {};
					} else {
						var elementSettings = RuvuvElementorExpandTools.widgetEditorSettings( widgetId );
					}

					this.run(elementSettings);
				},

				onDestroy: function() {
					elementorFrontend.Module.prototype.onDestroy.apply( this, arguments );

					this.deactivate();
				}
			} );
			
			new EgFrontendStickyHandler( { $element: $scope } );

			tooltipInstance = new ruvuvTooltip( $scope );
			tooltipInstance.init();
		} );

		elementorFrontend.hooks.addAction( 'frontend/element_ready/column', function($scope)  {
			var editMode = Boolean( elementorFrontend.isEditMode() ),
				columnId = $scope.data( 'id' );
				columnStickyOptions = {
			        containerSelector: '.elementor-row',
			        innerWrapperSelector: '.elementor-column-wrap',
			        stickyClass: 'is-affixed',
			        topSpacing: 20,
			        bottomSpacing: 20
			    },
				tiltOptions = {
			        reset: true,
			        scale: 1,
			        axis: null
			    },
			    stickyInstance = null;

			var	sliderOptions = {
				transition : 'fade',
				transitionDuration : 400,
				duration : 5000,
				animateFirst: true
			};

			if(! editMode) {
				var settings = $scope.data('settings');
				if(settings && $scope.hasClass('ruvuv-sticky-column') && ( -1 !== settings.eg_column_sticky_on.indexOf( elementorFrontend.getCurrentDeviceMode() ) )) {
					$scope.data( 'stickyColumnInit', true );

					columnStickyOptions.topSpacing = settings.eg_column_sticky_top_offset;
					columnStickyOptions.bottomSpacing = settings.eg_column_sticky_bottom_offset;
					
					stickyInstance = new StickySidebar($scope[0], columnStickyOptions);

					jQuery(window).on( 'resize.RuvuvElementorExpandStickyColumn orientationchange.RuvuvElementorExpandStickyColumn', RuvuvElementorExpandTools.debounce( 50, resizeDebounce ) );
				}
				if($scope.hasClass('bg-moving-image')) {
					if ( $scope.data('moving') && (-1 !==settings.bg_moving_image_on.indexOf( elementorFrontend.getCurrentDeviceMode() )) ) {
						jQuery($scope).find('.elementor-column-wrap').bgscroll({scrollSpeed:$scope.data('moving').speed , direction:$scope.data('moving').direction, direction_type: $scope.data('moving').direction_type, direction_diagonal: $scope.data('moving').direction_diagonal });
						if ($scope.data('moving').direction == 'h') {
							jQuery($scope).find('.elementor-column-wrap').css('background-repeat', 'repeat-x');
						} else if ($scope.data('moving').direction == 'v') {
							jQuery($scope).find('.elementor-column-wrap').css('background-repeat', 'repeat-y');
						} else {
							jQuery($scope).find('.elementor-column-wrap').css('background-repeat', 'repeat');
						}
					}
				}
				if ($scope.hasClass('ruvuv-rellax')) {
					jQuery($scope).attr('data-rellax-speed', settings['rellax_parallax_speed']['size']);
					jQuery($scope).attr('data-rellax-percentage', settings['rellax_parallax_percentage']['size']);
					jQuery($scope).attr('data-rellax-zindex', settings['rellax_parallax_zindex']);
					rellax = new Rellax($scope[0]);
				}

				if ($scope.hasClass('eg-bg-slider')) {
					if ($scope.data('slider')) {

						var urls = $scope.data('slider');

						var attachments = [];

						jQuery.each(urls, function(index, url) {
							var mediaURL = '';
							if (url.eg_bg_slider_media_select == 'media' && url.eg_bg_slider_elements_media != null) {
								mediaURL =  url.eg_bg_slider_elements_media['url'];
							} else if (url.eg_bg_slider_media_select == 'link' && url.eg_bg_slider_elements_url != null) {
								mediaURL =  url.eg_bg_slider_elements_url;
							}
							var mediaAlt = '';
							if (url.eg_bg_slider_elements_alt != null) {
								mediaAlt = url.eg_bg_slider_elements_alt;
							}
							attachments.push([{'url':mediaURL, 'alt':mediaAlt}]);
						});

						var settings = $scope.data('settings');

						sliderOptions.duration = settings.eg_bg_slider_duration['size'];
						sliderOptions.transition = settings.eg_bg_slider_transition;
						sliderOptions.transitionDuration = settings.eg_bg_slider_transitionDuration['size'];

						if(attachments != null) {
							jQuery($scope[0]).backstretch(attachments, sliderOptions);
						}
					}
				}
			} else {
				settings = RuvuvElementorExpandTools.columnEditorSettings( columnId );
				if ( 'yes' === settings['sticky'] ) {
					$scope.addClass( 'ruvuv-sticky-column' );

					if ( -1 !== settings['stickyOn'].indexOf( elementorFrontend.getCurrentDeviceMode() ) ) {
						columnStickyOptions.topSpacing = settings['topSpacing'];
						columnStickyOptions.bottomSpacing = settings['bottomSpacing'];

						$scope.data( 'stickyColumnInit', true );

						stickyInstance = new StickySidebar( $scope[0], columnStickyOptions );

						jQuery(window).on( 'resize.RuvuvElementorExpandStickyColumn orientationchange.RuvuvElementorExpandStickyColumn', RuvuvElementorExpandTools.debounce( 50, resizeDebounce ) );
					}
				}

				if (settings['eg_bg_slider_on'] == 'yes') {
					$scope.addClass('eg-bg-slider');
					if (settings['eg_bg_slider_elements'] != null) {

						var urls = settings['eg_bg_slider_elements'];

						var attachments = [];

						jQuery.each( urls.models, function( index, url ) {
							var mediaURL = '';
							if (url.attributes.eg_bg_slider_media_select == 'media' && url.attributes.eg_bg_slider_elements_media != null) {
								mediaURL =  url.attributes.eg_bg_slider_elements_media['url'];
							} else if (url.attributes.eg_bg_slider_media_select == 'link' && url.attributes.eg_bg_slider_elements_url != null) {
								mediaURL =  url.attributes.eg_bg_slider_elements_url;
							}
							var mediaAlt = '';
							if (url.attributes.eg_bg_slider_elements_alt != null) {
								mediaAlt = url.attributes.eg_bg_slider_elements_alt;
							}
							attachments.push([{'url':mediaURL, 'alt':mediaAlt, 'mute': false}]);
						} );

						sliderOptions.duration = settings.eg_bg_slider_duration['size'];
						sliderOptions.transition = settings.eg_bg_slider_transition;
						sliderOptions.transitionDuration = settings.eg_bg_slider_transitionDuration['size'];

						if(attachments != null) {
							jQuery($scope[0]).backstretch(attachments, sliderOptions);
						}
					}
				} else {
					$scope.removeClass('eg-bg-slider');
				}
			}

			function resizeDebounce() {
				var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
					availableDevices  = settings['eg_column_sticky_on'] || [],
					isInit            = $scope.data( 'stickyColumnInit' );

				if ( -1 !== availableDevices.indexOf( currentDeviceMode ) ) {

					if ( ! isInit ) {
						$scope.data( 'stickyColumnInit', true );
						stickyInstance = new StickySidebar( $scope[0], columnStickyOptions );
						stickyInstance.updateSticky();
					}
				} else {
					$scope.data( 'stickyColumnInit', false );
					stickyInstance.destroy();
				}
			}
		});

		elementorFrontend.hooks.addAction( 'frontend/element_ready/section', function($scope)  {

			var editMode = Boolean( elementorFrontend.isEditMode() ),
				columnId = $scope.data( 'id' );

			// sticky handler
			var EgFrontendStickyHandler = elementorFrontend.Module.extend( {

				isActive: function() {
					return undefined !== this.$element.data( 'eg_sticky' );
				},

				activate: function(elementSettings) {
					var editMode = Boolean( elementorFrontend.isEditMode() ),
						sectionId = $scope.data( 'id' );
					if (!editMode) {
						var elementSettings = this.$element.data('settings') ? this.$element.data('settings') : {};
					} else {
						var elementSettings = RuvuvElementorExpandTools.sectionEditorSettings( sectionId );
					}
					var stickyOptions = {
						to: elementSettings.eg_sticky,
						offset: elementSettings.eg_sticky_offset,
						effectsOffset: elementSettings.eg_sticky_effects_offset,
						classes: {
							sticky: 'elementor-sticky',
							stickyActive: 'elementor-sticky--active elementor-section--handles-inside',
							stickyEffects: 'elementor-sticky--effects',
							spacer: 'elementor-sticky__spacer'
						}
					},
					$wpAdminBar = elementorFrontend.getElements( '$wpAdminBar' );

					if ( elementSettings.eg_sticky_parent ) {
						stickyOptions.parent = '.elementor-widget-wrap';
					}

					if ( $wpAdminBar.length && 'top' === elementSettings.eg_sticky && 'fixed' === $wpAdminBar.css( 'position' ) ) {
						stickyOptions.offset += $wpAdminBar.height();
					}

					this.$element.sticky( stickyOptions );
				},

				deactivate: function() {
					if ( ! this.isActive() ) {
						return;
					}

					this.$element.sticky( 'destroy' );
				},

				run: function( elementSettings, refresh ) {
					if ( ! elementSettings.eg_sticky ) {
						this.deactivate();

						return;
					}

					var currentDeviceMode = elementorFrontend.getCurrentDeviceMode(),
						activeDevices = elementSettings.eg_sticky_on;

					if ( -1 !== activeDevices.indexOf( currentDeviceMode ) ) {
						if ( refresh ) {
							this.reactivate();
						} else if ( ! this.isActive() ) {
							this.activate(elementSettings);
						}
					} else {
						this.deactivate();
					}
				},

				reactivate: function() {
					this.deactivate();

					this.activate();
				},

				onElementChange: function( settingKey ) {
					var editMode = Boolean( elementorFrontend.isEditMode() ),
						sectionId = $scope.data( 'id' );
					if (!editMode) {
						var elementSettings = this.$element.data('settings') ? this.$element.data('settings') : {};
					} else {
						var elementSettings = RuvuvElementorExpandTools.sectionEditorSettings( sectionId );
					}
					if ( -1 !== [ 'eg_sticky', 'eg_sticky_on' ].indexOf( settingKey ) ) {
						this.run( elementSettings, true );
					}

					if ( -1 !== [ 'eg_sticky_offset', 'eg_sticky_effects_offset', 'eg_sticky_parent' ].indexOf( settingKey ) ) {
						this.reactivate();
					}
				},

				onInit: function() {
					elementorFrontend.Module.prototype.onInit.apply( this, arguments );

					var editMode = Boolean( elementorFrontend.isEditMode() ),
						sectionId = $scope.data( 'id' );
					if (!editMode) {
						var elementSettings = this.$element.data('settings') ? this.$element.data('settings') : {};
					} else {
						var elementSettings = RuvuvElementorExpandTools.sectionEditorSettings( sectionId );
					}

					this.run(elementSettings);
				},

				onDestroy: function() {
					elementorFrontend.Module.prototype.onDestroy.apply( this, arguments );

					this.deactivate();
				}
			} );
			new EgFrontendStickyHandler( { $element: $scope } );

			var	sliderOptions = {
				transition : 'fade',
				transitionDuration : 400,
				duration : 5000,
				animateFirst: true
			};
			if (! editMode) {
				var settings = $scope.data('settings');

				if($scope.hasClass('bg-moving-image')) {
					if ($scope.data('moving') && ( -1 !== settings.bg_moving_image_on.indexOf( elementorFrontend.getCurrentDeviceMode() ) )) {
						jQuery($scope).bgscroll({ scrollSpeed:$scope.data('moving').speed , direction:$scope.data('moving').direction, direction_type: $scope.data('moving').direction_type, direction_diagonal: $scope.data('moving').direction_diagonal, horizontal_position: $scope.data('moving').horizontal_position, vertical_position: $scope.data('moving').vertical_position });
						if ($scope.data('moving').direction == 'h') {
							jQuery($scope).css('background-repeat', 'repeat-x');
						} else if ($scope.data('moving').direction == 'v') {
							jQuery($scope).css('background-repeat', 'repeat-y');
						} else {
							jQuery($scope).css('background-repeat', 'repeat');
						}
					}
				}
				if ($scope.hasClass('eg-bg-slider')) {
					if ($scope.data('slider')) {
							
						var urls = $scope.data('slider');
						
						var attachments = [];
						
						jQuery.each(urls, function(index, url) {
							var mediaURL = '';
							if (url.eg_bg_slider_media_select == 'media' && url.eg_bg_slider_elements_media != null) {
								mediaURL =  url.eg_bg_slider_elements_media['url'];
							} else if (url.eg_bg_slider_media_select == 'link' && url.eg_bg_slider_elements_url != null) {
								mediaURL =  url.eg_bg_slider_elements_url;
							}
							var mediaAlt = '';
							if (url.eg_bg_slider_elements_alt != null) {
								mediaAlt = url.eg_bg_slider_elements_alt;
							}
							attachments.push([{'url':mediaURL, 'alt':mediaAlt}]);
						});

						var settings = $scope.data('settings');

						sliderOptions.duration = settings.eg_bg_slider_duration['size'];
						sliderOptions.transition = settings.eg_bg_slider_transition;
						sliderOptions.transitionDuration = settings.eg_bg_slider_transitionDuration['size'];

						if(attachments != null) {
							jQuery($scope[0]).backstretch(attachments, sliderOptions);
						}
					}
				}
				if ($scope.hasClass('ruvuv-rellax')) {
					jQuery($scope).attr('data-rellax-speed', settings['rellax_parallax_speed']['size']);
					jQuery($scope).attr('data-rellax-percentage', settings['rellax_parallax_percentage']['size']);
					jQuery($scope).attr('data-rellax-zindex', settings['rellax_parallax_zindex']);
					rellax = new Rellax($scope[0]);
				}
				if ($scope.hasClass('ruvuv-gradient-move')) {
					jQuery(".ruvuv-gradient-move-color").each(function() {
				    	try {
				      		var elem=jQuery(this).nextAll(".ruvuv-gradient-move:first")[0];
				      		jQuery(this).prependTo(elem);   
				    	} catch(e){
				    	}
				  	});
				}
			} else {
				settings = RuvuvElementorExpandTools.sectionEditorSettings( columnId );

				if (settings['background_color_changing'] == 'yes') {
					var colorString = '';
					colorCount = 1;
					if (settings['changing_colors'] != null) {
						$scope.addClass('ruvuv-gradient-move');
						jQuery.each( settings['changing_colors'].models, function( index, obj ) {
							if (colorCount < settings['changing_colors'].models.length) {
								colorString += obj.attributes.changing_color+',';
							} else {
								colorString += obj.attributes.changing_color;
							}
							colorCount++;
						} );
					}
						
					jQuery($scope[0]).prepend('<div class="ruvuv-gradient-move-color" style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; background: '+'linear-gradient('+settings.background_color_changing_angle['size']+'deg,'+colorString+'); background-size: 400% 400% !important; -webkit-animation: Gradient '+settings.background_color_animation_time['size']+'s ease-in-out infinite; -moz-animation: Gradient '+settings.background_color_animation_time['size']+'s ease-in-out infinite; animation: Gradient '+settings.background_color_animation_time['size']+'s ease-in-out infinite;"></div>');
				}

				if (settings['eg_bg_slider_on'] == 'yes') {
					$scope.addClass('eg-bg-slider');
					if (settings['eg_bg_slider_elements'] != null) {
							
						var urls = settings['eg_bg_slider_elements'];
						
						var attachments = [];

						jQuery.each( urls.models, function( index, url ) {
							var mediaURL = '';
							if (url.attributes.eg_bg_slider_media_select == 'media' && url.attributes.eg_bg_slider_elements_media != null) {
								mediaURL =  url.attributes.eg_bg_slider_elements_media['url'];
							} else if (url.attributes.eg_bg_slider_media_select == 'link' && url.attributes.eg_bg_slider_elements_url != null) {
								mediaURL =  url.attributes.eg_bg_slider_elements_url;
							}
							var mediaAlt = '';
							if (url.attributes.eg_bg_slider_elements_alt != null) {
								mediaAlt = url.attributes.eg_bg_slider_elements_alt;
							}
							attachments.push([{'url':mediaURL, 'alt':mediaAlt, 'mute': false}]);
						} );

						sliderOptions.duration = settings.eg_bg_slider_duration['size'];
						sliderOptions.transition = settings.eg_bg_slider_transition;
						sliderOptions.transitionDuration = settings.eg_bg_slider_transitionDuration['size'];

						if(attachments != null) {
							jQuery($scope[0]).backstretch(attachments, sliderOptions);
						}
					}
				} else {
					$scope.removeClass('eg-bg-slider');
				}
				if (settings['eg_section_particles_on'] == 'yes') {
					$scope.addClass('ruvuv-particles');
					var particlesId = 'ruvuv-particles-'+$scope.data('id');
					$scope.attr('id', particlesId);
					if(settings['eg_section_particles_style'] == 'custom') {
						if (settings['eg_section_particles_js'] != null) {
							particlesJS(particlesId, JSON.parse(settings['eg_section_particles_js']));
						}
					} else if(settings['eg_section_particles_style'] == '1') {
						particlesJS(particlesId, {"particles":{"number":{"value":200,"density":{"enable":true,"value_area":800}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"star","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.15728691040806816,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":10.782952832645451,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":500,"color":"#ffffff","opacity":0.4,"width":2},"move":{"enable":true,"speed":5,"direction":"bottom-right","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":0.5}},"bubble":{"distance":400,"size":4,"duration":0.3,"opacity":1,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
					} else if(settings['eg_section_particles_style'] == '2') {
						particlesJS(particlesId, {"particles":{"number":{"value":16,"density":{"enable":false,"value_area":2367.442924896818}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.1431318113305818,"random":false,"anim":{"enable":false,"speed":3.233766233766234,"opacity_min":0.1,"sync":false}},"size":{"value":15.782952832645451,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":false,"distance":561.194221302933,"color":"#ffffff","opacity":0.14430708547789706,"width":3.0464829156444933},"move":{"enable":true,"speed":3.206824121731046,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":3286.994724774322,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"repulse"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":400,"size":40,"duration":2,"opacity":8,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
					} else if(settings['eg_section_particles_style'] == '3') {
						particlesJS(particlesId, {"particles":{"number":{"value":213,"density":{"enable":false,"value_area":1341.5509907748635}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"triangle","stroke":{"width":0,"color":"#ffffff"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.25654592973848367,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":0,"random":true,"anim":{"enable":false,"speed":40,"size_min":0.1,"sync":false}},"line_linked":{"enable":true,"distance":128.27296486924183,"color":"#ffffff","opacity":0.49705773886831206,"width":0.9620472365193136},"move":{"enable":true,"speed":8.017060304327615,"direction":"top-left","random":false,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":481.0236182596568,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":false,"mode":"remove"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":475.0651691962869,"size":4.060386061506726,"duration":4.060386061506725,"opacity":0.6983864025791567,"speed":3},"repulse":{"distance":200,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
					} else if(settings['eg_section_particles_style'] == '4') {
						particlesJS(particlesId, {"particles":{"number":{"value":10,"density":{"enable":true,"value_area":200}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":6},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.07034120608655228,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":45.96902595506592,"random":true,"anim":{"enable":true,"speed":10,"size_min":20,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":4.734885849793636,"direction":"none","random":true,"straight":false,"out_mode":"bounce","bounce":false,"attract":{"enable":false,"rotateX":1202.559045649142,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":200,"size":40,"duration":8,"opacity":0.6,"speed":3},"repulse":{"distance":100,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
					} else if(settings['eg_section_particles_style'] == '5') {
						particlesJS(particlesId, {"particles":{"number":{"value":10,"density":{"enable":true,"value_area":200}},"color":{"value":["#BD10E0","#B8E986","#50E3C2","#FFD300","#E86363"]},"shape":{"type":"polygon","stroke":{"width":0,"color":"#000"},"polygon":{"nb_sides":4},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":0.07034120608655228,"random":true,"anim":{"enable":false,"speed":1,"opacity_min":0.1,"sync":false}},"size":{"value":30,"random":false,"anim":{"enable":true,"speed":20,"size_min":20,"sync":false}},"line_linked":{"enable":false,"distance":200,"color":"#ffffff","opacity":1,"width":2},"move":{"enable":true,"speed":4.734885849793636,"direction":"bottom","random":false,"straight":true,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":1202.559045649142,"rotateY":1200}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":false,"mode":"grab"},"onclick":{"enable":false,"mode":"push"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":100,"size":60,"duration":2,"opacity":8,"speed":4},"repulse":{"distance":100,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
					} else if(settings['eg_section_particles_style'] == '6') {
						particlesJS(particlesId, {"particles":{"number":{"value":160,"density":{"enable":true,"value_area":800}},"color":{"value":"#ffffff"},"shape":{"type":"circle","stroke":{"width":0,"color":"#000000"},"polygon":{"nb_sides":5},"image":{"src":"img/github.svg","width":100,"height":100}},"opacity":{"value":1,"random":true,"anim":{"enable":true,"speed":1,"opacity_min":0,"sync":false}},"size":{"value":3,"random":true,"anim":{"enable":false,"speed":4,"size_min":0.3,"sync":false}},"line_linked":{"enable":false,"distance":150,"color":"#ffffff","opacity":0.2,"width":1},"move":{"enable":true,"speed":1,"direction":"none","random":true,"straight":false,"out_mode":"out","bounce":false,"attract":{"enable":false,"rotateX":600,"rotateY":600}}},"interactivity":{"detect_on":"canvas","events":{"onhover":{"enable":true,"mode":"bubble"},"onclick":{"enable":true,"mode":"repulse"},"resize":true},"modes":{"grab":{"distance":400,"line_linked":{"opacity":1}},"bubble":{"distance":250,"size":0,"duration":2,"opacity":0,"speed":3},"repulse":{"distance":400,"duration":0.4},"push":{"particles_nb":4},"remove":{"particles_nb":2}}},"retina_detect":true});
					}
				}
			}
		} );

		var RuvuvElementorExpandTools = {
			debounce: function( threshold, callback ) {
				var timeout;

				return function debounced( $event ) {
					function delayed() {
						callback.call( this, $event );
						timeout = null;
					}

					if ( timeout ) {
						clearTimeout( timeout );
					}

					timeout = setTimeout( delayed, threshold );
				};
			},
			columnEditorSettings: function( columnId ) {
				var editorElements = null,
					columnData     = {};

				if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
					return false;
				}

				editorElements = window.elementor.elements;

				if ( ! editorElements.models ) {
					return false;
				}

				jQuery.each( editorElements.models, function( index, obj ) {

					jQuery.each( obj.attributes.elements.models, function( index, obj ) {
						if ( columnId == obj.id ) {
							columnData = obj.attributes.settings.attributes;
						}
					} );

				} );

				return {
					'sticky': columnData['eg_column_sticky'] || 'no',
					'topSpacing': columnData['eg_column_sticky_top_offset'] || 20,
					'bottomSpacing': columnData['eg_column_sticky_bottom_offset'] || 20,
					'stickyOn': columnData['eg_column_sticky_on'] || [ 'desktop', 'tablet', 'mobile'],
					'rellax_parallax': columnData['rellax_parallax'] || '',
					'rellax_parallax_speed': columnData['rellax_parallax_speed'] || { 'size': 0, 'unit': 'px'},
					'rellax_parallax_percentage': columnData['rellax_parallax_percentage'] || { 'size': 0, 'unit': 'px'},
					'rellax_parallax_zindex': columnData['rellax_parallax_zindex'] || 0,
					'eg_bg_slider_on': columnData['eg_bg_slider_on'] || '',
					'eg_bg_slider_elements': columnData['eg_bg_slider_elements'] || [],
					'eg_bg_slider_duration': columnData['eg_bg_slider_duration'] || { 'size': 5000, 'unit': 'px'},
					'eg_bg_slider_elements_mute': columnData['eg_bg_slider_elements_mute'] || false,
					'eg_bg_slider_transition': columnData['eg_bg_slider_transition'] || 'fade',
					'eg_bg_slider_transitionDuration': columnData['eg_bg_slider_transitionDuration'] || { 'size': 400, 'unit': 'px'},
				}
			},
			sectionEditorSettings: function( sectionId ) {
				var editorElements = null,
					sectionData     = {};

				if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
					return false;
				}

				editorElements = window.elementor.elements;

				if ( ! editorElements.models ) {
					return false;
				}

				jQuery.each( editorElements.models, function( index, obj ) {
					if ( sectionId == obj.id ) {
						sectionData = obj.attributes.settings.attributes;
					}
				} );

				return {
					'background_color_changing': sectionData['background_color_changing'] || '',
					'background_color_changing_angle': sectionData['background_color_changing_angle'] || { 'size': -45, 'unit': 'px'},
					'background_color_animation_time': sectionData['background_color_animation_time'] || { 'size': 12, 'unit': 'px'},
					'changing_colors': sectionData['changing_colors'] || [],
					'eg_sticky': sectionData['eg_sticky'] || '',
					'eg_sticky_on': sectionData['eg_sticky_on'] || [ 'desktop', 'tablet', 'mobile'],
					'eg_sticky_offset': sectionData['eg_sticky_offset'] || '0',
					'eg_sticky_effects_offset': sectionData['eg_sticky_effects_offset'] || '0',
					'eg_sticky_zindex': sectionData['eg_sticky_zindex'] || '10',
					'eg_sticky_parent': sectionData['eg_sticky_parent'] || false,
					'eg_section_bg_moving_image': sectionData['eg_section_bg_moving_image'] || '',
					'eg_section_bg_moving_image_value': sectionData['eg_section_bg_moving_image_value'] || 5,
					'eg_section_bg_moving_image_direction': sectionData['eg_section_bg_moving_image_direction'] || 'h',
					'eg_section_bg_moving_image_diagonal_reverse': sectionData['eg_section_bg_moving_image_diagonal_reverse'] || '',
					'eg_section_bg_moving_image_direction_type': sectionData['eg_section_bg_moving_image_direction_type'] || '+',
					'bg_moving_image_horizontal_position':  sectionData['bg_moving_image_horizontal_position'] || 'middle',
					'eg_bg_slider_on': sectionData['eg_bg_slider_on'] || '',
					'eg_bg_slider_elements': sectionData['eg_bg_slider_elements'] || [],
					'eg_bg_slider_duration': sectionData['eg_bg_slider_duration'] || { 'size': 5000, 'unit': 'px'},
					'eg_bg_slider_elements_mute': sectionData['eg_bg_slider_elements_mute'] || false,
					'eg_bg_slider_transition': sectionData['eg_bg_slider_transition'] || 'fade',
					'eg_bg_slider_transitionDuration': sectionData['eg_bg_slider_transitionDuration'] || { 'size': 400, 'unit': 'px'},
					'eg_section_particles_on': sectionData['eg_section_particles_on'] || '',
					'eg_section_particles_style': sectionData['eg_section_particles_style'] || 'custom',
					'eg_section_particles_js': sectionData['eg_section_particles_js'] || '',
					'background_image': sectionData['background_image'] || '',
					'rellax_parallax': sectionData['rellax_parallax'] || '',
					'rellax_parallax_speed': sectionData['rellax_parallax_speed'] || { 'size': 0, 'unit': 'px'},
					'rellax_parallax_percentage': sectionData['rellax_parallax_percentage'] || { 'size': 0, 'unit': 'px'},
					'rellax_parallax_zindex': sectionData['rellax_parallax_zindex'] || 0,
				}
			},
			widgetEditorSettings: function( widgetId ) {
				var editorElements = null,
					widgetData     = {};

				if ( ! window.elementor.hasOwnProperty( 'elements' ) ) {
					return false;
				}

				editorElements = window.elementor.elements;

				if ( ! editorElements.models ) {
					return false;
				}

				jQuery.each( editorElements.models, function( index, obj ) {

					jQuery.each( obj.attributes.elements.models, function( index, obj ) {

						jQuery.each( obj.attributes.elements.models, function( index, obj ) {
							if ( widgetId == obj.id ) {
								widgetData = obj.attributes.settings.attributes;
							}
						} );

					} );

				} );

				return {
					'tooltip': widgetData['eg_tooltip'] || 'no',
					'tooltipDescription': widgetData['eg_tooltip_description'] || '',
					'tooltipPlacement': widgetData['eg_tooltip_position'] || 'top',
					'xOffset': widgetData['eg_tooltip_x_offset'] || 0,
					'yOffset': widgetData['eg_tooltip_y_offset'] || 0,
					'tooltipAnimation': widgetData['eg_tooltip_animation'] || 'shift-toward',
					'zIndex': widgetData['eg_tooltip_z_index'] || '999',
					'tooltip_trigger': widgetData['eg_tooltip_trigger'] || 'mouseenter',
					'eg_sticky': widgetData['eg_sticky'] || '',
					'eg_sticky_on': widgetData['eg_sticky_on'] || [ 'desktop', 'tablet', 'mobile'],
					'eg_sticky_offset': widgetData['eg_sticky_offset'] || '0',
					'eg_sticky_effects_offset': widgetData['eg_sticky_effects_offset'] || '0',
					'eg_sticky_zindex': widgetData['eg_sticky_zindex'] || '10',
					'eg_sticky_parent': widgetData['eg_sticky_parent'] || false,
					'rellax_parallax': widgetData['rellax_parallax'] || '',
					'rellax_parallax_speed': widgetData['rellax_parallax_speed'] || { 'size': 0, 'unit': 'px'},
					'rellax_parallax_percentage': widgetData['rellax_parallax_percentage'] || { 'size': 0, 'unit': 'px'},
					'rellax_parallax_zindex': widgetData['rellax_parallax_zindex'] || 0,
					'ruvuv_heading': widgetData['ruvuv_heading'] || '',
					'ruvuv_heading_style': widgetData['ruvuv_heading_style'] || '',
					'gradient_colors': widgetData['gradient_colors'] || [],
					'gradient_color_rotate': widgetData['gradient_color_rotate'] || { 'size': 45, 'unit': 'px'},
				}
			}
		};

		var ruvuvTooltip = function( $scope ) {
			var self           = this,
				widgetId       = $scope.data('id'),
				widgetSelector = $scope[0],
				settings       = {},
				editMode       = Boolean( elementorFrontend.isEditMode() );

			self.init = function() {

				if ( ! editMode ) {
					settings = $scope.data( 'tooltip-settings' );
				} else {
					settings = RuvuvElementorExpandTools.widgetEditorSettings( widgetId );
				}

				if ( ! settings ) {
					return false;
				}

				if ( 'undefined' === typeof settings ) {
					return false;
				}

				if ( 'yes' !== settings['tooltip'] || 'undefined' === typeof settings['tooltip'] || '' === settings['tooltipDescription'] ) {
					return false;
				}

				$scope.addClass( 'ruvuv-tooltip-widget' );

				if ( widgetSelector._tippy ) {
					widgetSelector._tippy.destroy();
				}

				var tippyInstance = tippy(
					[ widgetSelector ],
					{
						html: document.querySelector( '#ruvuv-tooltip-content-' + widgetId ),
						appendTo: widgetSelector,
						arrow: true,
						placement: settings['tooltipPlacement'],
						flipBehavior: 'clockwise',
						trigger: settings['tooltip_trigger'],
						offset: settings['xOffset'] + ', ' + settings['yOffset'],
						animation: settings['tooltipAnimation'],
						zIndex: settings['zIndex']
					}
				);

				if ( editMode && widgetSelector._tippy ) {
					widgetSelector._tippy.show();
				}
			};
		};
	});

	jQuery(document).ready(function($) {

		jQuery('[data-ruvuv-section-link]').click(function() {

			var link = jQuery(this).data('ruvuv-section-link'),
				external = jQuery(this).data('ruvuv-section-link-external');

			if(external == 'on') {
				window.open( link , '_blank' );
			} else {
				window.location.href = link;
			}
		});
	});
}( jQuery, window.elementorFrontend ) );