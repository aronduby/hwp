(function () {
    'use strict';

    const _ = require('lodash');
    const moment = require('moment');
    const Litepicker = require('litepicker').Litepicker;
    const mobileFriendly = require('litepicker/dist/plugins/mobilefriendly');
    const charting = require('./charting');

    const goalieFields = {
        'saves': 0,
        'goals_allowed': 0,
        'advantage_goals_allowed': 0,
        'five_meters_taken_on': 0,
        'five_meters_blocked': 0,
        'five_meters_allowed': 0,
        'shoot_out_taken_on': 0,
        'shoot_out_blocked': 0,
        'shoot_out_allowed': 0,
    };
    const goalieFieldKeys = Object.keys(goalieFields);
    const fieldFields = {
        'goals': 0,
        'shots': 0,
        'assists': 0,
        'steals': 0,
        'turnovers': 0,
        'blocks': 0,
        'kickouts': 0,
        'kickouts_drawn': 0,
        'advantage_goals': 0,
        'five_meters_called': 0,
        'five_meters_drawn': 0,
        'five_meters_taken': 0,
        'five_meters_made': 0,
        'sprints_taken': 0,
        'sprints_won': 0,
        'shoot_out_taken': 0,
        'shoot_out_made': 0,
    };
    const fieldFieldKeys = Object.keys(fieldFields);
    const goalieGeneratedFields = {
        save_percent: (stat) => percent(stat.saves, stat.saves + stat.goals_allowed),
        five_meters_missed: (stat) => stat.five_meters_taken_on - stat.five_meters_blocked - stat.five_meters_allowed,
        // has to be after five_meters_missed
        five_meters_save_percent: (stat) => percent((stat.five_meters_missed + stat.five_meters_blocked), stat.five_meters_taken_on),
        shoot_out_missed: (stat) => stat.shoot_out_taken_on - stat.shoot_out_blocked - stat.shoot_out_allowed,
        // has to be after shoot_out_missed
        shoot_out_save_percent: (stat) => percent((stat.shoot_out_missed + stat.shoot_out_blocked), stat.shoot_out_taken_on),
    };
    const fieldGeneratedFields = {
        shooting_percent: (stat) => percent(stat.goals, stat.shots),
        steals_to_turnovers: (stat) => stat.steals - stat.turnovers,
        kickouts_drawn_to_called: (stat) => stat.kickouts_drawn - stat.kickouts,
        sprints_percent: (stat) => percent(stat.sprints_won, stat.sprints_taken),
        five_meters_percent: (stat) => percent(stat.five_meters_made, stat.five_meters_taken),
        shoot_out_percent: (stat) => percent(stat.shoot_out_made, stat.shoot_out_taken),
    };

    const positions = ['goalie', 'field'];
    const teams = ['JV', 'V'];

    const chartsLoading = charting.initCharts();

    const data = JSON.parse(document.getElementById('statsData').textContent);
    // convert players to be objects keyed by the id
    const playerList = JSON.parse(document.getElementById('playersData').textContent);
    const players = teams.reduce((acc, team) => {
        playerList[team].forEach(playerSeason => {
            playerSeason.link = makePlayerLink(playerSeason);
            acc[playerSeason.player_id] = playerSeason;
        });
        return acc;
    }, {});

    // row templates
    const goalieRowTemplate = _.template(document.getElementById('goalieRow').value);
    const fieldRowTemplate = _.template(document.getElementById('fieldRow').value);

    const page = document.querySelector('.page--stats');

    const dateRangePicker = new Litepicker({
        element: document.getElementById('dateRangePicker'),
        inlineMode: true,
        singleMode: false,
        firstDay: 0,
        numberOfMonths: 4,
        numberOfColumns: 4,
        minDate: data.minDate,
        maxDate: data.maxDate,
        startDate: data.start,
        endDate: data.end,
        switchingMonths: 1,
        highlightedDays: Array.from(new Set(data.stats.map(z => z.start))),
        plugins: ['mobilefriendly'],
        mobilefriendly: {
            breakpoint: 1024,
        }
    });

    dateRangePicker.on('selected', (start, end) => {
        drawDateRange(start.timestamp(), end.timestamp());
    });
    window.drp = dateRangePicker;

    /**
     * Date range auto selector buttons
     */
    document.querySelectorAll('.dateRange-buttons button').forEach(btn => {
        let start, end;

        switch(btn.dataset.range) {
            case 'allSeason':
            default:
                start = data.minDate;
                end = data.maxDate;
                break;

            case 'thisWeek':
                start = moment().startOf('week').unix() * 1000;
                end = moment().endOf('week').unix() * 1000;
                break;

            case 'lastWeek':
                const startAt = moment().subtract(7, 'days');
                start = startAt.startOf('week').unix() * 1000;
                end = startAt.endOf('week').unix() * 1000;
                break;
        }

        if (
            start < data.minDate || start > data.maxDate
            || end < data.minDate || end > data.maxDate
        ) {
            btn.disabled = true;
        }

        btn.dataset.start = start;
        btn.dataset.end = end;
    });

    page.querySelector('.dateRange-buttons').addEventListener('click', (e) => {
        let btn;
        const path = e.composedPath();
        for (let el of path) {
            if (el.matches('button.btn')) {
                btn = el;
                break;
            } else if (el === e.currentTarget) {
                break;
            }
        }

        if (btn) {
            dateRangePicker.setDateRange(btn.dataset.start, btn.dataset.end);
        }
    });


    drawDateRange(data.start, data.end);

    function drawDateRange(start, end) {
        page.classList.add('loading');

        const formattedData = calculateStats(data.stats, start, end, players);
        const isEmpty = ! positions.some(position => {
            return teams.some(team => formattedData[position][team].players.length > 0);
        });

        if (isEmpty) {
            page.classList.add('empty');
        } else {
            drawTables(formattedData);
            drawCharts(formattedData);
            page.classList.remove('empty');
        }

        page.classList.remove('loading');
    }

    function drawTables(formattedData) {
        [
            ['goalie', goalieRowTemplate],
            ['field', fieldRowTemplate]
        ].forEach(pd => {
            const position = pd[0];
            const template = pd[1];

            teams.forEach(team => {
                const table = document.querySelector(`table[data-team="${team}"][data-position="${position}"]`);
                const tbody = table.querySelector('tbody');
                const tfoot = table.querySelector('tfoot');

                tbody.innerHTML = formattedData[position][team].players.reduce((html, stats) => {
                    return html += template(stats);
                }, '');

                tfoot.innerHTML = template(Object.assign({playerSeason: {link: 'Totals'}}, formattedData[position][team].totals));
            });
        })

    }

    function drawCharts(formattedData) {
        const totals = Object.assign({}, formattedData.field.totals, formattedData.goalie.totals);
        page.querySelectorAll('.charts [data-field]').forEach(el => {
            const field = el.dataset.field;
            el.textContent = totals[field];
        });

        chartsLoading.then(() => charting.drawCharts(totals));
    }

    /**
     * {
     *     [position]: {
     *          totals: {},
     *         [team] {
     *             totals: {}
     *             players: [{}]
     *         }
     *     }
     * }
     */
    function calculateStats(stats, start, end, players) {
        const calculated = stats.reduce((acc, stat) => {
            if (stat.start < start || stat.start > end) {
                return acc;
            }

            const baseObject = {
                playerSeason: players[stat.player_id] || { link: 'Unknown - ' + stat.player_id },
                player_id: stat.player_id
            };

            const hasGoalieStats = goalieFieldKeys.some(fld => stat[fld] !== 0 && stat[fld] !== null);
            if (hasGoalieStats) {
                acc.goalie[stat.team].players[stat.player_id] = goalieFieldKeys.reduce((playerStats, gfk) => {
                    const s = stat[gfk];

                    playerStats[gfk] = (playerStats[gfk] || 0) + s;
                    acc.goalie.totals[gfk] += s;
                    acc.goalie[stat.team].totals[gfk] += s;

                    return playerStats;
                }, acc.goalie[stat.team].players[stat.player_id] || Object.assign({}, baseObject));
            }

            acc.field[stat.team].players[stat.player_id] = fieldFieldKeys.reduce((playerStats, ffk) => {
                const s = stat[ffk];

                playerStats[ffk] = (playerStats[ffk] || 0) + s;
                acc.field.totals[ffk] += s;
                acc.field[stat.team].totals[ffk] += s;

                return playerStats;
            }, acc.field[stat.team].players[stat.player_id] || Object.assign({}, baseObject));

            return acc;
        }, {
            'goalie': {
                totals: Object.assign({}, goalieFields),
                JV: {
                    totals: Object.assign({}, goalieFields),
                    players: {},
                },
                V: {
                    totals: Object.assign({}, goalieFields),
                    players: {},
                }
            },
            'field': {
                totals: Object.assign({}, fieldFields),
                JV: {
                    totals: Object.assign({}, fieldFields),
                    players: {},
                },
                V: {
                    totals: Object.assign({}, fieldFields),
                    players: {},
                }
            }
        });

        // now loop through and set the calculated stats
        [
            ['goalie', goalieGeneratedFields],
            ['field', fieldGeneratedFields]
        ].forEach((positionData) => {
            const positionKey = positionData[0];
            const positionGeneratedFields = positionData[1];

            calculated[positionKey].totals = mergeGeneratedFields(calculated[positionKey].totals, positionGeneratedFields);

            teams.forEach((team) => {
                calculated[positionKey][team].totals = mergeGeneratedFields(calculated[positionKey][team].totals, positionGeneratedFields);
                Object.keys(calculated[positionKey][team].players).forEach((playerId) => {
                    calculated[positionKey][team].players[playerId] = mergeGeneratedFields(calculated[positionKey][team].players[playerId], positionGeneratedFields);
                });
            });
        });

        // turn the players objects into sorted arrays
        positions.forEach(position => {
            teams.forEach(team => {
                calculated[position][team].players = Object.values(calculated[position][team].players).sort((a, b) => {
                    return a.playerSeason.link.localeCompare(b.playerSeason.link);
                });
            });
        });

        return calculated;
    }

    function mergeGeneratedFields(stats, generatedFields) {
        return Object.keys(generatedFields).reduce((acc, generateFieldKey) => {
            acc[generateFieldKey] = generatedFields[generateFieldKey](acc);
            return acc;
        }, stats);
    }

    function percent(part, whole) {
        return (ratio(part, whole) * 100).toFixed(1) * 1;
    }

    function ratio(part, whole) {
        try {
            const num = (part / whole);
            return Number.isNaN(num) ? 0 : num;
        } catch (e) {
            return 0;
        }
    }

    function makePlayerLink(playerSeason) {
        const name = `${playerSeason.player.first_name} ${playerSeason.player.last_name}`;
        return `<a href="/players/${playerSeason.player.name_key}">#${playerSeason.number} ${name}</a>`;
    }

})();