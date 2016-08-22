<?php
/**
 * Created by PhpStorm.
 * User: deanar
 * Date: 07/08/16
 * Time: 23:57
 */

/**
 * @var \yii\web\View $this
 * @var array $result
 * @var array $projects
 * @var array $projects_array
 * @var array $time_by_project
 * @var array $logged_time_data
 */

?>

<script>
    //    window.time_by_day_by_project = <?//=json_encode($result)?>//;
    window.w = {
        projects: <?=json_encode($projects_array)?>,
        time_by_project: <?=json_encode($time_by_project)?>,
        logged_time_data: <?=json_encode(array_values($logged_time_data))?>,
    };
    window.WakaTimeProjectColors = [

        "#3581ba",

        "#2ca02c",

        "#dc9658",

        "#d62728",

        "#9467bd",

        "#8c564b",

        "#aec7e8",

        "#e377c2",

        "#f7b6d2",

        "#7f7f7f",

        "#c7c7c7",

        "#bcbd22",

        "#dbdb8d",

        "#17becf",

        "#9edae5",

        "#ffbb78",

        "#98df8a",

        "#ff9896",

        "#c5b0d5",

        "#c49c94",

    ];
</script>

<div class="chart-box">
    <div id="coding-activity">
        <div class="graph"></div>
    </div>
</div>


<script id="projects-tooltip-template" type="x-tmpl-mustache">
    <table class="c3-tooltip">
      <tr>
        <th colspan="2">{{header}}</th>
      </tr>
      <tr>
        <td class="name"><span style="background-color:{{color}}"></span><b>Total</b></td>
        <td class="value">{{total.formatted}}</td>
      </tr>
      {{#projects}}
        <tr>
          <td class="name"><span style="background-color:{{color}}"></span>{{project}}</td>
          <td class="value">{{formatted}}</td>
        </tr>
      {{/projects}}
    </table>

</script>