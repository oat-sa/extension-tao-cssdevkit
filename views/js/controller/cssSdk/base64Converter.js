/*
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
 * Copyright (c) 2015 (original work) Open Assessment Technologies SA ;
 *
 */

/**
 *
 * @author dieter <dieter@taotesting.com>
 */
define([
    'jquery',
    'i18n',
    'ui',
    'ui/uploader',
    'ui/feedback'
],
    function ($, __, ui, uploader, feedback) {

        // convert a resource to base 64
        var base64Container = $('#base64-container'),
            $textarea = $('#base64-code');


        base64Container.on('create.uploader', function (e, file, interactionHook) {
            $textarea.width(base64Container.outerWidth() * 2).height(base64Container.outerHeight());
        });

        base64Container.on('upload.uploader', function (e, file, interactionHook) {
            if(interactionHook.error) {
                feedback().error(interactionHook.error);
                return;
            }

            feedback().success(interactionHook.success, {
                timeout: {
                    success: 4000
                }});
            $textarea.html(interactionHook.base64).select();
        });

        base64Container.on('fail.uploader', function (e, file, interactionHook) {
            feedback().error(interactionHook.message);
        });

        base64Container.uploader({
            uploadUrl: base64Container.data('url'),
            uploadBtnText: __('Convert to Base64 Code')
        });
    });
