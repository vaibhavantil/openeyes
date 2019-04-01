<?php
/**
 * (C) OpenEyes Foundation, 2018
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2019, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
?>


<div class="cols-7">

    <div class="row divider">
        <h2>Edit setting</h2>
    </div>

    <?php
    $form = $this->beginWidget('BaseEventTypeCActiveForm', array(
        'id' => 'settingsform',
        'enableAjaxValidation' => false,
        'focus' => '#username',
        'layoutColumns' => array(
            'label' => 2,
            'field' => 5,
        ),
    )) ?>

    <table class="cols-full last-left standard">
        <colgroup>
            <col class="cols-1">
            <col class="cols-1">
        </colgroup>
        <tbody>
        <tr>

            <td><?= $metadata->name ?></td>
            <td>
                <?php
                $this->renderPartial(
                    '_admin_setting_' . strtolower(str_replace(' ', '_', $metadata->field_type->name)),
                    ['metadata' => $metadata]
                );
                ?>
            </td>
        </tr>
        </tbody>
        <tfoot class="pagination-container">
        <tr>
            <td colspan="2">
                <?= CHtml::submitButton('Save', [
                        'class' => 'button large',
                        'name' => 'save',
                        'id' => 'et_save'
                    ]
                );
                ?>

                <?= CHtml::submitButton('Cancel', [
                        'class' => 'button large',
                        'name' => 'cancel',
                        'id' => 'et_cancel'
                    ]
                );
                ?>
            </td>
        </tr>
        </tfoot>
    </table>

    <?php $this->endWidget() ?>
</div>
