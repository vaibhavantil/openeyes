<?php
/**
 * (C) OpenEyes Foundation, 2014
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU Affero General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more details.
 * You should have received a copy of the GNU Affero General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @link http://www.openeyes.org.uk
 *
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (C) 2014, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/agpl-3.0.html The GNU Affero General Public License V3.0
 */
?>
<script src="<?= Yii::app()->assetManager->createUrl('js/oescape/oescape-plotly.js')?>"></script>
<script src="<?= Yii::app()->assetManager->createUrl('js/oescape/plotly-VA.js')?>"></script>

  <form action="#OphCiExamination_Episode_VisualAcuityHistory">
    <input name="subspecialty_id" value=<?= $this->subspecialty->id ?> type="hidden">
    <input name="patient_id" value=<?= $this->patient->id ?> type="hidden">
      <?= CHtml::dropDownList(
        'va_history_unit_id',
        $va_unit->id,
        CHtml::listData(
          OEModule\OphCiExamination\models\OphCiExamination_VisualAcuityUnit::model()->active()->findAll(),
          'id',
          'name')
      )?>
  </form>
<div id="js-hs-chart-VA" class="highchart-area" data-highcharts-chart="2" dir="ltr" style="min-width: 500px; left: 0px; top: 0px;">
  <div id="highcharts-VA-right" class="highcharts-VA highcharts-right highchart-section"></div>
  <div id="highcharts-VA-left" class="highcharts-VA highcharts-left highchart-section" style="display: none;"></div>
</div>
<script type="text/javascript">
  $(document).ready(function () {
    $('#va_history_unit_id').change(function () { this.form.submit(); });
    var va_ticks = <?= CJavaScript::encode($this->getVaTicks()); ?>;
    OEScape.full_va_ticks = va_ticks;
    var opnote_marking = <?= CJavaScript::encode($this->getOpnoteEvent()); ?>;
    var laser_marking = <?= CJavaScript::encode($this->getLaserEvent()); ?>;

    var sides = ['left', 'right'];

    //Plotly
    var va_plotly = <?= CJavaScript::encode($this->getPlotlyVaData()); ?>;

    var va_plotly_ticks = pruneYTicks(va_ticks, 800, 17);


    for (var side of sides){

      layout_plotly['shapes'] = [];
      layout_plotly['annotations'] = [];
      setMarkingEvents_plotly(layout_plotly, marker_line_plotly_options, marking_annotations, opnote_marking, side, -10, 120);
      setMarkingEvents_plotly(layout_plotly, marker_line_plotly_options, marking_annotations, laser_marking, side, -10, 120);

      var data =[{
        name: 'VA('+side+')',
        x: va_plotly[side]['x'].map(function (item) {
          return new Date(item);
        }),
        y: va_plotly[side]['y'],
        line: {
          color: (side=='right')?'#9fec6d':'#fe6767',
        },
        text: va_plotly[side]['x'].map(function (item, index) {
          var d = new Date(item);
          return OEScape.toolTipFormatters_plotly.VA( d, va_plotly[side]['y'][index], 'VA('+side+')');
        }),
        hoverinfo: 'text',
        type: 'line',
      }];
      var yaxis_options = {
        range: [-15, 150],
        tickvals: va_plotly_ticks['tick_position'],
        ticktext: va_plotly_ticks['tick_labels'],
      };
      layout_plotly['yaxis'] = setYAxis_VA(yaxis_options);
      layout_plotly['height'] = 800;
      layout_plotly['xaxis']['rangeslider'] = {};

      Plotly.newPlot(
        'highcharts-VA-'+side, data, layout_plotly, options_plotly
      );
    }
  });
</script>
