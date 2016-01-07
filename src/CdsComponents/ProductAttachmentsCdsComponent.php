<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 12/24/2015
 * Time: 9:38 PM
 */

namespace TopFloor\Cds\CdsComponents;

use TopFloor\Cds\Helpers\ProductAttributesHelper;

class ProductAttachmentsCdsComponent extends CdsComponent {
  public function initialize() {
    $this->viewVars = array(
      'tableId' => 'cds-product-attachment-table',
      'tableClass' => 'cds-product-details-container cds-attribute-table',
      'attachmentTarget' => 'cds-catalog-attachment',
      'attachments' => $this->processAttachments(),
    );

    parent::initialize();
  }

  protected function processAttachments() {
    /** @var ProductAttributesHelper $attributes */
    $attributes = $this->settings['attributes'];

    $attachments = $attributes->get('attachments');
    $attributeValues = $attributes->get('attributeValues');

    foreach ($attachments as $id => $attachment) {
      $value = $attributeValues[$attachment['id']];

      $attachments[$id]['cleanId'] = htmlspecialchars($attachment['id']);
      $attachments[$id]['filename'] = $this->getFilename($value);
      $attachments[$id]['href'] = $value;
    }

    return $attachments;
  }

  protected function getFilename($value) {
    $pos = strripos($value, '/');
    if ($pos !== false) {
      $value = substr($value, $pos + 1);
    }

    return $value;
  }

  public function isEnabled() {
    /** @var ProductAttributesHelper $attributes */
    $attributes = $this->settings['attributes'];
    $attributes = $attributes->get('attachments');

    return !empty($attributes) ? parent::isEnabled() : false;
  }
}
