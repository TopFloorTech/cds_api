<?php
/**
 * Created by PhpStorm.
 * User: BMcClure
 * Date: 8/17/2015
 * Time: 2:56 PM
 */

namespace TopFloor\Cds\Helpers;


class ProductAttributesHelper
{
    protected $productInfo;

    protected $templates = array(
        'multiList'      => '<ul id="cds-dv-%s" class="cds-attribute-multilist">%s</ul>',
        'multiListItem'  => '<li>%s</li>',
        'multiListInput' => '<input type="checkbox" value="%s" name="cds-dv-%s" onchange=\'%s\' id="cds-dv-%s">',
        'multiListLabel' => '<label for="cds-dv-%s">%s</label>',
        'list'           => '<select id="cds-dv-%s" onchange=\'%s\'>%s</select>',
        'listOption'     => '<option value="%s"%s>%s</option>',
        'range'          => '<input id="cds-dv-%s" onchange=\'%s\' onfocus="select()" size="10" value="%s" type="text">',
        'text'           => '<input id="cds-dv-%s" onchange=\'%s\' onfocus="select()" size="10" value="%s" type="text">',
        'setButton'     => '<button>Set</button>',
        'jsHandler'      => 'cds.handleChangeDynamicAttribute(%s);'
    );

    protected $initialized = false;

    protected $attributes = array(
        'attributes' => array(),
        'configurableAttributes' => array(),
        'attachments' => array(),
        'imageAttributes' => array(),
        'htmlAttributes' => array(),
        'footnotes' => array(),
        'notices' => array(),
        'attributeValues' => array(),
        'settings' => array(),
        'listPrice' => 0.00,
        'quantityDiscountSchedule' => array(),
    );

    public function __construct($productInfo)
    {
        $this->productInfo = $productInfo;

        $this->initialize();
    }

    public function get($type = null)
    {
        if (is_null($type)) {
            return $this->attributes;
        }

        if (!isset($this->attributes[$type])) {
            return array();
        }

        return $this->attributes[$type];
    }

    public function initialize()
    {
        if ($this->initialized) {
            return;
        }

        $attributes = $this->productInfo['attributes'];
        $attributeValues = $this->productInfo['attributeValues'];

        foreach ($attributes as $index => $attribute) {
            if ($attribute['id'] === 'list_price') {
                $this->attributes['listPrice'] = preg_replace('/[^0-9\.]+/', '', $attributeValues[$index]);

                continue;
            }

            if ($attribute['id'] === 'quantity_discount_schedule') {
                foreach (explode('|', $attributeValues[$index]) as $item) {
                    if (preg_match('/(?<min>[0-9\.]*),(?<max>[0-9\.]*):(?<price>.*)/', $item, $match)) {
                        $this->attributes['quantityDiscountSchedule'][] = $match;
                    }
                }

                continue;
            }

            if (isset($attributeValues[$index])) {
                if ($attribute['visible'] !== false) {
                    switch ($attribute['dataType']) {
                        case 'list':
                        case 'range':
                        case 'multilist':
                        case 'text':
                            $this->attributes['configurableAttributes'][] = $attribute;
                            break;
                        case 'attachment':
                            $this->attributes['attachments'][] = $attribute;
                            break;
                        case 'image':
                            $this->attributes['imageAttributes'][] = $attribute;
                            break;
                        case 'html':
                            $this->attributes['htmlAttributes'][] = $attribute;
                            break;
                        case 'footnote':
                            $this->attributes['footnotes'][] = $attribute;
                            break;
                        case 'notice':
                            $this->attributes['notices'][] = $attribute;
                            break;
                        default:
                            $this->attributes['productAttributes'][] = $attribute;
                            break;
                    }
                }

                $value = $attributeValues[$index];
                if (in_array($attribute['dataType'], array('list', 'range', 'multilist'))) {
                    $value = explode('|', $value);
                }

                $this->attributes['attributeValues'][$attribute['id']] = $value;

                $structure = array('dataType' => $attribute['dataType']);

                foreach (array('label', 'toolTip', 'imageURL') as $item) {
                    if (!empty($attribute[$item])) {
                        $structure[$item] = $attribute[$item];
                    }
                }

                foreach (array('precision', 'step') as $item) {
                    if (isset($attribute[$item])) {
                        $structure[$item] = $attribute[$item];
                    }
                }

                if (!empty($attribute['persistedUnit'])) {
                    $structure['unit'] = $attribute['persistedUnit'];
                }

                foreach (array('searchable', 'visible', 'multiValue', 'rangeSearchable', 'selectLTE', 'selectGTE') as $item) {
                    if ($attribute[$item]) {
                        $structure[$item] = true;
                    }
                }

                if (isset($attribute['sortOrder'])) {
                    $structure['sortOrder'] = $attribute['sortOrder'];
                }

                foreach (array('cadDataType', 'cadParameterName') as $item) {
                    if (!empty($attribute[$item])) {
                        $structure[$item] = $attribute[$item];
                    }
                }

                if ($attribute['dataType'] == 'fraction') {
                    $value = $this->fraction($attributeValues[$attribute['id']]);
                }

                $structure['value'] = $value;

                $this->attributes['settings'][$attribute['id']] = $structure;
            }

        }

        $this->attributes['attributes'] = $attributes;

        $this->initialized = true;
    }

    public function fraction($value, $tolerance = 1.e-6) {
        $w = 0;
        $f = $value;
        if ($value > 1.0) {
            $w = floor($value);
            $f = $value - $w;
        }
        if ($f == 0) {
            return "$w";
        }

        $h1=1; $h2=0;
        $k1=0; $k2=1;
        $b = 1/$f;
        do {
            $b = 1/$b;
            $a = floor($b);
            $aux = $h1; $h1 = $a*$h1+$h2; $h2 = $aux;
            $aux = $k1; $k1 = $a*$k1+$k2; $k2 = $aux;
            $b = $b-$a;
        } while (abs($f-$h1/$k1) > $f*$tolerance);

        if ($w != 0) {
            return "$w $h1/$k1";
        }
        return "$h1/$k1";
    }

    public function getLabel($attribute, $attributeValue)
    {
        $label = $attribute['label'];

        if (!empty($attribute['persistedUnit'])) {
            $label .= ' (' . $attribute['persistedUnit'] . ')';
        }

        if ($attribute['dataType'] === 'range') {
            $from = number_format($attributeValue[0], $attribute['precision']);
            $to = number_format($attributeValue[1], $attribute['precision']);

            $label .= " ($from to $to)";
        }

        return $label;
    }

    public function appendUnit($label, $attribute, $unitSystem) {
        $unit = null;

        if (is_null($attribute['persistedUnit'])) {
            return $label;
        }

        $unit = $attribute['persistedUnit'];

        if ($unitSystem === 'metric' && $attribute['metricDefaultUnit']) {
            $unit = $attribute['metricDefaultUnit'];
        } elseif ($unitSystem === 'english' && $attribute['englishDefaultUnit']) {
            $unit = $attribute['englishDefaultUnit'];
        }

        return $label . ' (' . $unit . ')';
    }

    public function getDynamicInput($attribute, $attributeValue)
    {
        $output = '';
        $id = htmlspecialchars($attribute['id']);

        $js = sprintf($this->templates['jsHandler'], json_encode($attribute['id']));

        switch ($attribute['dataType']) {
            case 'multilist':
                $items = array();

                foreach ($attributeValue as $j => $v) {
                    $item = sprintf($this->templates['multiListInput'], htmlspecialchars($v), $id, $js, $id-$j);
                    $item .= sprintf($this->templates['multiListLabel'], "$id-$j", $v);

                    $items[] = $item;
                }

                $output = sprintf($this->templates['multiList'], $id, implode("\n", $items));

                break;
            case 'list':
                $options = array();

                foreach ($attributeValue as $val) {
                    $selected = ($val === $attributeValue[count($val) - 1]) ? ' selected="selected"' : '';

                    $options[] = sprintf($this->templates['listOption'], htmlspecialchars($val), $selected, $val);
                }

                $output = sprintf($this->templates['list'], $id, $js, implode("\n", $options));

                break;
            case 'range':
                $v = '';

                if (count($attributeValue) > 2) {
                    $v = htmlspecialchars(number_format($attributeValue[2], $attribute['precision']));
                }

                $output = sprintf($this->templates['range'], $id, $js, $v);
                $output .= sprintf($this->templates['setButton']);

                break;
            case 'text':
                $js = sprintf($this->templates['jsHandler'], $id);

                $output = sprintf($this->templates['text'], $id, $js, $attributeValue);
                $output .= sprintf($this->templates['setButton']);

                break;
        }

        return $output;
    }

    public function getAttachmentValue($attribute, $attributeValue) {
        $v = $attributeValue;

        $pos = strripos($v, '/');

        if ($pos !== false) {
            $v = substr($v, $pos + 1);
        }

        if (strlen($v) > 37) {
            $v = substr($v, 0, 37) . "...";
        }

        return $v;
    }
}
