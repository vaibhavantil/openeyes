<?php
/**
 * OpenEyes.
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
?>
<div class="box admin">
    <div class="data-group">
        <div class="cols-8 column">
            <h2>Whiteboard Settings</h2>
        </div>
        <div class="cols-4 column">
        </div>
    </div>

    <form id="whiteboard_settings">
        <input type="hidden" name="YII_CSRF_TOKEN" value="<?php echo Yii::app()->request->csrfToken?>" />
        <table class="grid">
            <thead>
            <tr>
                <th>Setting</th>
                <th>Value</th>
            </tr>
            </thead>
            <tbody>
            <?php
            foreach ($settings as $metadata) {?>
                <tr class="clickable" data-key="<?php echo $metadata->key?>" data-uri='OphTrOperationbooking/oeadmin/WhiteboardSettings/editSetting?key=<?=$metadata->key;?>'>
                    <td style="width:50%"><?php echo $metadata->name?></td>
                    <td><?php echo $metadata->getSettingName()?></td>
                </tr>
            <?php }?>
            </tbody>
        </table>
    </form>
    <br>
</div>
