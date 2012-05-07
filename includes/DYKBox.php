<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jeroen
 * Date: 5/6/12
 * Time: 6:53 PM
 * To change this template use File | Settings | File Templates.
 */

class DYKBox extends ContextSource {

	protected $mainCategory;
	protected $specificCategory;

	/**
	 * Constructor.
	 *
	 * @since 0.1
	 *
	 * @param string $mainCategory
	 * @param string|false $specificCategory
	 * @param IContextSource|null $context
	 */
	public function __construct( $mainCategory, $specificCategory = false, IContextSource $context = null ) {
		if ( !is_null( $context ) ) {
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
		$html = '';

		$html .= '<h4 class="didyouknow-header">';
		$html .= $this->msg( 'didyouknow-header' )->escaped();
		$html .= '</h4>';

		$title = $this->getArticleTitle();

		if ( $title === false ) {
			return '';
		}
		else {
			$html .= $this->getOutput()->parse( $this->getArticleContent( $title ) );
		}

		$html = Html::rawElement(
			'div',
			array( 'class' => 'didyouknow' ),
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
	public function getModules() {
		return array(
			'ext.dyk'
		);
	}

	/**
	 * Displays the did you know box.
	 *
	 * @since 0.1
	 */
	public function display() {
		$this->getOutput()->addHTML( $this->getHTML() );
		$this->getOutput()->addModules( $this->getModules() );
	}

	/**
	 * Returns the content for the article with provided title.
	 *
	 * @param Title $title
	 *
	 * @return string
	 */
	protected function getArticleContent( Title $title ) {
		$article = new Article( $title, 0 );
		$content = $article->fetchContent();
		return is_string( $content ) ? $content : '';
	}

	/**
	 * Returns the title for the article to get content from or false if there is none.
	 *
	 * @return Title|false
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
	 * @param string$categoryName
	 *
	 * @return string|false
	 */
	protected function getPageFromCategory( $categoryName ) {
		global $wgContLang;

		$dbr = wfGetDB( DB_SLAVE );
		$res = $dbr->selectRow(
			array( 'page', 'categorylinks' ),
			array( 'page_namespace', 'page_title' ),
			array(
				'cl_from=page_id',
				'cl_to' => Title::newFromText( $categoryName, NS_CATEGORY )->getDBkey()
			),
			__METHOD__,
			array( 'ORDER BY' => 'RAND()' )
		);

		if ( $res !== false ) {
			$res = $wgContLang->getNsText( $res->page_namespace ) . ':' . $res->page_title;
		}

		return $res;
	}

}