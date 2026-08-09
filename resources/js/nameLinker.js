import { template } from 'lodash';


// noinspection JSUnresolvedReference
/**
 * @type { byName: Object<string, string>, byNameKey: Object<string, string> }
 */
const playerlist = window.playerlist;

// the template for making the name into a link
const tmpl = template('<a href="<%= url %>" title="view player"><%= title %></a>');

/**
 * Matches cap number (and it's variants) plus name
 * $1 = #[cap number]
 * $2 = Name
 * @type {RegExp}
 */
const regex = /(#\d{1,2}(?:(?:[a-zA-Z]|\/)?\d{0,2})?) ((?:\b\w+) (?:\b\w+))/g;

export function linker(str) {
    return str.replace(regex, replace);
}

function replace(match, cap, name, offset, string) {
    const url = playerlist.byName?.[name] ?? false;
    if (url) {
        return tmpl({ url, title: match });
    } else {
        return match;
    }
}

export function matcher(str) {
    let matched;
    const nameKeys = [];

    while ((matched = regex.exec(str)) !== null) {
        const name = matched[2];
        const url = playerlist?.byName?.[name] ?? false;
        if (url) {
            nameKeys.push(url.replace('/players/', ''));
        }
    }

    return nameKeys;
}

export function finder(str) {
    let matched;
    const found = [];

    while ((matched = regex.exec(str)) !== null) {
        found.push(matched);
    }

    return found;
}
