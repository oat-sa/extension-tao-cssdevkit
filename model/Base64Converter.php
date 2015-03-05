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

use Jig\Utils\FsUtils;
use Jig\Utils\Utils;

class Base64Converter {

    /**
     * Path to resource file to convert
     * @var string
     */
    private $resource;

    /**
     * Init new Batch tool
     *
     * @param string $resource
     * @throws \common_exception_Error
     */
    public function __construct($resource) {
        if(!is_file($resource['tmp_name'])) {
            throw new \common_exception_Error('Resource file ' . $resource['tmp_name'] . ' not found');
        }
        $this->resource = $resource;
    }

    /**
     * @return array
     */
    public function convertToBase64() {
        $base64 = '';

        if (isset($_SERVER['CONTENT_LENGTH'])
            && (int)$_SERVER['CONTENT_LENGTH'] > min(Utils::iniGetBytes('post_max_size'), 1000000)
        ) {
            return array('error' => 'File too large - max size is 1 MB');
        }
        else {
            $base64 .= 'data:' . FsUtils::getMimeType($this->resource['tmp_name']) . ';'
                     . 'base64,' . base64_encode(file_get_contents($this->resource['tmp_name']));
            $oldFileSize = filesize($this->resource['tmp_name']);
            $newFileSize = strlen($base64);
            $sizeDiff    = ($newFileSize * 100 / $oldFileSize) - 100;
            $diffPrefix  = $sizeDiff < 0 ? '-' : '+';
            $sizeDiff    = $diffPrefix . (string)round($sizeDiff, 1) . '%';
            $msg         = basename($this->resource['name']) . ', '
                . 'was ' . FsUtils::formatFileSize($oldFileSize) . ', '
                . 'now ' . FsUtils::formatFileSize($newFileSize) . ' '
                . '(' . $diffPrefix . (string)round($sizeDiff, 1) . '%, '
                . 'about +30% is normal)';
            return array(
                'success' => $msg,
                'base64'  => $base64
            );
        }
    }

} 