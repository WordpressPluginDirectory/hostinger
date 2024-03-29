<?php

namespace Hostinger\Admin\Onboarding\Steps;

use Hostinger\Settings;

defined( 'ABSPATH' ) || exit;

class AddDescription extends OnboardingStep {
	private string $website_type;

	public function __construct() {
		$this->website_type = Settings::get_setting( 'survey.website.type' );
	}

	public function get_title(): string {
		return __( 'Edit post description', 'hostinger' );
	}

	public function get_body(): array {
		return array(
			array(
				'title'       => __( 'Go to Posts', 'hostinger' ),
				'description' => __( 'In the left sidebar, find the Posts button. Click on the All Posts button and find the post for which you want to change the description.', 'hostinger' ),
			),
			array(
				'title'       => __( 'Edit post', 'hostinger' ),
				'description' => __( 'Hover over the chosen post to see the options menu. Click on the Edit button to open the post editor.', 'hostinger' ),
			),
			array(
				'title'       => __( 'Edit description', 'hostinger' ),
				'description' => __( 'You can see the whole post in the editor. Find the description part and change it to your preferences.', 'hostinger' ),
			),
		);
	}

	public function step_identifier(): string {
		return 'edit_description';
	}

	public function get_redirect_link(): string {
		return admin_url( 'edit.php' );
	}

	public function button_text(): string {
		return __( 'Take me there', 'hostinger' );
	}
}
