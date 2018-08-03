<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2017
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2017, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */

use OEModule\OphCiExamination\models\PastSurgery_Operation;

?>

<?php
if (!isset($values)) {
    $values = array(
        'id' => $op->id,
        'operation' => $op->operation,
        'side_id' => $op->side_id,
        'side_display' => $op->side ? $op->side->adjective : 'None',
        'date' => $op->date,
        'date_display' => $op->getDisplayDate(),
        'had_operation' => $op->had_operation
    );
}
$required = isset($required) ? $required : false;

if (isset($values['date']) && strtotime($values['date'])) {
    list($sel_year, $sel_month, $sel_day) = array_pad(explode('-', $values['date']), 3, 0);
} else {
    $sel_day = $sel_month = null;
    $sel_year = date('Y');
}

?>
<tr class="row-<?=$row_count;?><?php if($removable){ echo " read-only"; } ?>"
    <?= $removable ? "data-key='{$row_count}'" : ''; ?>
    id="<?= $model_name ?>_operations_<?=$row_count?>"
>
    <td>
        <?php if (!$removable) : ?>
            <?= $values['operation'] ?>
        <?php else : ?>
            <?php echo CHtml::textField($field_prefix . '[operation]', $values['operation'], array(
                'placeholder' => 'Click the green plus or type',
                'autocomplete' => Yii::app()->params['html_autocomplete'],
                'class' => 'common-operation',
            )); ?>
            <input type="hidden" name="<?= $field_prefix ?>[id]" value="<?=$values['id'] ?>" />
        <?php endif; ?>
    </td>
    <td class="past-surgery-entry has-operation">
        <label class="inline highlight">
            <?php echo CHtml::radioButton(
                $field_prefix . '[had_operation]',
                $posted_not_checked,
                array('value' => PastSurgery_Operation::$NOT_CHECKED)
            ); ?>
            Not checked
        </label>
        <label class="inline highlight">
            <?php echo CHtml::radioButton(
                $field_prefix . '[had_operation]',
                $values['had_operation'] === (string) PastSurgery_Operation::$PRESENT,
                array('value' => PastSurgery_Operation::$PRESENT)
            ); ?>
            yes
        </label>
        <label class="inline highlight">
            <?php echo CHtml::radioButton(
                $field_prefix . '[had_operation]',
                $values['had_operation'] === (string) PastSurgery_Operation::$NOT_PRESENT,
                array('value' => PastSurgery_Operation::$NOT_PRESENT)
            ); ?>
            no
        </label>
    </td>
    <?php if (!$removable) : ?>
        <td class="<?= $model_name ?>_sides" style="white-space:nowrap">
            <?php if ($values['side'] == 'Right'||$values['side'] == 'Both') { ?>
                <i class="oe-i laterality R small pad"></i>
            <?php } ?>
        </td>
        <td class="<?= $model_name ?>_sides" style="white-space:nowrap">
            <?php if ($values['side'] == 'Left'||$values['side'] == 'Both') { ?>
                <i class="oe-i laterality L small pad"></i>
            <?php } ?>
        </td>
        <td></td>
        <td></td>
    <?php else :?>
        <?php $this->widget('application.widgets.eyeSelector', [
            'inputNamePrefix' => $field_prefix,
            'selectedEyeId' => $values['side_id']
        ]); ?>
    <?php endif; ?>

    <td>
        <?php if (!$removable) :?>
            <?=Helper::formatFuzzyDate($values['date']) ?>
        <?php else :?>
        <?php /* I have seen a css class instead of this (???) style="width:90px" */ ?>
            <input id="past-surgery-datepicker-<?= $row_count ?>" style="width:90px"
                   class="date"
                   placeholder="yyyy-mm-dd"
                   name="<?= $field_prefix ?>[date]" value="<?=$values['date'] ?>" autocomplete="off">
            <i class="js-has-tooltip oe-i info small pad right"
               data-tooltip-content="You can enter date format as yyyy-mm-dd, or yyyy-mm or yyyy."></i>
        <?php endif; ?>
    </td>
    <?php if ($removable && !$required) : ?>
        <td>
            <i class="oe-i trash remove_item"></i>
        </td>
    <?php elseif (!$required) : ?>
        <td>read only
            <i class="js-has-tooltip oe-i info small pad right"
               data-tooltip-content="This operation is recorded as an Operation Note event in OpenEyes and cannot be edited here"></i></td>
    <?php elseif ($required) : ?>
        <td>mandatory <i class="js-has-tooltip oe-i info small pad right"
                         data-tooltip-content="<?=$values['operation'];?> is mandatory to collect."></i></td>
    <?php endif; ?>
</tr>