<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-pkae-elementor-templates-modal__header">
	<div id="pkae-elementor-template-library-header" class="elementor-templates-modal__header">
		<div class="elementor-templates-modal__header__logo-area">
			<div class="elementor-templates-modal__header__logo">
				<span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper">
					<i class="eicon-elementor"></i>
				</span>
				<span class="elementor-templates-modal__header__logo__title">Powerkit Addons</span>
			</div>
		</div>

		<div class="pkae-header-buttons">
			<button type="button" class="pkae-tab-btn pkae-tab-active" data-type="all">ALL</button>
			<button type="button" class="pkae-tab-btn" data-type="template">Template</button>
			<button type="button" class="pkae-tab-btn" data-type="section">Section</button>
		</div>

		<div class="elementor-templates-modal__header__items-area">
			<div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item">
				<i class="eicon-close" aria-hidden="true" title="Close"></i>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="tmpl-pkae-elementor-template-library-loading">
	<div id="pkae-elementor-template-library-loading">
		<div class="elementor-loader-wrapper">
			<div class="elementor-loader">
				<div class="elementor-loader-boxes">
					<div class="elementor-loader-box"></div>
					<div class="elementor-loader-box"></div>
					<div class="elementor-loader-box"></div>
					<div class="elementor-loader-box"></div>
				</div>
			</div>
			<div class="elementor-loading-title"><?php esc_html__( 'Loading', 'powerkit-addons-for-elementor' ); ?></div>
		</div>
	</div>
</script>
<!-- Tools / Filter Template -->
<script type="text/template" id="tmpl-pkae-elementor-template-library-tools">
	<div id="pkae-elementor-template-library-toolbar">
		<div id="pkae-elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar">				
			<div id="elementor-template-library-filter">
				<select id="pkae-elementor-template-library-filter-theme" class="elementor-template-library-filter-select" name="theme">
					<!-- JS will populate this -->
				</select>
			</div>
			<div class="pkae-search-wrapper">
				<input type="text" id="pkae-template-search" placeholder="SEARCH TEMPLATE" />
				<i class="eicon-search"></i>
			</div>
		</div>
	</div>
</script>






