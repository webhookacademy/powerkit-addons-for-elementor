const initialEPKA = window.epka = window.epka || {};
var EPKAStore = null;

(function( $ ) {
	const elementor_add_section_tmpl = $( "#tmpl-elementor-add-section" );

	if (0 < elementor_add_section_tmpl.length && typeof elementor !== undefined) {
		let text = elementor_add_section_tmpl.text();
		(text = text.replace(
			'<div class="elementor-add-section-drag-title',
			'<div class="elementor-add-section-area-button elementor-add-epka-templates-button" title="PowerKit Library"> <i class="eicon-folder"></i> </div> <div class="elementor-add-section-drag-title'
		)),

		elementor_add_section_tmpl.text(text),
		elementor.on( "preview:loaded", function() {
			$( elementor.$previewContents[0].body).on(
				"click",
				".elementor-add-epka-templates-button",
				openLibrary
			);
		});

		function showLoadingView() {
			if (initialEPKA.epkaModal) {
				initialEPKA.epkaModal.getElements("loading").show();
				initialEPKA.epkaModal.getElements("content").hide();
			}
		}

		function hideLoadingView() {
			if (initialEPKA.epkaModal) {
				initialEPKA.epkaModal.getElements("loading").hide();
				initialEPKA.epkaModal.getElements("content").show();
			}
		}
		function openLibrary() {
			const insertIndex = 0 < jQuery(this).parents(".elementor-section-wrap").length ? jQuery(this).parents(".elementor-add-section").index() : -1;
			initialEPKA.insertIndex = insertIndex;

			elementorCommon &&
				( initialEPKA.epkaModal ||
					( ( initialEPKA.epkaModal = elementorCommon.dialogsManager.createWidget(
						"lightbox",
						{
							id: "epka-elementor-template-library-modal",
							className: "elementor-templates-modal",
							message: "",
							hide: {
								auto: !1,
								onClick: !1,
								onOutsideClick: !1,
								onOutsideContextMenu: !1,
								onBackgroundClick: !0
							},
							position: {
								my: "center",
								at: "center"
							},
							onShow: function() {
								
								const header = initialEPKA.epkaModal.getElements("header");
								if( !$('#epka-elementor-template-library-header').length ) {
									header.append( wp.template( 'epka-elementor-templates-modal__header' ) () );
								}
								const content = initialEPKA.epkaModal.getElements("content");
								if( !$('#epka-elementor-template-library-filter-toolbar-remote').length ) {
									content.append( wp.template( 'epka-elementor-template-library-tools' ) () );
								}
								if( !$('#epka-elementor-templates-header').length ) {
									content.append('<div id="epka-elementor-templates-header" class="wrap"></div>');
								}
								if( !$('#epka_main_library_templates_panel').length ) {
									content.append('<div id="epka_main_library_templates_panel" class="epka__main-view"></div>');
								}
								if( 'dark' !== elementor.settings.editorPreferences.model.get('ui_theme') ) {
									$("#epka_main_library_templates_panel").removeClass('epka-dark-mode');
								}
								else {
									$("#epka_main_library_templates_panel").addClass('epka-dark-mode');
								}
								const loading = initialEPKA.epkaModal.getElements("loading");
								if( !$('#epka-elementor-template-library-loading').length ) {
									loading.append( wp.template( 'epka-elementor-template-library-loading' ) ());
								}
								
								var event = new Event("modal-close");
								$("#epka-elementor-templates").on(
									"click",
									".close-modal",
									function() {
										document.dispatchEvent(event);
										return initialEPKA.epkaModal.hide(), !1;
									}
								);
								$(".elementor-templates-modal__header__close").click( function() {
									return initialEPKA.epkaModal.hide(); 
								});
								epka_get_library_view();
								$('#epka-elementor-template-library-filter-theme').select2({
									placeholder: 'Theme',
									allowClear: true,
									width: 150,
								});
							},
							onHide: function() {
								if( 'dark' !== elementor.settings.editorPreferences.model.get('ui_theme') ) {
									$("#epka_main_library_templates_panel").removeClass('epka-dark-mode');
								}
								else {
									$("#epka_main_library_templates_panel").addClass('epka-dark-mode');
								}
							}
						}
					)),
					initialEPKA.epkaModal.getElements("message").append( initialEPKA.epkaModal.addElement("content"), initialEPKA.epkaModal.addElement('loading') )),
					initialEPKA.epkaModal.show() );
		}

		initialEPKA.epkaModal = null;

	}

	var EPKA_Update_Actions = function( insertIndex ){

		$('.epka-btn-template-insert, .elementor-template-library-template-action').unbind('click');
        $('.epka-btn-template-insert, .elementor-template-library-template-action').click(function(){
			var EPKA_Selected_Item = this;
            showLoadingView();
			var filename = $( EPKA_Selected_Item ).attr( "data-template-name" ) + ".json";
			console.log("File Name: ", filename);
			$.post( 
				ajaxurl, 
				{ action : 'get_content_from_powerkit_export_file', filename: filename }, 
				function(response) {
					if (!response.success || !response.data) {
						console.error('Template Load Failed:', response);
						elementor.templates.showErrorDialog('Template response invalid.');
						hideLoadingView();
						return;
					}

					const data = response.data;

					if (insertIndex == -1) {
						elementor.getPreviewView().addChildModel(data, { silent: 0 });
					} else {
						elementor.getPreviewView().addChildModel(data, { at: insertIndex, silent: 0 });
					}

					elementor.channels.data.trigger('template:after:insert', {});

					if (typeof $e !== 'undefined' && $e.internal) {
						$e.internal('document/save/set-is-modified', { status: true });
					} else {
						elementor.saver.setFlagEditorChange(true);
					}

					showLoadingView();
					initialEPKA.epkaModal.hide();
				}
			)
			.fail(function error(errorData) {
				console.error('Template Import Error:', errorData.responseText);
				elementor.templates.showErrorDialog(
					'The template couldn’t be imported. Details in console log.'
				);
				hideLoadingView();
			});

        });

		$('#epka-elementor-template-library-filter-theme').on( 'change', function(e) {
            var filters = {};
			$(this).each(function(index, select) {
				var value = String( $(select).val() );
				if (value.indexOf(',') !== -1) {
					value = value.split(',');
				}
				filters[$(select).attr('name')] = value;
			});
			$('.epka-item, h2.epka-templates-library-template-category').each(function(i, item) {
				var show = true;
				$.each(filters, function(name, val) {
					if ( val === null ) { return; }
					if ( name === 'theme' && $(item).data('theme').indexOf(val) === -1) {
						show = false;
					} else if( $(item).data(name).indexOf(val) === -1) {
						show = false;
					}
				});
				if (show) {
					$(item).show();
				}else{
					$(item).hide();
				}
			});
		});

        $('.epka-template-thumb').click( function() {
			var jsonData = $(this).attr('data-template');
			var data = jQuery.parseJSON( jsonData );
			var slug = data.id;
			$('.elementor-templates-modal__header__logo').hide();
			$('#epka-elementor-template-library-toolbar').hide();
			$('#epka-elementor-template-library-header-preview').show();
			$('#epka-elementor-template-library-header-preview').find('.elementor-template-library-template-action').attr( 'data-template-name', slug );
			$('.epka-header-back-button').show();
            showLoadingView();
            $.post( ajaxurl, { action : 'get_epka_preview', data: data}, function(data) {
				hideLoadingView();
				$('.epka__main-view').html( data );
            	EPKA_Update_Actions(insertIndex);
            });
        });

		$('.epka-header-back-button').click(function() {
			$(this).hide();
			$('#epka-elementor-template-library-header-preview').hide();
			$('#epka-elementor-template-library-toolbar').show();
			$('.elementor-templates-modal__header__logo').show();
			epka_get_library_view();
        });
		
    }

	function epka_get_library_view() {
        
		var filters = {};
        if( !insertIndex ) { var insertIndex = null; }

		$('.elementor-templates-modal__header__logo').show();
		$('#epka-elementor-template-library-toolbar').show();
		$('.epka-header-back-button').hide();
		$('#epka-elementor-template-library-header-preview').hide();		

		showLoadingView();
		if( EPKAStore == null ) { 

			$.post( ajaxurl, { action : 'get_epka_templates_library_view' }, function( data ) {

				hideLoadingView();
				$( '.epka__main-view').html( data );
				EPKAStore = data;
				EPKA_Update_Actions( insertIndex );
			});
		} else {
			hideLoadingView();
			$('.epka__main-view').html( EPKAStore );
			EPKA_Update_Actions( insertIndex );
		}

		var filterValue = $('#epka-elementor-template-library-filter-theme').val();
		if( filterValue ) {
			filters['theme'] = filterValue;
			$( '.epka-item, h2.epka-templates-library-template-category' ).each( function( i, item ) {
				var show = true;
				$.each( filters, function( name, val ) {
					if ( val === null ) { return; }
					if ( name === 'theme' && $(item).data('theme').indexOf(val) === -1 ) {
						show = false;
					} else if( $(item).data(name).indexOf(val) === -1) {
						show = false;
					}
				});
				if (show) {
					$(item).show();
				}else{
					$(item).hide();
				}
			});
		}

		var getTemplateBottomButton = $('#elementor-preview-iframe').contents().find('#elementor-add-new-section .elementor-add-template-button');
		if( getTemplateBottomButton.length && !getTemplateBottomButton.hasClass('EPKA_template_btn') ){
			getTemplateBottomButton.hover(function(){
				$(this).addClass('EPKA_template_btn');
				insertIndex = -1;
			});
		}

		var getTemplateInlineButtons = $('#elementor-preview-iframe').contents().find('.elementor-add-section-inline .elementor-add-template-button');
		if( getTemplateInlineButtons.length ){
			getTemplateInlineButtons.each(function(){
				if(!$(this).hasClass('EPKA_template_btn')){
					$(this).addClass('EPKA_template_btn');
				} else {
					$(this).unbind('hover');
					$(this).hover(function(){
						var templateContainer = $(this).parent().parent().parent(),
						allSections = $(this).parent().parent().parent().parent().children(),
						tempInsertIndex = [];
						for (let index = 0; index < allSections.length; index++) {
							if(allSections[index].localName != 'div' || allSections[index] == templateContainer[0]){
								tempInsertIndex.push(allSections[index]);
							}
						} 
						for (let index = 0; index < tempInsertIndex.length; index++) {
							if(tempInsertIndex[index] == templateContainer[0]){ insertIndex = index;  }
						} 
					});
				}
			});

		} 
	}


})(jQuery);
