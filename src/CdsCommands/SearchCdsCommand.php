<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace Cds\CdsCommands;


use TopFloor\Cds\CdsService;

class SearchCdsCommand extends CdsCommand {
	public function setDependencies() {
		$dependencies = $this->getDependencies();

		$host = htmlspecialchars($this->service->getHost());
		$urlHandler = $this->service->getUrlHandler();

		$productUrlTemplate = $urlHandler->construct(array(
			'page' => 'product',
			'id' => '%PRODUCT%',
			'cid' => '%CATEGORY%',
		));

		$searchUrlTemplate = $urlHandler->construct(array(
			'page' => 'search',
			'cid' => '%CATEGORY%',
		));

		$dependencies->js('http://' . $host . '/catalog3/js/cds-faceted-search2.js');

		$dependencies->setting('Keys', array(
			'productUrlTemplate' => $productUrlTemplate,
			'searchUrlTemplate' => $searchUrlTemplate,
			'containerId' => 'cds-keys-result',
			'attributeLabel' => 'Attribute',
			'valueLabel' => 'Value',
		));
	}

	public function execute($parameters = array()) {
		$default = array(
			'categoryId' => 'root',
			'displayPowerGrid' => true,
			'renderProductsListType' => 'list',
			'showUnitToggle' => false,
			'appendUnitToProductUrl' => true,
			'loadProducts' => '', // TODO: Write a class that loads products
		);

		$parameters += $default;

		$output = '';

		$output .= 'TopFloor.Cds.Search.initialize(' . json_encode($parameters) . ');' . "\n";

		return $output;
	}
}