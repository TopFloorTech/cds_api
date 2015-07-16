<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace Cds\CdsCommands;


use TopFloor\Cds\CdsService;

class CompareCdsCommand extends CdsCommand {
	public function setDependencies() {
		$dependencies = $this->getDependencies();

		$urlHandler = $this->service->getUrlHandler();

		$productUrlTemplate = $urlHandler->construct(array(
			'page' => 'product',
			'id' => '%PRODUCT%',
			'cid' => '%CATEGORY%'
		));

		$dependencies->setting('Compare', array(
			'productUrlTemplate' => $productUrlTemplate,
			'containerId' => 'cds-product-compare-container',
		));
	}

	public function execute($parameters = array()) {
		$output = '';

		$output .= 'TopFloor.Cds.Compare.initialize();';

		return $output;
	}
}