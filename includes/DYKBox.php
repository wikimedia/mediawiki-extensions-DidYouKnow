<?php
/**
 * Created by JetBrains PhpStorm.
 * User: jeroen
 * Date: 5/6/12
 * Time: 6:53 PM
 * To change this template use File | Settings | File Templates.
 */

class DYKBox extends ContextSource {

	public function __construct( IContextSource $context = null ) {
		if ( !is_null( $context ) ) {
			$this->setContext( $context );
		}
	}

	public function getHTML() {
		$html = '';

		$html .= $this->msg( 'didyouknow-header' )->escaped();

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

}