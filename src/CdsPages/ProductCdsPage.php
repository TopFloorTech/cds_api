<?php
/**
 * Created by PhpStorm.
 * User: Ben
 * Date: 7/15/2015
 * Time: 3:58 PM
 */

namespace TopFloor\Cds\CdsPages;

use TopFloor\Cds\CdsComponents\CadRequesterCdsComponent;
use TopFloor\Cds\CdsComponents\ConfigurableAttributesCdsComponent;
use TopFloor\Cds\CdsComponents\ProductAttachmentsCdsComponent;
use TopFloor\Cds\CdsComponents\ProductAttributesCdsComponent;
use TopFloor\Cds\CdsComponents\ProductCartCdsComponent;
use TopFloor\Cds\CdsComponents\ProductImagesCdsComponent;
use TopFloor\Cds\CdsComponents\SpecSheetCdsComponent;
use TopFloor\Cds\CdsComponents\UnitToggleCdsComponent;
use TopFloor\Cds\Helpers\ProductAttributesHelper;

class ProductCdsPage extends CdsPage {
	/** @var  ProductAttributesHelper */
	protected $attributes;

	public function initialize() {
		$urlHandler = $this->service->getUrlHandler();

		$product = $urlHandler->get('id');
		$category = $urlHandler->get('cid');

		if ($category == 'product') {
			// 'product' is a reserved prefix
			$category = null;
		}

		$productInfo = $this->service->getProductInfo()->productInfo($product, $category);

		$this->attributes = new ProductAttributesHelper($productInfo);

		if (is_null($category) && !empty($productInfo['category'])) {
			$category = $productInfo['category']['id'];
		}

		$title = (!empty($productInfo['description'])) ? $productInfo['description'] : $productInfo['label'];

		$this->pageVars = array(
			'productIconsClass' => 'cds-product-icons',
			'productPrintIconHref' => 'javascript:window.print();',
			'productPrintIconSrc' => '//' . $this->service->getHost() . '/catalog3/images/print_page.png',
			'productEmailIconHref' => 'javascript:cds.emailPage();',
			'productEmailIconSrc' => '//' . $this->service->getHost() . '/catalog3/images/email_go.png',
			'customHeaderClass' => 'cds-product-custom-header',
			'customHeader' => $productInfo['headerHTML'],
			'productHeaderClass' => 'cds-product-header',
			'imageContainerId' => 'cds-product-image-container',
			'imageContainerClass' => 'cds-product-image-container',
			'productImageId' => 'cds-product-image',
			'productImageClass' => 'cds-product-image',
			'productImageSrc' => isset($productInfo['imageURL']) ? $productInfo['imageURL'] : null,
			'productImageAlt' => isset($productInfo['imageAlt']) ? $productInfo['imageAlt'] : null,
			'productImageTitle' => isset($productInfo['imageTitle']) ? $productInfo['imageTitle'] : null,
			'productControlsClass' => 'cds-product-controls',
			'showTitle' => false,
			'productDescription' => isset($productInfo['description']) ? $productInfo['description'] : '',
			'modelNumberId' => 'cds-product-number',
			'modelNumberName' => 'cds-product-number',
			'productLabel' => isset($productInfo['label']) ? $productInfo['label'] : '',
			'categoryLabel' => $productInfo['category']['label'],
			'productDetailsClass' => 'cds-product-details',
			'longDescription' => $productInfo['longDescription'],
			'productDetailsLeftClass' => 'cds-product-details-left',
			'productDetailsRightClass' => 'cds-product-details-right',
		);

		$this->dependencies->setting('Product', array(
			'containerId' => 'cds-cart-container',
			'catalogCommand' => 'products',
        ));

		$this->defaultParameters = array(
			'productId' => $product,
			'categoryId' => $category,
			'productLabel' => $this->pageVars['productLabel'],
			'productDescription' => $productInfo['description'] ?: $productInfo['category']['label'],
			'productImageUrl' => $this->pageVars['productImageUrl'],
			'quantityDiscountSchedule' => $this->attributes->get('quantityDiscountSchedule'),
            'productAttributes' => $this->attributes->get('settings'),
			'title' => $title,
		);

		parent::initialize();
	}

	public function setupComponents() {
		$parameters = $this->getParameters();
		$urlHandler = $this->service->getUrlHandler();

		$this->addComponent(new ProductCartCdsComponent($this->service, $this, array(
			'listPrice' => $this->attributes->get('listPrice'),
			'parameters' => array(
				'productId' => $parameters['productId'],
				'categoryId' => $parameters['categoryId'],
				'productLabel' => $parameters['productLabel'],
				'productDescription' => $parameters['productDescription'],
				'productImageUrl' => $parameters['productImageUrl'],
				'cartUrl' => $urlHandler->getUriForPage("cart"),
			)
		)));

		$this->addComponent(new CadRequesterCdsComponent($this->service, $this, array(
			'parameters' => array(
				'productId' => $parameters['productId'],
			)
		)));

		$this->addComponent(new SpecSheetCdsComponent($this->service, $this, array(
			'parameters' => array(
				'productId' => $parameters['productId'],
				'categoryId' => $parameters['categoryId'],
			)
		)));

		$this->addComponent(new ProductImagesCdsComponent($this->service, $this, array(
			'attributes' => $this->attributes,
		)));

		$this->addComponent(new UnitToggleCdsComponent($this->service, $this));

		$this->addComponent(new ConfigurableAttributesCdsComponent($this->service, $this, array(
			'attributes' => $this->attributes,
			'parameters' => array(
				'productId' => $parameters['productId'],
				'categoryId' => $parameters['categoryId'],
			),
		)));

		$this->addComponent(new ProductAttributesCdsComponent($this->service, $this, array(
			'attributes' => $this->attributes,
		)));

		$this->addComponent(new ProductAttachmentsCdsComponent($this->service, $this, array(
			'attributes' => $this->attributes,
		)));
	}

	public function pageTitle() {
		return $this->getParameter('title');
	}
}
