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

	public function getHTML() {
		$html = '';

		$html .= $this->msg( 'didyouknow-header' )->escaped();

		$title = $this->getArticleTitle();

		if ( $title === false ) {
			return 'TODO'; // TODO
		}
		else {
			$html .= $this->getArticleContent( $title );
		}

		$html = Html::rawElement(
			'div',
			array( 'class' => 'didyouknow' ),
			$html
		);

		return $html;
	}

	public function getModules() {
		return array(
			'ext.dyk'
		);
	}

	public function display() {
		$this->getOutput()->addHTML( $this->getHTML() );
		$this->getOutput()->addModules( $this->getModules() );
	}

	/**
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
	 * @return Title|false
	 */
	protected function getArticleTitle() {
		$titles = array();

		if ( $this->specificCategory !== false ) {
			$titles = $this->getArticlesInCategory( $this->specificCategory );
		}

		if ( empty( $titles ) ) {
			$titles = $this->getArticlesInCategory( $this->mainCategory );
		}

		return empty( $titles ) ? false : $titles[array_rand( $titles )];
	}

	protected function getArticlesInCategory( $categoryName ) {

	}

}