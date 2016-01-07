<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:38 PM
 */

namespace TopFloor\Cds\CdsComponents;

use TopFloor\Cds\Helpers\ProductAttributesHelper;

class ProductAttributesCdsComponent extends CdsComponent {
  public function initialize() {
    $this->viewVars = array(
      'tableId' => 'cds-product-attribute-table',
      'tableClass' => 'cds-product-details-container cds-attribute-table',
      'attributes' => $this->processAttributes(),
    );

    parent::initialize();
  }

  protected function processAttributes() {
    /** @var ProductAttributesHelper $attributes */
    $attributes = $this->settings['attributes'];

    $productAttributes = $attributes->get('productAttributes');
    $attributeValues = $attributes->get('attributeValues');

    foreach ($productAttributes as $id => $attribute) {
      $value = $attributeValues[$attribute['id']];

      if ($attribute['dataType'] == 'fraction') {
        $value = $attributes->fraction($value);
      }

      $productAttributes[$id]['label'] = $attributes->getLabel($attribute, $attributeValues);
      $productAttributes[$id]['cleanId'] = htmlspecialchars($attribute['id']);
      $productAttributes[$id]['value'] = $value;
    }

    return $productAttributes;
  }

  public function isEnabled() {
    /** @var ProductAttributesHelper $attributes */
    $attributes = $this->settings['attributes'];
    $productAttributes = $attributes->get('attributes');

    return !empty($productAttributes) ? parent::isEnabled() : false;
  }
}
