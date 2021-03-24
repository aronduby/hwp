(function() {
    'use strict';

    const _ = require('lodash');

    const headerArray = ['Stat', 'Value'];

    // setup the colors for the charts
    const base = '#cfcfcf',
        accent = '#2a82c9',
        accentAlt = '#f29800',
        colors = {
            default: [accent, base],
            negative: [base, accent],
            multiple: [accent, accentAlt, base]
        };

    // the default google chart options
    const defaultOptions = {
        width: '100%',
        height: '100%',
        chartArea: {
            top: '5%',
            left: '5%',
            width: '90%',
            height: '90%'
        },
        pieHole: 0.9,
        legend: 'none',
        backgroundColor: 'none',
        pieSliceText: 'none',
        pieSliceBorderColor: 'none'
    };

    let _stats = {};
    let _chartEls = [];
    let _charts = [];

    function initCharts() {
        return new Promise(resolve => {
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(function(){
                _chartEls = document.querySelectorAll('.stat[data-chart]');
                _chartEls.forEach(el => {
                    const id = _.uniqueId('chart');
                    el.id = id;
                    _charts[id] = new google.visualization.PieChart(el.querySelector('.stat-chart'));
                });

                resolve();

                // redraw charts when the window size changes
                window.onresize = () => drawCharts(_stats);
            });
        });
    }

    function drawCharts(stats) {
        _stats = stats;

        _chartEls.forEach(el => {
            const chartName = el.dataset.chart;
            const chart = _charts[el.id];

            const data = methods[chartName](stats);
            const chartOptions = Object.assign({}, defaultOptions);

            if (data.options) {
                if (data.options.negative) {
                    chartOptions.colors = colors.negative;
                } else if (data.options.multiple) {
                    chartOptions.colors = colors.multiple;
                }
            } else {
                chartOptions.colors = colors.default;
            }

            chart.draw(google.visualization.arrayToDataTable(data.data), chartOptions);
            el.classList.remove('stat--loading');

        });
    }

    function fiveMeterSaves(stats) {
        if (stats.five_meters_taken_on > 0) {
            return {
                options: {
                    multiple: true
                },
                data: [
                    ['Stat', 'Value'],
                    ['Blocked', stats.five_meters_blocked],
                    ['Missed', stats.five_meters_missed],
                    ['Allowed', stats.five_meters_allowed],
                ]
            };

        } else {
            return {
                options: {
                    negative: true
                },
                data: [
                    headerArray,
                    ['Blocked/Missed/Allowed', {
                        v: 1,
                        f: 0
                    }]
                ]
            };
        }
    }

    function assists(stats) {
        if (stats.goals > 0 || stats.assists > 0) {
            return {
                options: {
                    multiple: true
                },
                data: [
                    headerArray,
                    ['Goals', stats.goals],
                    ['Assists' ,stats.assists]
                ]
            };

        } else {
            return {
                options: {
                    negative: true
                },
                data: [
                    headerArray,
                    ['Goals/Assists', {
                        v: 1,
                        f: 0
                    }]
                ]
            };
        }
    }

    function kickouts(stats) {
        if (stats.kickouts_drawn > 0 || stats.kickouts > 0) {
            if (stats.kickouts_drawn > stats.kickouts) {
                // drew more, make it positive
                return {
                    data: [
                        headerArray,
                        ['Drawn', stats.kickouts_drawn],
                        ['Called', stats.kickouts]
                    ]
                };

            } else {
                // called more, make it negative
                return {
                    options: {
                        negative: true
                    },
                    data: [
                        headerArray,
                        ['Called', stats.kickouts],
                        ['Drawn', stats.kickouts_drawn]
                    ]
                };

            }
        } else {
            // nothing to show
            return {
                options: {
                    negative: true
                },
                data: [
                    headerArray,
                    ['v', 1],
                    ['f', 0]
                ]
            };
        }
    }

    function saves(stats) {
        if (stats.saves > 0 || stats.goals_allowed > 0) {
            return {
                data: [
                    headerArray,
                    ['Saves', stats.saves],
                    ['Goals Allowed', stats.goals_allowed]
                ]
            };

        } else {
            return {
                options: {
                    negative: true
                },
                data: [
                    headerArray,
                    ['Saves/Goals Allowed', {
                        v: 1,
                        f: 0
                    }]
                ]
            };
        }
    }

    function shootOutSaves(stats) {
        if (stats.shoot_out_taken_on) {
            return {
                options: {
                    multiple: true
                },
                data: [
                    headerArray,
                    ['Blocked', stats.shoot_out_blocked],
                    ['Missed', stats.shoot_out_missed],
                    ['Allowed', stats.shoot_out_allowed],
                ]
            };

        } else {
            return {
                options: {
                    negative: true
                },
                data: [
                    headerArray,
                    ['Blocked/Missed/Allowed', {
                        v: 1,
                        f: 0
                    }]
                ]
            };
        }
    }

    function shooting(stats) {
        if (stats.shots > 0) {
            return {
                data: [
                    headerArray,
                    ['Made', stats.goals],
                    ['Missed/Blocked', stats.shots - stats.goals]
                ]
            };
        } else {
            return {
                options: {
                    negative: true
                },
                data: [
                    headerArray,
                    ['Shots', {
                        v: 1,
                        f: 0
                    }]
                ]
            };
        }
    }

    function sprints(stats) {
        return {
            data: [
                headerArray,
                ['Won', stats.sprints_won],
                ['Lost', stats.sprints_taken - stats.sprints_won]
            ]
        };
    }

    function stealsToTurnovers(stats) {
        // at least oen steal or turnover
        if (stats.steals > 0 || stats.turnovers > 0) {

            // more steals than turnovers
            if (stats.steals > stats.turnovers) {
                return {
                    data: [
                        headerArray,
                        ['Steals', stats.steals],
                        ['Turnovers', stats.turnovers]
                    ]
                };

            } else {
                // make it negative
                return {
                    options: {
                        negative: true
                    },
                    data: [
                        headerArray,
                        ['Turnovers', stats.turnovers],
                        ['Steals', stats.steals]
                    ]
                };
            }

        } else {
            // no steals or turnovers
            return {
                options: {
                    negative: true
                },
                data: [
                    headerArray,
                    ['Steals/Turnovers', {
                        v: 1,
                        f: 0
                    }]
                ]
            };
        }
    }

    function thirstsQuenched(stats) {
        return {
            data: [
                headerArray,
                ['Thirsts', _.random(200, 500)],
                ['Quenched', 0]
            ]
        }
    }

    const methods = {
        initCharts,
        drawCharts,

        // individual charts
        fiveMeterSaves,
        assists,
        kickouts,
        saves,
        shootOutSaves,
        shooting,
        sprints,
        stealsToTurnovers,
        thirstsQuenched,
    };
    module.exports = methods;
})();