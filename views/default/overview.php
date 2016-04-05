<?php
use dosamigos\chartjs\ChartJs;
use jackh\dashboard\System;

$system = new System;
$disk   = $system->disk_usage();
?>
<div class="row">
<div class="col-sm-12">
<?=ChartJs::widget([
    'type'          => 'Line',
    'options'       => [
        'style' => "width: 100%; height: 250px",
    ],
    'clientOptions' => [
        'pointDot'       => false,
        'scaleFontColor' => '#CCCCCC',
    ],
    'data'          => [
        'labels'   => ["January", "February", "March", "April", "May", "June", "July"],
        'datasets' => [
            [
                'fillColor'   => "rgba(220,220,220,0)",
                'strokeColor' => "rgb(5, 149, 255)",
                'data'        => [65, 59, 90, 81, 56, 55, 40],
            ],
        ],
    ],
]);
?>
</div>
</div>
<div class="row" style="margin-top: 50px">
<div class="col-sm-4">
<?=ChartJs::widget([
    'type'          => 'Doughnut',
    'options'       => [
        'style' => "width: 150px; height: 150px",
        'id'    => 'disk',
    ],
    'clientOptions' => [
        'tooltipTemplate'       => "<%if (label){%><%=label%>: <%}%><%= value %>GB",
        'animation'             => false,
        'percentageInnerCutout' => 70,
    ],
    'data'          => [
        [
            'value'     => (float) $disk["free_size"],
            'color'     => "rgb(5, 149, 255)",
            'highlight' => "rgb(5, 169, 255)",
            'label'     => "空闲空间",
        ],
        [
            'value'     => (float) $disk["totle_size"] - (float) $disk["free_size"],
            'color'     => "#DADADA",
            'highlight' => "#E0E0E0",
            'label'     => "已占用空间",
        ],
    ],
]);
?>
<?php
$this->registerJs('
    ;var legendHolder = document.createElement("div");
    legendHolder.innerHTML = chartJS_disk.generateLegend();
    $.each(legendHolder.firstChild.childNodes, function(legendNode, index){
        $(legendNode).on("mouseover", function(){
            var activeSegment = chartJS_disk.segments[index];
            activeSegment.save();
            activeSegment.fillColor = activeSegment.highlightColor;
            chartJS_disk.showTooltip([activeSegment]);
            activeSegment.restore();
        });
    });
    $(legendHolder.firstChild).on("mouseout", function(){
        chartJS_disk.draw();
    });
    $("#disk").parent().append(legendHolder.firstChild);
');
?>
</div>
</div>
