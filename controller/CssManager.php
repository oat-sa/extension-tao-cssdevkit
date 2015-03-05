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
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA;
 *               
 * 
 */

namespace oat\taoCssDevKit\controller;

use oat\taoCssDevKit\model\CssBatchTool;
/**
 * Css Manager controller,
 * to apply css to all items
 *
 * @author Open Assessment Technologies SA
 * @package taoCssDevKit
 * @license GPL-2.0
 *
 */
class CssManager extends \tao_actions_CommonModule {

    /**
     * initialize the services
     */
    public function __construct(){
        parent::__construct();
    }

    /**
     * A possible entry point to tao
     */
    public function index() {
        // shows the upload/download options
        $this->setView('CssManager/index.tpl');
    }

    /**
     *
     */
    public function apply() {
        $cssFileData = $file = \tao_helpers_Http::getUploadedFile('content');

        $batchTool = new CssBatchTool($cssFileData['tmp_name']);
        $report = $batchTool->applyToClass(\taoItems_models_classes_ItemsService::singleton()->getRootClass(), $cssFileData['name']);

        $this->returnJson($report);
        
    }


    /**
     *
     */
    public function reset() {
        //reset all custom CSS in the items
    }
    
}