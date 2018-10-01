<?php
/**
 * OpenEyes
 *
 * (C) OpenEyes Foundation, 2017
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public
 * License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later
 * version. OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the
 * implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details. You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled
 * COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2017, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
?>

<h2>Required Risk Set</h2>

<div class="cols-7">

    <?php
    $columns = array(
        'checkboxes' => array(
            'header' => '',
            'type' => 'raw',
            'value' => function ($data, $row) {
                return CHtml::checkBox(
                    "OEModule_OphCiExamination_models_OphCiExaminationRiskSet[][id]",
                    false,
                    ['value' => $data->id]
                );
            },
            'cssClassExpression' => '"checkbox"',
        ),
        'name',
        array(
            'header' => 'Subspecialty',
            'name' => 'subspecialty_id',
            'type' => 'raw',
            'value' => function ($data, $row) {
                return $data->subspecialty ? $data->subspecialty->name : null;
            },
        ),
        array(
            'header' => \Firm::contextLabel(),
            'name' => 'firm_id',
            'type' => 'raw',
            'value' => function ($data, $row) {
                return $data->firm ? $data->firm->name : null;
            }
        ),
    );

    $dataProvider = $model->search();
    $dataProvider->pagination = false;
    ?>

    <form id="generic-admin-form"><?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'dataProvider' => $dataProvider,
            'itemsCssClass' => 'generic-admin standard',
            //'template' => '{items}',
            "emptyTagName" => 'span',
            'summaryText' => false,
            'rowHtmlOptionsExpression' => 'array("data-row"=>$row)',
            'enableSorting' => false,
            'enablePagination' => false,
            'columns' => $columns,
            'rowHtmlOptionsExpression' => 'array("data-id" => $data->id)',
            'rowCssClass' => array('clickable'),
        ));
        ?>
    </form>

    <?php echo CHtml::button(
        'Add',
        [
            'class' => 'button large',
            'type' => 'submit',
            'name' => 'add',
            'data-uri' => '/OphCiExamination/oeadmin/RisksAssignment/create/',
            'id' => 'et_add'
        ]
    ); ?>

    <?php echo CHtml::button(
        'Delete',
        [
            'class' => 'button large',
            'type' => 'submit',
            'name' => 'delete',
            'data-uri' => '/OphCiExamination/oeadmin/RisksAssignment/delete',
            'id' => 'et_delete'
        ]
    ); ?>
</div>

<script>
    $(document).ready(function () {
        $('table.generic-admin tbody').on('click', 'tr td:not(".checkbox")', function () {
            var id = $(this).closest('tr').data('id');
            window.location.href = '/OphCiExamination/oeadmin/RisksAssignment/update/' + id;
        });
    });
</script>