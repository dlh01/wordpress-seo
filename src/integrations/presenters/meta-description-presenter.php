<?php
/**
 * Author watcher to save the meta data to an Indexable.
 *
 * @package Yoast\YoastSEO\Presenters
 */

namespace Yoast\WP\Free\Integrations\Presenters;

use Yoast\WP\Free\Conditionals\Indexables_Feature_Flag_Conditional;
use Yoast\WP\Free\Conditionals\Simple_Page_Conditional;
use Yoast\WP\Free\WordPress\Integration;

class Meta_Description_Presenter implements Integration {

	/**
	 * Returns the conditionals based in which this loadable should be active.
	 *
	 * @return array
	 */
	public static function get_conditionals() {
		return [ Indexables_Feature_Flag_Conditional::class, Simple_Page_Conditional::class ];
	}

	/**
	 * Initializes the integration.
	 *
	 * This is the place to register hooks and filters.
	 *
	 * @return void
	 */
	public function register_hooks() {
		\remove_action( 'wpseo_head', [ \WPSEO_Frontend::get_instance(), 'metadesc' ], 6 );
		\add_action( 'wpseo_head', [ $this, 'present' ], 6 );
	}

	public function present() {

	}
}
