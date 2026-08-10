import * as charting from './charting';

charting.initCharts()
    .then(() => charting.drawCharts(window.stats ?? {}));
