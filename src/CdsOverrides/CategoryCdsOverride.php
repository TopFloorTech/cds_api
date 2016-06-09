<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/10/2016
 * Time: 2:55 PM
 */

namespace TopFloor\Cds\CdsOverrides;

use TopFloor\Cds\CdsEntities\CdsEntity;

class CategoryCdsOverride extends CdsOverride {
    function override(CdsEntity $entity, $viewMode = 'full') {
        $cds = $entity->getCds();

        if (empty($cds->category) || $this->fieldMap->isEmpty()) {
            return;
        }

        switch ($viewMode) {
            case 'teaser':
                $this->overrideField('teaser_description', $cds->category, 'description');
                $this->overrideField('teaser_image', $cds->category, array('searchImageURL', 'browseImageURL'));

                break;
            default:
                $this->overrideField('headline', $cds->category, 'label');
                $this->overrideField('description', $cds->category, 'searchHeaderHTML');
                $this->overrideField('image', $cds->category, 'searchImageURL');

                if (!empty($cds->category['children'])) {
                    foreach ($cds->category['children'] as &$child) {
                        $childFieldMap = $this->fieldMap->createFieldMap($child['id'], 'category');

                        $this->overrideField('teaser_image', $child, 'browseImageURL', $childFieldMap);
                        $this->overrideField('teaser_description', $child, 'description', $childFieldMap);
                    }
                }

                break;
        }
    }
}
