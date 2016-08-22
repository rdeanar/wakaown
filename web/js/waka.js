/**
 * Created by deanar on 22/08/16.
 */

var options = {color_scheme: 'Dark' };

var renderLoggedTimeByProjectChart = function(logged_time_data, time_by_project, projects) {
    var max = Math.ceil(_.max(logged_time_data, function(item) {
        return item.value;
    }).value);
    max += max % 2;
    var totalColumn = ['<b>Total</b>'].concat(_.map(logged_time_data, function(item) {
        return item.value;
    }));
    var projectColumns = _.map(time_by_project, function(data, key) {
        return [key.toString()].concat(_.map(data, function(item) {
            return item.value;
        }));
    });
    var sortedColumns = _.sortBy(projectColumns, function(column) {
        return projects[column[0]].total;
    }).reverse();
    sortedColumns = [totalColumn].concat(sortedColumns);
    var colors = {
        '<b>Total</b>': options.color_scheme == 'Dark' ? '#FFF' : '#000',
    };
    _.each(sortedColumns, function(column, index) {
        if (index > 0) {
            colors[column[0]] = window.WakaTimeProjectColors[index - 1 % window.WakaTimeProjectColors.length];
        }
    });
    _.each(time_by_project, function(day) {
        _.each(day, function(proj) {
            proj.color = colors[proj.project.toString()];
        });
    });
    var groups = _.map(projectColumns, function(column) {
        return column[0];
    });
    var types = _.object(_.map(sortedColumns, function(column, index) {
        return [column[0], index == 0 ? 'spline' : 'area-step'];
    }));
    var data = {
        columns: sortedColumns,
        groups: [['<b>Total</b>'], groups],
        types: types,
        colors: colors,
        onclick: $.proxy(function(d, i) {
            window.location.href = '/dashboard/day?date=' + encodeURIComponent(logged_time_data[d.index].date);
        }, this),
    };

    console.log(data);

    var logged_time_by_project_chart = c3.generate({
        data: data,
        bindto: '#coding-activity .graph',
        axis: {
            x: {
                padding: {
                    left: 0.04,
                    right: 0.04
                },
                tick: {
                    format: function(x) {
                        return logged_time_data[x].xAxis;
                    },
                },
            },
            y: {
                min: 0,
                ticks: 4,
                padding: {
                    bottom: 10
                },
                label: {
                    text: 'Hours',
                    position: 'outer-middle',
                },
            },
        },
        tooltip: {
            contents: $.proxy(function(cols) {
                var total = _.find(cols, function(col) {
                    return col.id == '<b>Total</b>';
                });
                cols = _.filter(cols, function(col) {
                    return col.id != '<b>Total</b>';
                });
                cols = _.filter(cols, function(col) {
                    return time_by_project[col.id.toString()][col.index].total_seconds >= 60;
                });
                var html = $('#projects-tooltip-template').html();
                return Mustache.render(html, {
                    header: logged_time_data[total.index].name,
                    total: logged_time_data[total.index],
                    color: colors['<b>Total</b>'],
                    projects: _.map(cols, function(col) {
                        return time_by_project[col.id.toString()][col.index];
                    }),
                });
            }, this),
        },
        grid: {
            y: {
                lines: _.map(d3.scale.linear().domain([0, max]).ticks(4), function(tick) {
                    return {
                        value: tick,
                        class: 'c3-ygrid'
                    };
                }),
            },
        },
        legend: {
            show: false,
        },
        point: {
            r: 4,
            focus: {
                expand: {
                    r: 6,
                },
            },
        },
        size: {
            height: 170
        },
        padding: {
            right: 20,
            left: 42
        },
    });

    // $('#coding-activity .c3-circle').hover(function() {
    //     $(this).attr('r', '6');
    // }, function() {
    //     $(this).attr('r', '4');
    // });
    // $('#coding-activity .c3-circle').click($.proxy(function(e) {
    //     var $circle = $(e.target);
    //     window.location.href = '/dashboard/day?date=' + encodeURIComponent(this.getDay($circle));
    // }, this));
};


$(document).ready(function () {

    //renderLoggedTimeByProjectChart();

    renderLoggedTimeByProjectChart(window.w.logged_time_data, window.w.time_by_project, window.w.projects);

});