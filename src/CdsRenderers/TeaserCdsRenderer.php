<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 8:13 PM
 */

namespace TopFloor\Cds\CdsRenderers;


use TopFloor\Cds\CdsEntities\CdsEntity;

class TeaserCdsRenderer extends CacheableCdsRenderer
{
    public /** @noinspection HtmlUnknownTarget */
        $templates = array(
        'wrapper' => '<div class="cds-integration-teaser">%s</div>',
        'link' => '<a href="%s">%s</a>',
        'image' => '<img src="%s">',
        'label' => '<h3>%s <i class="fa fa-chevron-circle-right"></i></h3>',
        'description' => '<p>%s</p>',
    );

    public function _render(CdsEntity $entity)
    {
        $cds = $entity->getCds();

        $output = '';

        if (!empty($cds->category['browseImageURL'])) {
            $output .= sprintf($this->templates['image'], $cds->category['browseImageURL']);
        }

        $output .= sprintf($this->templates['label'], $cds->category['label']);

        if (!empty($cds->category['description'])) {
            $output .= sprintf($this->templates['description'], $cds->category['description']);
        }

        $output = sprintf($this->templates['link'], $entity->getUrl(), $output);

        return sprintf($this->templates['wrapper'], $output);
    }
}
