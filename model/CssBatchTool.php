<?php
/**
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; under version 2
 * of the License (non-upgradable).
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright (c) 2014-2015 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\taoCssDevKit\model;

use oat\taoCssDevKit\helpers\Utils;
use oat\taoQtiItem\model\qti\Service;
use taoItems_models_classes_itemModel;

/**
 * Script to batch apply a stylesheet to a number of items
 */
class CssBatchTool {

    /**
     * CSS file
     * @var string
     */
    private $cssFile;

    /**
     * Init new Batch tool
     *
     * @param string $cssFile
     * @throws \common_exception_Error
     */
    public function __construct($cssFile) {
        if(!is_file($cssFile)) {
            throw new \common_exception_Error('CSS file ' . $cssFile . ' not found');
        }

        $this->cssFile = $cssFile;
    }

    /**
     * Apply css to all instances of this class and its subclasses
     *
     * @param \core_kernel_classes_Class $class
     * @param $destPath, optional
     * @return \common_report_Report
     * @throws \common_exception_Error
     */
    public function applyToClass(\core_kernel_classes_Class $class, $destPath = null) {
        $destPath = is_null($destPath) ? basename($this->cssFile) : $destPath; 
        
        if(strtolower(substr(strrchr($destPath, '.'), 1)) !== 'css') {
            throw new \common_exception_Error($destPath . ' does not appear to be a stylesheet');
        }
        
        $report = new \common_report_Report(\common_report_Report::TYPE_SUCCESS);
        $itemIterator = new \core_kernel_classes_ResourceIterator(array($class));
        $count = 0;
        foreach ($itemIterator as $item) {
            // is QTI?
            $model = \taoItems_models_classes_ItemsService::singleton()->getItemModel($item);
            if (!is_null($model) && $model->getUri() == taoItems_models_classes_itemModel::CLASS_URI_QTI) {
                $subReport = $this->applyToItem($item, $destPath);
                $report->add($subReport);
                if ($subReport->getType() == \common_report_Report::TYPE_SUCCESS) {
                    $count++;
                } else {
                    $report->setType($subReport->getType());
                }
            }
        }
        $report->setMessage($count > 0 ? __('%1s has been applied to %2s items', basename($destPath), $count) : __('CSS was not applied to any items'));
        return $report;
    }

    /**
     * Apply the css to this item
     *
     * @param \core_kernel_classes_Resource $item
     * @param $destPath
     * @return \common_report_Report
     */
    public function applyToItem(\core_kernel_classes_Resource $item, $destPath) {
        $itemService = \taoItems_models_classes_ItemsService::singleton();
        $availableLangs = array(DEFAULT_LANG);
        foreach ($availableLangs as $lang) {
            if ($itemService->hasItemContent($item, $lang)) {

                // get the new
                $modifiedXml = $this->applyToXml(Service::singleton()->getDataItemByRdfItem($item)->toXML(), $destPath);

                $manager = new \taoItems_helpers_ResourceManager(array('item'=> $item , 'lang' => $lang));
                $manager->add($this->cssFile, $destPath, '');

                Service::singleton()->saveXmlItemToRdfItem($modifiedXml, $item);
                return new \common_report_Report(\common_report_Report::TYPE_SUCCESS, $item->getLabel());

            } else {
                return new \common_report_Report(\common_report_Report::TYPE_INFO, $item->getLabel() . ' (n/a)');
            }
        }
    }

    /**
     * @param $xml
     * @param $cssName
     * @return mixed
     */
    protected function applyToXml($xml, $cssName) {
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->loadXML($xml);
        
        Utils::appendStylesheet($doc, $cssName);
        return $doc->saveXML();
    }
}
