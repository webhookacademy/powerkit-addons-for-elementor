const initialPKAE = window.pkae = window.pkae || {};
var PKAEStore = null;

(function($) {
    const elementor_add_section_tmpl = $("#tmpl-elementor-add-section");

    if (0 < elementor_add_section_tmpl.length && typeof elementor !== undefined) {
        let text = elementor_add_section_tmpl.text();
        (text = text.replace(
            '<div class="elementor-add-section-drag-title',
            '<div class="elementor-add-section-area-button elementor-add-pkae-templates-button" title="PowerKit Library"> <i class="eicon-folder"></i> </div> <div class="elementor-add-section-drag-title'
        )),

        elementor_add_section_tmpl.text(text),
            elementor.on("preview:loaded", function() {
                $(elementor.$previewContents[0].body).on(
                    "click",
                    ".elementor-add-pkae-templates-button",
                    openLibrary
                );
            });

        function showLoadingView() {
            if (initialPKAE.pkaeModal) {
                initialPKAE.pkaeModal.getElements("loading").show();
                initialPKAE.pkaeModal.getElements("content").hide();
            }
        }

        function hideLoadingView() {
            if (initialPKAE.pkaeModal) {
                initialPKAE.pkaeModal.getElements("loading").hide();
                initialPKAE.pkaeModal.getElements("content").show();
            }
        }

        function openLibrary() {
            const insertIndex = 0 < jQuery(this).parents(".elementor-section-wrap").length ? jQuery(this).parents(".elementor-add-section").index() : -1;
            initialPKAE.insertIndex = insertIndex;

            elementorCommon &&
                (initialPKAE.pkaeModal ||
                    ((initialPKAE.pkaeModal = elementorCommon.dialogsManager.createWidget(
                            "lightbox", {
                                id: "pkae-elementor-template-library-modal",
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

                                    const header = initialPKAE.pkaeModal.getElements("header");
                                    if (!$('#pkae-elementor-template-library-header').length) {
                                        header.append(wp.template('pkae-elementor-templates-modal__header')());
                                    }
                                    const content = initialPKAE.pkaeModal.getElements("content");
                                    if (!$('#pkae-elementor-template-library-filter-toolbar-remote').length) {
                                        content.append(wp.template('pkae-elementor-template-library-tools')());
                                    }
                                    if (!$('#pkae-elementor-templates-header').length) {
                                        content.append('<div id="pkae-elementor-templates-header" class="wrap"></div>');
                                    }
                                    if (!$('#pkae_main_library_templates_panel').length) {
                                        content.append('<div id="pkae_main_library_templates_panel" class="pkae__main-view"></div>');
                                    }
                                    if ('dark' !== elementor.settings.editorPreferences.model.get('ui_theme')) {
                                        $("#pkae_main_library_templates_panel").removeClass('pkae-dark-mode');
                                    } else {
                                        $("#pkae_main_library_templates_panel").addClass('pkae-dark-mode');
                                    }
                                    const loading = initialPKAE.pkaeModal.getElements("loading");
                                    if (!$('#pkae-elementor-template-library-loading').length) {
                                        loading.append(wp.template('pkae-elementor-template-library-loading')());
                                    }

                                    var event = new Event("modal-close");
                                    $("#pkae-elementor-templates").on(
                                        "click",
                                        ".close-modal",
                                        function() {
                                            document.dispatchEvent(event);
                                            return initialPKAE.pkaeModal.hide(), !1;
                                        }
                                    );
                                    $(".elementor-templates-modal__header__close").click(function() {
                                        return initialPKAE.pkaeModal.hide();
                                    });
                                    pkae_get_library_view();
                                    $('#pkae-elementor-template-library-filter-theme').select2({
                                        placeholder: 'Theme',
                                        allowClear: true,
                                        width: 150,
                                    });
                                },
                                onHide: function() {
                                    if ('dark' !== elementor.settings.editorPreferences.model.get('ui_theme')) {
                                        $("#pkae_main_library_templates_panel").removeClass('pkae-dark-mode');
                                    } else {
                                        $("#pkae_main_library_templates_panel").addClass('pkae-dark-mode');
                                    }
                                }
                            }
                        )),
                        initialPKAE.pkaeModal.getElements("message").append(initialPKAE.pkaeModal.addElement("content"), initialPKAE.pkaeModal.addElement('loading'))),
                    initialPKAE.pkaeModal.show());
        }

        initialPKAE.pkaeModal = null;

    }

    var PKAE_Update_Actions = function(insertIndex) {

        $('.pkae-btn-template-insert, .elementor-template-library-template-action').unbind('click');
        $('.pkae-btn-template-insert, .elementor-template-library-template-action').click(function() {
            var PKAE_Selected_Item = this;
            showLoadingView();
            var filename = $(PKAE_Selected_Item).attr("data-template-name") + ".json";
            console.log("File Name: ", filename);
            $.post(
                    ajaxurl, { action: 'get_content_from_powerkit_export_file', filename: filename, security: pkae_ajax.security },
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
                        initialPKAE.pkaeModal.hide();
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

        $('#pkae-elementor-template-library-filter-theme').on('change', function(e) {
            var filters = {};
            $(this).each(function(index, select) {
                var value = String($(select).val());
                if (value.indexOf(',') !== -1) {
                    value = value.split(',');
                }
                filters[$(select).attr('name')] = value;
            });
            $('.pkae-item, h2.pkae-templates-library-template-category').each(function(i, item) {
                var show = true;
                $.each(filters, function(name, val) {
                    if (val === null) { return; }
                    if (name === 'theme' && $(item).data('theme').indexOf(val) === -1) {
                        show = false;
                    } else if ($(item).data(name).indexOf(val) === -1) {
                        show = false;
                    }
                });
                if (show) {
                    $(item).show();
                } else {
                    $(item).hide();
                }
            });
        });

        $('.pkae-template-thumb').click(function() {
            var jsonData = $(this).attr('data-template');
            var data = jQuery.parseJSON(jsonData);
            var slug = data.id;
            $('.elementor-templates-modal__header__logo').hide();
            $('#pkae-elementor-template-library-toolbar').hide();
            $('#pkae-elementor-template-library-header-preview').show();
            $('#pkae-elementor-template-library-header-preview').find('.elementor-template-library-template-action').attr('data-template-name', slug);
            $('.pkae-header-back-button').show();
            showLoadingView();
            $.post(ajaxurl, { action: 'get_pkae_preview', data: data, security: pkae_ajax.security }, function(data) {
                hideLoadingView();
                $('.pkae__main-view').html(data);
                PKAE_Update_Actions(insertIndex);
            });
        });

        $('.pkae-header-back-button').click(function() {
            $(this).hide();
            $('#pkae-elementor-template-library-header-preview').hide();
            $('#pkae-elementor-template-library-toolbar').show();
            $('.elementor-templates-modal__header__logo').show();
            pkae_get_library_view();
        });

    }

    function pkae_get_library_view() {

        var filters = {};
        if (!insertIndex) { var insertIndex = null; }

        $('.elementor-templates-modal__header__logo').show();
        $('#pkae-elementor-template-library-toolbar').show();
        $('.pkae-header-back-button').hide();
        $('#pkae-elementor-template-library-header-preview').hide();

        showLoadingView();
        if (PKAEStore == null) {

            $.post(ajaxurl, { action: 'get_pkae_templates_library_view' }, function(data) {

                hideLoadingView();
                $('.pkae__main-view').html(data);
                PKAEStore = data;
                PKAE_Update_Actions(insertIndex);
            });
        } else {
            hideLoadingView();
            $('.pkae__main-view').html(PKAEStore);
            PKAE_Update_Actions(insertIndex);
        }

        var filterValue = $('#pkae-elementor-template-library-filter-theme').val();
        if (filterValue) {
            filters['theme'] = filterValue;
            $('.pkae-item, h2.pkae-templates-library-template-category').each(function(i, item) {
                var show = true;
                $.each(filters, function(name, val) {
                    if (val === null) { return; }
                    if (name === 'theme' && $(item).data('theme').indexOf(val) === -1) {
                        show = false;
                    } else if ($(item).data(name).indexOf(val) === -1) {
                        show = false;
                    }
                });
                if (show) {
                    $(item).show();
                } else {
                    $(item).hide();
                }
            });
        }

        var getTemplateBottomButton = $('#elementor-preview-iframe').contents().find('#elementor-add-new-section .elementor-add-template-button');
        if (getTemplateBottomButton.length && !getTemplateBottomButton.hasClass('PKAE_template_btn')) {
            getTemplateBottomButton.hover(function() {
                $(this).addClass('PKAE_template_btn');
                insertIndex = -1;
            });
        }

        var getTemplateInlineButtons = $('#elementor-preview-iframe').contents().find('.elementor-add-section-inline .elementor-add-template-button');
        if (getTemplateInlineButtons.length) {
            getTemplateInlineButtons.each(function() {
                if (!$(this).hasClass('PKAE_template_btn')) {
                    $(this).addClass('PKAE_template_btn');
                } else {
                    $(this).unbind('hover');
                    $(this).hover(function() {
                        var templateContainer = $(this).parent().parent().parent(),
                            allSections = $(this).parent().parent().parent().parent().children(),
                            tempInsertIndex = [];
                        for (let index = 0; index < allSections.length; index++) {
                            if (allSections[index].localName != 'div' || allSections[index] == templateContainer[0]) {
                                tempInsertIndex.push(allSections[index]);
                            }
                        }
                        for (let index = 0; index < tempInsertIndex.length; index++) {
                            if (tempInsertIndex[index] == templateContainer[0]) { insertIndex = index; }
                        }
                    });
                }
            });

        }
    }


})(jQuery);