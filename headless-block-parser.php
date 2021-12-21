<?php

/**
 * Plugin Name: BYOB Headless Block Parser
 * Description: Custom Gutenberg block parser that replaces internal link URL domains with that of the decoupled frontend JS app.
 * Version:     0.1.2
 * Author:      Kellen Mace, Amor Kumar
 * Author URI:  https://kellenmace.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Custom block parser for headless WordPress sites.
 */
class Headless_Block_Parser extends WP_Block_Parser {

	/**
	 * Parses a document and returns a list of block structures
	 *
	 * When encountering an invalid parse will return a best-effort
	 * parse. In contrast to the specification parser this does not
	 * return an error on invalid inputs.
	 *
	 * @param string $document Input document being parsed.
	 *
	 * @return WP_Block_Parser_Block[]
	 */
	public function parse( $document ): array {
		$is_graphql_request = function_exists( 'is_graphql_request' ) && is_graphql_request();
		$is_rest_request    = defined( 'REST_REQUEST' );

		// Don't modify the document if this is not a GraphQL or REST API request.
		if ( ! $is_graphql_request && ! $is_rest_request ) {
			return parent::parse( $document );
		}

		$document_with_replacements = $this->replace_internal_link_url_domains( $document );

		return parent::parse( $document_with_replacements );
	}

	/**
	 * Rewrite internal link URLs to point to the decoupled frontend app.
	 *
	 * @param string $document Input document being parsed.
	 *
	 * @return string $document Input document with internal link URL domains replaced.
	 */
	private function replace_internal_link_url_domains( string $document ): string {
		$site_url         = site_url();
		$slashed_site_url = addcslashes( site_url(), '/' );

		$document_with_replacements = str_replace( 'href="' . $site_url, 'data-internal-link="true" href="' . FRONTEND_APP_URL, $document );
		$document_with_replacements = str_replace( '"url": "' . $slashed_site_url, '"url": "' . FRONTEND_APP_URL, $document_with_replacements );

		return $document_with_replacements;
	}
}

/**
 * Register a custom Gutenberg block parser.
 *
 * @return string Name of block parser class.
 */
add_filter( 'block_parser_class', fn (): string => 'Headless_Block_Parser' );
