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
namespace oat\taoCssDevKit\test;

use oat\tao\test\TaoPhpUnitTestRunner;
use oat\taoCssDevKit\helpers\Utils;

class UtilsTest extends TaoPhpUnitTestRunner
{
    public function testAppendStylesheetNoPreviousStylesheets() {
        $sampleSrc = dirname(__FILE__) . '/samples/choice.xml';
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->load($sampleSrc);
        
        // Assert there are no stylesheets.
        $this->assertEquals(0, $doc->documentElement->getElementsByTagName('stylesheet')->length);
        
        Utils::appendStylesheet($doc, 'proot.css');
        
        // Assert there is now a single stylesheet.
        $this->assertEquals(1, $doc->documentElement->getElementsByTagName('stylesheet')->length);
        
        // Assert the attributes are correct.
        $stylesheet = $doc->documentElement->getElementsByTagName('stylesheet')->item(0);
        
        $this->assertEquals('proot.css', $stylesheet->getAttribute('href'));
        $this->assertEquals('all', $stylesheet->getAttribute('media'));
        $this->assertEquals('text/css', $stylesheet->getAttribute('type'));
        $this->assertEquals('', $stylesheet->getAttribute('title'));
    }
    
    public function testAppendStylesheetAfterCleanup() {
        $sampleSrc = dirname(__FILE__) . '/samples/stylesheets.xml';
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->load($sampleSrc);
        
        // Assert there are some stylesheets.
        $this->assertGreaterThanOrEqual(1, $doc->documentElement->getElementsByTagName('stylesheet')->length);
        
        Utils::appendStylesheet($doc, 'proot.css', true);
        
        // Assert that now, there is only a single stylesheet...
        $this->assertEquals(1, $doc->documentElement->getElementsByTagName('stylesheet')->length);
        
        // Let's check the attributes to make sure this is the correct one that remain...
        $stylesheet = $doc->documentElement->getElementsByTagName('stylesheet')->item(0);
        
        $this->assertEquals('proot.css', $stylesheet->getAttribute('href'));
        $this->assertEquals('all', $stylesheet->getAttribute('media'));
        $this->assertEquals('text/css', $stylesheet->getAttribute('type'));
        $this->assertEquals('', $stylesheet->getAttribute('title'));
    }
    
    public function testAppendStylesheetWithInvalidItem() {
        $sampleSrc = dirname(__FILE__) . '/samples/invalid.xml';
        $doc = new \DOMDocument('1.0', 'UTF-8');
        $doc->load($sampleSrc);
        
        // Assert there are some stylesheets (at the wrong place here).
        $this->assertEquals(2, $doc->documentElement->getElementsByTagName('stylesheet')->length);
        // Should be a child of assessmentItem (that's bad)...
        $this->assertEquals('assessmentItem', $doc->documentElement->getElementsByTagName('stylesheet')->item(0)->parentNode->tagName);
        
        Utils::appendStylesheet($doc, 'proot.css');
        
        // Only 1 stylesheet should be found, as a previous sibling of itemBody.
        $this->assertEquals(1, $doc->documentElement->getElementsByTagName('stylesheet')->length);
        
        $stylesheet = $doc->documentElement->getElementsByTagName('stylesheet')->item(0);
        $this->assertEquals('proot.css', $stylesheet->getAttribute('href'));
        $this->assertEquals('all', $stylesheet->getAttribute('media'));
        $this->assertEquals('text/css', $stylesheet->getAttribute('type'));
        $this->assertEquals('', $stylesheet->getAttribute('title'));
    }
}

?>