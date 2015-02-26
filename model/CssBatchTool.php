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
 * Copyright (c) 2014 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\taoCssDevKit\model;

/**
 * Script to batch apply a stylesheet to a number of items
 */
class CssBatchTool {
    
    /**
     * Path to css file
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
     * @return \common_report_Report
     */
    public function applyToClass(\core_kernel_classes_Class $class) {
        $report = new \common_report_Report(\common_report_Report::TYPE_SUCCESS);
        $itemItertator = new \core_kernel_classes_ResourceIterator(array($class));
        $count = 0;
        foreach ($itemItertator as $item) {
            // is QTI?
            $model = \taoItems_models_classes_ItemsService::singleton()->getItemModel($item);
            if ($model->getUri() == TAO_ITEM_MODEL_QTI) {
                $subreport = $this->applyToItem($item);
                $report->add($subreport);
                if ($subreport->getType() == \common_report_Report::TYPE_SUCCESS) {
                    $count++;
                } else {
                    $report->setType($subreport->getType());
                }
            }
        }
        $report->setMessage($count > 0 ? __('Applied to %s items', $count) : __('Css was not applied to any items')); 
        return $report;
    }
    
    /**
     * Apply the css to this item
     * 
     * @param \core_kernel_classes_Resource $item
     * @return \common_report_Report
     */
    public function applyToItem(\core_kernel_classes_Resource $item) {
        $itemService = \taoItems_models_classes_ItemsService::singleton();
        $availableLangs = array(DEFAULT_LANG);
        foreach ($availableLangs as $lang) {
            if ($itemService->hasItemContent($item, $lang)) {
                
                // get the new 
                $modifiedXml = $this->applyToXml($itemService->getItemContent($item), $lang);
                
                $manager = new \taoItems_helpers_ResourceManager(array('item'=> $item , 'lang' => $lang));
                $manager->add($this->cssFile, basename($this->cssFile), '');
                
                $itemService->setItemContent($item, $modifiedXml, $lang);
                return new \common_report_Report(\common_report_Report::TYPE_SUCCESS, __('Applied css to %s', $item->getLabel()));

            } else {
                return new \common_report_Report(\common_report_Report::TYPE_INFO, __('No item content for %s', $item->getLabel()));
            }
        }
    }

    /**
     * 
     * @param string $xml
     * @throws CssFoundException
     * @return string
     */
    protected function applyToXml($xml) {
        $xml = new \SimpleXMLElement($xml);
        
        $cssName = basename($this->cssFile);
        $addStyleNode = true;
        foreach($xml[0]->stylesheet as $stylesheet) {
            if((string)$stylesheet->attributes() -> href === $cssName) {
                $addStyleNode = false;
                break;
            }
        }
        
        if($addStyleNode) {
            $css = $xml->addChild('stylesheet', '');
            $css -> addAttribute('href', basename($this->cssFile));
            $css -> addAttribute('type','text/css');
            $css -> addAttribute('media','all');
            $css -> addAttribute('title','');
        }
        return $xml->asXML();
    }
}
