<?php
/**
 * Register all core functions for the plugin.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / includes
 */

/**
 * Register all core functions for the plugin.
 *
 * @since 1.3.0
 *
 * @package    ShareWhere
 * @subpackage ShareWhere / includes
 */

/**
 * Returns suffix based on SCRIPT_DEBUG constant.
 *
 * @since 1.3.0
 */
function sw_get_script_style_suffix() {

	$suffix = ( defined( 'SCRIPT_DEBUG' ) && true === constant( 'SCRIPT_DEBUG' ) ) ? '' : '.min';

	return $suffix;
}


/**
 * Returns google map api key if exists else false.
 *
 * @since 1.3.0
 *
 * @param array $options ShareWhere options.
 *
 * @return stirng|bool
 */
function sw_get_google_map_api_key( $options ) {

	if ( ! empty( $options['google_map_api_key'] ) ) {
		return $options['google_map_api_key'];
	}

	return false;
}

/**
 * Check if google map is enabled for respected component, i.e., WordPress posts, comments, etc.
 *
 * @since 1.3.0
 *
 * @param string $component Component.
 * @param array  $options   ShareWhere options.
 *
 * @return bool
 */
function sw_check_google_map_enable( $component, $options ) {

	if ( empty( $options['google_map_api_key'] ) ) {
		return false;
	}

	if ( ! empty( $options['google_map_components'] ) && true === in_array( $component, $options['google_map_components'], true ) ) {
		return true;
	}

	return false;
}
