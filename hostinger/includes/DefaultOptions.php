<?php

namespace Hostinger;

use Hostinger\Admin\Options\PluginOptions;
use Hostinger\Helper;

defined( 'ABSPATH' ) || exit;

class DefaultOptions {
	/**
	 * @return void
	 */
	public function add_options(): void {
		$this->install_bypass_code();
		$this->disable_authentication_password();

		foreach ( $this->options() as $key => $option ) {
			update_option( $key, $option );
		}
	}

    public function disable_authentication_password(): void {
        global $wpdb;

        // Check if any application passwords exist
        $existing_passwords = (int)$wpdb->get_var( $wpdb->prepare( "SELECT COUNT(*) FROM {$wpdb->usermeta} WHERE meta_key = %s", '_application_passwords' ) );

        if ( $existing_passwords === 0 ) {
            $options = array(
                'disable_authentication_password' => true,
            );

            $plugin_options = new PluginOptions( $options );
            update_option( HOSTINGER_PLUGIN_SETTINGS_OPTION, $plugin_options->to_array(), false );
        }
    }

	/**
	 * @return void
	 */
	public function install_bypass_code(): void {
		// Generate initial bypass code when plugin is first installed.
		$hostinger_plugin_settings = get_option( HOSTINGER_PLUGIN_SETTINGS_OPTION, false );

		if ( $hostinger_plugin_settings === false ) {
			$options = array(
				'bypass_code' => Helper::generate_bypass_code( 16 ),
			);

			$plugin_options = new PluginOptions( $options );

			update_option( HOSTINGER_PLUGIN_SETTINGS_OPTION, $plugin_options->to_array(), false );
		}
	}

	/**
	 * @return string[]
	 */
	private function options(): array {
		$options = array(
            'optin_monster_api_activation_redirect_disabled' => 'true',
            'wpforms_activation_redirect'                    => 'true',
            'aioseo_activation_redirect'                     => 'false',
		);

		if ( Helper::is_plugin_active( 'astra-sites' ) ) {
			$options = array_merge( $options, $this->get_astra_options() );
		}

		return $options;
	}

	/**
	 * @return string[]
	 */
	private function get_astra_options(): array {
		return array(
			'astra_sites_settings' => 'gutenberg',
		);
	}
}
