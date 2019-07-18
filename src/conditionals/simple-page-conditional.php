<?php
/**
 * Yoast SEO plugin file.
 *
 * @package Yoast\YoastSEO\Conditionals
 */

namespace Yoast\WP\Free\Conditionals;

/**
 * Conditional that is only met when on a simple page in the frontend.
 */
class Simple_Page_Conditional implements Conditional {
	/**
	 * @inheritdoc
	 */
	public function is_met() {
		return ( $this->get_simple_page_id() > 0 );
	}

	/**
	 * Returns the id of the currently opened page.
	 *
	 * @return int The id of the currently opened page.
	 */
	public function get_simple_page_id() {
		if ( \is_singular() ) {
			return \get_the_ID();
		}

		if ( ( is_home() && \get_option( 'show_on_front' ) === 'page' ) ) {
			return \get_option( 'page_for_posts' );
		}

		/**
		 * Filter: Allow changing the default page id.
		 *
		 * @api int $page_id The default page id.
		 */
		return apply_filters( 'wpseo_frontend_page_type_simple_page_id', 0 );
	}
}
