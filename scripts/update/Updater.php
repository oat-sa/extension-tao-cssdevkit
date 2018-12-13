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

namespace oat\taoCssDevKit\scripts\update;

use oat\taoCssDevKit\helpers\Utils;
use oat\taoQtiItem\model\qti\Service;
use taoItems_models_classes_itemModel;

/**
 * TAO CSS DevKit Updater.
 *
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 */
class Updater extends \common_ext_ExtensionUpdater
{

    /**
     * Perform update from $currentVersion to $versionUpdatedTo.
     *
     * @param string $currentVersion
     * @return string $versionUpdatedTo
     */
    public function update($initialVersion)
    {
        $currentVersion = $initialVersion;

        // migrate from 0.9 to 0.9.1
        // old taoResults extension gets uninistalled and taoOutcomeRds becomes
        // the new default result storage mechanism.
        if ($currentVersion == '0.9') {
            self::migrateFrom09To091();
            $currentVersion = '0.9.1';
        }

        if ($currentVersion == '0.9.1') {
            $currentVersion = '0.9.2';
        }
        $this->setVersion($currentVersion);

        if($this->isVersion('0.9.2')){
            $this->setVersion('0.9.3');
        }

        $this->skip('0.9.3', '3.1.0');

        return null;
    }

    static private function migrateFrom09To091() {
        // Get all items...
        $itemService = \taoItems_models_classes_ItemsService::singleton();
        $itemClass = $itemService->getRootClass();
        foreach ($itemClass->getInstances(true) as $item) {
            if ($itemService->hasItemModel($item, array(taoItems_models_classes_itemModel::CLASS_URI_QTI))) {
                $qtiXml = Service::singleton()->getDataItemByRdfItem($item)->toXML();

                if (empty($qtiXml) === false) {
                    $qtiDom = new \DOMDocument('1.0', 'UTF-8');
                    $qtiDom->loadXML($qtiXml);

                    $path = $itemService->getItemDirectory($item)->getPrefix();

                    // Get all stylesheet hrefs.
                    $hrefs = Utils::getStylesheetHrefs($qtiDom);

                    // Make sure the hrefs are refering existing files.
                    for ($i = 0; $i < count($hrefs); $i++) {
                        $href = $hrefs[$i];
                        if (is_readable($path . $href) === false) {
                            \common_Logger::i("The stylesheet->href '${path}.${href}' does not reference an existing file. Trying to repair...");

                            // Let's try with another name...
                            $pathinfo = pathinfo($href);
                            $altFileName = \tao_helpers_File::getSafeFileName($pathinfo['basename']);
                            $dirSep = ($pathinfo['dirname'] !== '.') ? $pathinfo['dirname'] . DIRECTORY_SEPARATOR : '';
                            $altPath = $path. $dirSep . $altFileName;

                            if (is_readable($altPath)) {
                                // Bingo! We rebind.
                                $hrefs[$i] = $dirSep . $altFileName;
                                \common_Logger::i("Repaired with new href '${dirSep}.${altFileName}}'.");
                            } else {
                                // It's definitely broken...
                                unset($hrefs[$i]);
                                \common_Logger::i("Could not be repaired! QTI stylesheet component removed from item.");
                            }
                        }
                    }

                    // Reput them in the item with cleanup enabled
                    // to solve the XMLSchema validation issue.
                    if (count($hrefs) > 0) {
                        $href = array_shift($hrefs);
                        Utils::appendStylesheet($qtiDom, $href, true);
                    }

                    // Append the rest of the stylesheets.
                    foreach ($hrefs as $href) {
                        Utils::appendStylesheet($qtiDom, $href);
                    }

                    Service::singleton()->saveXmlItemToRdfItem($qtiDom->saveXML(), $item);
                }
            }
        }
    }
}
