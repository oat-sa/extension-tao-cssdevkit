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
require_once dirname(dirname(__DIR__)) . '/taoQtiItem/includes/raw_start.php';

use oat\taoCssDevKit\model\CssBatchTool;

/**
 * Script to batch apply a stylesheet to a number of items
 */
try {
    // stylesheet can be either an argument...
    if(empty($argv[1])) {
        $localCss = glob(__DIR__ . '/*.css');
        if(!count($localCss)) {
            throw new Exception('No CSS file given');
        }
        $cssFile = array_shift($localCss);
    }
    // ...or a _single_ CSS file in the same directory
    else {
        if(!is_file($argv[1])) {
            throw new Exception('CSS file ' . $argv[1] . ' not found');
        }
        $cssFile = $argv[1];
    }
    
    $tool = new CssBatchTool($cssFile);
    $report = $tool->applyToClass(taoItems_models_classes_ItemsService::singleton()->getRootClass());
    echo tao_helpers_report_Rendering::renderToCommandline($report, true);
    
} catch(Exception $e) {
    print $e -> getMessage();
}



