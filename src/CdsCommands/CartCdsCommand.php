<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace Cds\CdsCommands;


use TopFloor\Cds\CdsService;

class CartCdsCommand extends CdsCommand {
	public function setDependencies() {
		$dependencies = $this->getDependencies();

		$dependencies->setting('Cart', array(
			'containerId' => 'cds-cart-container',
		));
	}

	public function execute($parameters = array()) {
		$output = '';

		$output .= 'TopFloor.Cds.Cart.initialize();';

		return $output;
	}
}