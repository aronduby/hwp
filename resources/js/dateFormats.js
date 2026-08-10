import moment from "moment";
import "./moment.phpformat";

/**
 * Basically the App\Providers\DateDirectiveServiceProvider in Moment.js
 * @type {{}}
 */
export const formats = {
    DAY: 'l',
    DAY_SHORT: 'D',
    DATE: 'n/j',
    STAMP: 'M jS \@ g:ia',
    DATE_SPAN: 'M j',
    TIME: 'g:ia',
    ISO: 'c',
};

export function day(m, full) {
    const today = moment().startOf('date');
    const diff = m.startOf('date').diff(today, 'days');

    if (diff === 0) {
        return 'today';
    } else if (diff < 7) {
        return m.formatPHP(full === false ? formats.DAY_SHORT : formats.DAY);
    } else {
        return datWithDate(m, full);
    }
}

export function date(m) {
    return m.formatPHP(formats.DATE);
}

export function dayWithDate(m) {
    const format = `${full === false ? formats.DAY_SHORT : formats.DAY} ${formats.DATE}`;
    return m.formatPHP(format);
}

export function dayWithDateTime(m) {
    const day = dayWithDate(m, false);
    const midnight = day.startOf('date');
    let dayTime;

    if (day.diff(midnight) > 0) {
        dayTime = ` @ ${time(m)}`;
    } else {
        dayTime = ' all day';
    }

    return day + dayTime;
}

export function dateSpan(from, to) {
    return `${from.formatPHP(formats.DATE_SPAN)} &ndash; ${to.formatPHP(formats.DATE_SPAN)}`;
}

export function time(m) {
    return m.formatPHP(formats.TIME);
}

export function stamp(m) {
    return m.formatPHP(formats.STAMP);
}

export function iso(m) {
    return m.formatPHP(formats.ISO);
}

