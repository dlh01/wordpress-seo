<?php

namespace Yoast\WP\Free\Tests;

use WPSEO_Options;
use Brain\Monkey;
use Mockery;
use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * TestCase base class.
 */
abstract class TestCase extends BaseTestCase {

	protected $mocked_options = [ 'wpseo', 'wpseo_titles', 'wpseo_taxonomy_meta', 'wpseo_social', 'wpseo_ms' ];

	protected function setUp() {
		parent::setUp();
		Monkey\setUp();

		Monkey\Functions\stubs(
			[
				// Null makes it so the function returns it's first argument.
				'esc_attr'       => null,
				'esc_html'       => null,
				'esc_textarea'   => null,
				'__'             => null,
				'_x'             => null,
				'esc_html__'     => null,
				'esc_html_x'     => null,
				'esc_attr_x'     => null,
				'is_admin'       => false,
				'is_multisite'   => false,
				'wp_kses_post'   => null,
				'site_url'       => 'https://www.example.org',
				'wp_json_encode' => function( $data, $options = 0, $depth = 512 ) {
					return \json_encode( $data, $options, $depth );
				},
				'wp_slash'       => null,
				'absint'         => function( $value ) {
					return \abs( \intval( $value ) );
				},
				'mysql2date'     => null,
				'wp_parse_args' => function( $settings, $defaults ) {
					return \array_merge( $defaults, $settings );
				},
			]
		);

		// This is required to ensure backfill and other statics are set.
		WPSEO_Options::get_instance();

		Monkey\Functions\expect( 'get_option' )
			->zeroOrMoreTimes()
			->with( call_user_func_array( 'Mockery::anyOf', $this->mocked_options ) )
			->andReturn( [] );

		Monkey\Functions\expect( 'get_site_option' )
			->zeroOrMoreTimes()
			->with( call_user_func_array( 'Mockery::anyOf', $this->mocked_options ) )
			->andReturn( [] );
	}

	protected function tearDown() {
		Monkey\tearDown();
		parent::tearDown();
	}

	/**
	 * Tests for expected output.
	 *
	 * @param string $expected    Expected output.
	 * @param string $description Explanation why this result is expected.
	 */
	protected function expectOutput( $expected, $description = '' ) {
		$output = \ob_get_contents();
		\ob_clean();

		$output   = \preg_replace( '|\R|', "\r\n", $output );
		$expected = \preg_replace( '|\R|', "\r\n", $expected );

		$this->assertEquals( $expected, $output, $description );
	}

	/**
	 * Tests if the output buffer contains the provided strings.
	 *
	 * @param string|array $expected Expected output.
	 */
	protected function expectOutputContains( $expected ) {
		$output = \preg_replace( '|\R|', "\r\n", \ob_get_contents() );
		\ob_clean();

		if ( ! \is_array( $expected ) ) {
			$expected = array( $expected );
		}

		foreach ( $expected as $needle ) {
			$found = \strpos( $output, $needle );
			$this->assertTrue( $found !== false, \sprintf( 'Expected "%s" to be found in "%s" but couldn\'t find it.', $needle, $output ) );
		}
	}

	/**
	 * Tests if the output buffer doesn't contain the provided strings.
	 *
	 * @param string|array $needles Expected output.
	 */
	protected function expectOutputNotContains( $needles ) {
		$output = \preg_replace( '|\R|', "\r\n", \ob_get_contents() );
		\ob_clean();

		if ( ! \is_array( $needles ) ) {
			$needles = array( $needles );
		}

		foreach ( $needles as $needle ) {
			$found = \strpos( $output, $needle );
			$this->assertTrue( $found === false, \sprintf( 'Expected "%s" to be found in "%s" but couldn\'t find it.', $needle, $output ) );
		}
	}
}
