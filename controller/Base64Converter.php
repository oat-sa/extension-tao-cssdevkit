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

use oat\taoCssDevKit\model\Base64Converter as Base64ConverterModel;


class Base64Converter extends \tao_actions_CommonModule {



    /**
     * A possible entry point to tao
     */
    public function index() {
        // shows the upload/download options
        $this->setView('CssManager/base64.tpl');
    }

    /**
     * Convert uploaded resource to base 64 code
     *
     * @return mixed
     */
    public function convert() {

        $resource = $file = \tao_helpers_Http::getUploadedFile('content');

        $base64Converter = new Base64ConverterModel($resource);

        $this -> returnJson($base64Converter->convertToBase64());
    }

} 