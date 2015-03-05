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
    'ui/feedback',
    'tpl!taoCssDevKit/controller/templates/report'
],
    function ($, __, ui, uploader, feedback, reportTpl) {

        // upload and apply a css file to multiple items
        var cssContainer = $('#css-container');

        cssContainer.on('upload.uploader', function (e, file, interactionHook) {

            feedback()[interactionHook.type](reportTpl(interactionHook),
                {timeout: {  info: 6000, success: 6000, warning: 6000, error: 6000}});
        });

        cssContainer.on('fail.uploader', function (e, file, interactionHook) {
            feedback().error(interactionHook.message);
        });

        cssContainer.uploader({
            uploadUrl: cssContainer.data('url'),
            uploadBtnText: __('Apply CSS to Items')
        });
    });
