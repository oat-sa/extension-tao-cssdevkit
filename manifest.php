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

return array(
    'name' => 'taoCssDevKit',
	'label' => 'Css Development Kit',
	'description' => '',
    'license' => 'GPL-2.0',
    'version' => '1.0.1',
	'author' => 'Open Assessment Technologies SA',
	'requires' => array('taoQtiItem' => '>=2.7.3', 'taoDataUri' => '>=0.0.0'),
	// for compatibility
	'dependencies' => array('tao'),
    'update' => 'oat\\taoCssDevKit\\scripts\\update\\Updater',
	'managementRole' => 'http://www.tao.lu/Ontologies/generis.rdf#taoCssDevKitManager',
    'acl' => array(
        array('grant', 'http://www.tao.lu/Ontologies/generis.rdf#taoCssDevKitManager', array('ext'=>'taoCssDevKit')),
    ),
    'uninstall' => array(
    ),
    'autoload' => array (
        'psr-4' => array(
            'oat\\taoCssDevKit\\' => dirname(__FILE__).DIRECTORY_SEPARATOR
        )
    ),
    'routes' => array(
        '/taoCssDevKit' => 'oat\\taoCssDevKit\\controller'
    ),    
	'constants' => array(
	    # views directory
	    "DIR_VIEWS" => dirname(__FILE__).DIRECTORY_SEPARATOR."views".DIRECTORY_SEPARATOR,
	    
		#BASE URL (usually the domain root)
		'BASE_URL' => ROOT_URL.'taoCssDevKit/',
	),
    'extra' => array(
        'structures' => dirname(__FILE__).DIRECTORY_SEPARATOR.'controller'.DIRECTORY_SEPARATOR.'structures.xml',
    )
);
