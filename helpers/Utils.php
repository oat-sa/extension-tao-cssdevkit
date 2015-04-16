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
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA (under the project TAO-PRODUCT);
 *
 */

namespace oat\taoCssDevKit\helpers;

/**
 * TAO CSS Dev Kit Utility class.
 * 
 * @author Jérôme Bogaerts <jerome@taotesting.com>
 *
 */
class Utils
{
    /**
     * Append a stylesheet to a DOMDocument representing a QTI-XML file.
     * 
     * The stylesheet will be appended prior to itemBody component in order
     * to keep the validity of the file against its XMLSchema.
     * 
     * @param \DOMDocument $doc
     * @param string $cssName The value that will be written as the 'href' attribute's value.
     * @param boolean $cleanUp Whether or not to clean up all stylesheet elements from the source $doc prior insertion of the new stylesheet component (default is false).
     */
    static public function appendStylesheet(\DOMDocument $doc, $cssName, $cleanUp = false) {
        
        $root = $doc->documentElement;
        $stylesheetElts = $root->getElementsByTagName('stylesheet');
        
        // Do we clean up?
        if ($cleanUp === true) {
            for ($i = 0; $i < $stylesheetElts->length; $i++) {
                $stylesheetElt = $stylesheetElts->item($i);
                $stylesheetElt->parentNode->removeChild($stylesheetElt);
                $i--;
            }
        }
        
        $stylesheetElts = $root->getElementsByTagName('stylesheet');
        
        // Let's query again and iterate to check if we have
        // to really add the node (same target href?).
        for ($i = 0; $i < $stylesheetElts->length; $i++) {
            $stylesheetElt = $stylesheetElts->item($i);
            
            if ($stylesheetElt->getAttribute('href') === $cssName) {
                return;
            }
        }
        
        // If we are here, it means we can append the new stylesheet element
        // to the document.
        $itemBodyElts = $root->getElementsByTagName('itemBody');
        if ($itemBodyElts->length === 1) {
            $newStylesheetElt = $doc->createElement('stylesheet');
            $newStylesheetElt->setAttribute('href', $cssName);
            $newStylesheetElt->setAttribute('type', 'text/css');
            $newStylesheetElt->setAttribute('media', 'all');
            
            $root->insertBefore($newStylesheetElt, $itemBodyElts->item(0));
        }
    }
}