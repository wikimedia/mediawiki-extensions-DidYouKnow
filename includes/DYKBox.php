<?php

use MediaWiki\MediaWikiServices;

/**
 * Class representing a did you know box.
 *
 * @since 0.1
 *
 * @file
 * @ingroup DYK
 *
 * @license GPL-2.0-or-later
 * @author Jeroen De Dauw < jeroendedauw@gmail.com >
 */
class DYKBox extends ContextSource {

	/** @var string */
	protected $mainCategory;
	/** @var bool|string */
	protected $specificCategory;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param string $mainCategory
	 * @param string|bool $specificCategory
	 * @param IContextSource|null $context
	 */
	public function __construct( $mainCategory, $specificCategory = false, IContextSource $context = null ) {
		if ( $context !== null ) {
			$this->setContext( $context );
		}

		$this->mainCategory = $mainCategory;
		$this->specificCategory = $specificCategory;
	}

	/**
	 * Returns the HTML for the did you know box.
	 *
	 * @since 0.1
	 *
	 * @return string
	 */
	public function getHTML() {
		$html = '<h4 class="didyouknow-header">';
		$html .= $this->msg( 'didyouknow-header' )->escaped();
		$html .= '</h4>';

		$title = $this->getArticleTitle();

		if ( $title === false ) {
			return '';
		} else {
			$html .= $this->getOutput()->parseAsContent( $this->getArticleContent( $title ) );
		}

		$html = Html::rawElement(
			'div',
			[ 'class' => 'didyouknow' ],
			$html
		);

		return $html;
	}

	/**
	 * Returns the resource modules needed by the did you know box.
	 *
	 * @since 0.1
	 *
	 * @return array
	 */
	public static function getModules() {
		return [
			'ext.dyk'
		];
	}

	/**
	 * Displays the did you know box.
	 *
	 * @since 0.1
	 */
	public function display() {
		$this->getOutput()->addHTML( $this->getHTML() );
		$this->getOutput()->addModules( self::getModules() );
	}

	/**
	 * Returns the content for the article with provided title.
	 *
	 * @since 0.1
	 *
	 * @param Title $title
	 *
	 * @return string
	 */
	protected function getArticleContent( Title $title ) {
		if ( method_exists( MediaWikiServices::class, 'getWikiPageFactory' ) ) {
			// MediaWiki 1.36+
			$wikiPage = MediaWikiServices::getInstance()->getWikiPageFactory()->newFromID( $title->getArticleID() );
		} else {
			$wikiPage = WikiPage::newFromID( $title->getArticleID() );
		}

		if ( $wikiPage === null ) {
			return '';
		}

		$content = $wikiPage->getContent();

		if ( $content === null || !( $content instanceof TextContent ) ) {
			return '';
		}

		return $content->getText();
	}

	/**
	 * Returns the title for the article to get content from or false if there is none.
	 *
	 * @since 0.1
	 *
	 * @return Title|bool
	 */
	protected function getArticleTitle() {
		$pageName = false;

		if ( $this->specificCategory !== false ) {
			$pageName = $this->getPageFromCategory( $this->specificCategory );
		}

		if ( $pageName === false ) {
			$pageName = $this->getPageFromCategory( $this->mainCategory );
		}

		return $pageName === false ? false : Title::newFromText( $pageName );
	}

	/**
	 * Gets a random page from a category.
	 * Note that the random function becomes inefficient for large result sets,
	 * so this should only be used for small categories.
	 *
	 * NOTE: this is only usable for small categories since the query becomes
	 * expensive for big categories. So do not use for categories with potentially
	 * hundreds of pages or more.
	 *
	 * @since 0.1
	 *
	 * @param string $categoryName
	 *
	 * @return string|bool
	 */
	protected function getPageFromCategory( $categoryName ) {
		$dbr = MediaWikiServices::getInstance()->getDBLoadBalancer()->getConnection( DB_REPLICA );
		$res = $dbr->selectRow(
			[ 'page', 'categorylinks' ],
			[ 'page_namespace', 'page_title' ],
			[
				'cl_from=page_id',
				'cl_to' => Title::newFromText( $categoryName, NS_CATEGORY )->getDBkey()
			],
			__METHOD__,
			[ 'ORDER BY' => 'RAND()' ]
		);

		if ( $res !== false ) {
			$contentLanguage = MediaWikiServices::getInstance()->getContentLanguage();
			$res = $contentLanguage->getNsText( $res->page_namespace ) . ':' . $res->page_title;
		}

		return $res;
	}

}
