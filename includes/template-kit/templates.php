<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-epka-elementor-templates-modal__header">
	<div id="epka-elementor-template-library-header" class="elementor-templates-modal__header">
		<div class="elementor-templates-modal__header__logo-area">
			<div class="elementor-templates-modal__header__logo">
				<span class="elementor-templates-modal__header__logo__icon-wrapper e-logo-wrapper">
					<i class="eicon-elementor"></i>
				</span>
				<span class="elementor-templates-modal__header__logo__title">Powerkit Addons</span>
			</div>
		</div>

		<div class="epka-header-buttons">
			<button type="button" class="epka-tab-btn epka-tab-active" data-type="all">ALL</button>
			<button type="button" class="epka-tab-btn" data-type="template">Template</button>
			<button type="button" class="epka-tab-btn" data-type="section">Section</button>
		</div>

		<div class="elementor-templates-modal__header__items-area">
			<div class="elementor-templates-modal__header__close elementor-templates-modal__header__close--normal elementor-templates-modal__header__item">
				<i class="eicon-close" aria-hidden="true" title="Close"></i>
			</div>
		</div>
	</div>
</script>
<script type="text/template" id="tmpl-epka-elementor-template-library-loading">
	<div id="epka-elementor-template-library-loading">
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
<script type="text/template" id="tmpl-epka-elementor-template-library-tools">
	<div id="epka-elementor-template-library-toolbar">
		<div id="epka-elementor-template-library-filter-toolbar-remote" class="elementor-template-library-filter-toolbar">				
			<div id="elementor-template-library-filter">
				<select id="epka-elementor-template-library-filter-theme" class="elementor-template-library-filter-select" name="theme">
					<!-- JS will populate this -->
				</select>
			</div>
			<div class="epka-search-wrapper">
				<input type="text" id="epka-template-search" placeholder="SEARCH TEMPLATE" />
				<i class="eicon-search"></i>
			</div>
		</div>
	</div>
</script>






