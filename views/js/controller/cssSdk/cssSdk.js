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
    'ui/feedback'],
    function ($, __, ui, uploader, feedback) {

        // upload and apply a css file to multiple items
        var cssContainer = $('#css-container');

//        cssContainer.on('create.uploader', function (e, file, interactionHook) {
//            var $undoBtn = $('<button>', {
//                disabled: 'disabled',
//                'class': 'btn-info small',
//                text: 'Undo'
//            }).prepend($('<span>', {
//                'class' : 'icon-undo'
//                }));
//            cssContainer.find('button').last().after($undoBtn);
//        });

        cssContainer.on('upload.uploader', function (e, file, interactionHook) {
            feedback().success(interactionHook.success);
        });

        cssContainer.on('fail.uploader', function (e, file, interactionHook) {
            feedback().error(interactionHook.message);
        });

        cssContainer.uploader({
            uploadUrl: cssContainer.data('url'),
            uploadBtnText: __('Apply CSS to Items')
        });
    });
