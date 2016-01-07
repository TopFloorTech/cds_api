<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:38 PM
 */

namespace TopFloor\Cds\CdsComponents;

use TopFloor\Cds\Helpers\ProductAttributesHelper;

class ConfigurableAttributesCdsComponent extends CdsComponent {
  public function initialize() {
    $urlHandler = $this->service->getUrlHandler();

    $this->viewVars = array(
      'tableId' => 'cds-product-dynamic-attribute-table',
      'tableClass' => 'cds-product-details-container cds-attribute-table',
      'labelId' => 'cds-product-dynamic-attribute-table-label',
      'configurableAttributes' => $this->processConfigurableAttributes(),
    );

    $this->defaultParameters = array(
      'productId' => $urlHandler->get('id'),
      'categoryId' => $urlHandler->get('cid'),
    );

    parent::initialize();
  }

  protected function processConfigurableAttributes() {
    /** @var ProductAttributesHelper $attributes */
    $attributes = $this->settings['attributes'];

    $configurableAttributes = $attributes->get('configurableAttributes');
    $attributeValues = $attributes->get('attributeValues');

    foreach ($configurableAttributes as $id => $attribute) {
      $value = $attributeValues[$attribute['id']];

      $configurableAttributes[$id]['label'] = $attributes->getLabel($attribute, $attributeValues);
      $configurableAttributes[$id]['cleanId'] = htmlspecialchars($attribute['id']);
      $configurableAttributes[$id]['value'] = $value;
      $configurableAttributes[$id]['input'] = $attributes->getDynamicInput($attribute, $value);
    }

    return $configurableAttributes;
  }

  public function isEnabled() {
    /** @var ProductAttributesHelper $attributes */
    $attributes = $this->settings['attributes'];
    $configurableAttributes = $attributes->get('configurableAttributes');

    return !empty($configurableAttributes) ? parent::isEnabled() : false;
  }
}
