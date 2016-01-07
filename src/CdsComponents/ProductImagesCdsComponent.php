<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:40 PM
 */

namespace TopFloor\Cds\CdsComponents;

use TopFloor\Cds\Helpers\ProductAttributesHelper;

class ProductImagesCdsComponent extends CdsComponent {
  public function initialize() {
    $this->viewVars = array(
      'images' => $this->getImages(),
      'additionalImagesClass' => 'cds-product-additional-images',
      'additionalImagesLabelClass' => 'cds-product-additional-images-label',
      'additionalImagesLabelText' => 'Line Drawing - Click to Enlarge',
      'expandedImageContainerId' => 'cds-product-additional-images-expanded',
      'expandedImageId' => 'cds-product-additional-images-expanded-img',
    );

    parent::initialize();
  }

  protected function getImages() {
    /** @var ProductAttributesHelper $attributes */
    $attributes = $this->settings['attributes'];

    $images = array();

    $attributeValues = $attributes->get('attributeValues');

    foreach ($attributes->get('imageAttributes') as $attribute) {
      $id = htmlspecialchars($attributeValues[$attribute['id']]);
      $label = htmlspecialchars($attribute['label']);

      $images[] = array(
        'fullSrc' => $id,
        'thumbSrc' => $id,
        'alt' => $label,
        'title' => $label,
      );
    }

    return $images;
  }
}
