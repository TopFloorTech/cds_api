<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 2/3/2016
 * Time: 8:32 PM
 */

namespace TopFloor\Cds\CdsEntities;


class UtilityCdsEntity extends CdsEntity {

    protected $pageMap = array(
        'search' => 'keys',
    );

    protected function mapPage($id) {
        return (isset($this->pageMap[$id])) ? $this->pageMap[$id] : $id;
    }

    protected function initialize()
    {
        $page = $this->getId();

        $parameters = array('page' => $this->mapPage($page));

        switch ($page) {
            case 'search':
                $parameters['cid'] = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 'root';
                $parameters['cdskeys'] = isset($_REQUEST['cdskeys']) ? $_REQUEST['cdskeys'] : '';

                break;
            case 'cart':
                $config = $this->service->getConfig();
                
                $parameters['cartThankYouUrl'] = $config->get('cartThankYouUrl');
        }

        $this->parameters += $parameters;

        parent::initialize();
    }

    public function getLabel()
    {
        $name = $this->getId();

        switch ($name) {
            case 'search':
                $categoryId = $this->getParameter('cid');

                if (empty($categoryId) || $categoryId == 'root') {
                    $name = 'Search';
                } else {
                    $categoryInfo = $this->service->categoryRequest($categoryId);

                    $name = $categoryInfo['label'];
                }

                break;
            default:
                $name = ucfirst($name);

                break;
        }

        return $name;
    }

    public function shouldCache() {
        $doNotCache = array('search');

        return (!in_array($this->getId(), $doNotCache));
    }
}
