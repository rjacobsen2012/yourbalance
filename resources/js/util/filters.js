import Vue from 'vue'
import moment from 'moment'

/**
 * @param {String} value
 * @param {Number} count
 * @param {String} suffix
 */
export const pluralize = (value, count, suffix = 's') => {
    if (typeof value !== 'string' || typeof suffix !== 'string' || typeof count !== 'number' || count < 0) {
        return value
    }

    return count === 1 ? `${value}` : `${value}${suffix}`
}
Vue.filter('pluralize', pluralize)


/**
 * @param {String} value
 * @param {String} suffix
 */
export const depluralize = (value, suffix = 's') => {
    if (typeof value !== 'string' || typeof suffix !== 'string') {
        return value
    }

    return value.endsWith(suffix) ? value.substring(0, value.length - suffix.length) : value
}
Vue.filter('depluralize', depluralize)


/**
 * @param {String} text
 * @param {Number} length
 * @param {String} clamp
 */
export const truncate = (text, length, clamp) => {
    text = text || '';
    clamp = clamp || '...';
    length = length || 30;

    if (text.length <= length)
        return text;

    let tcText = text.slice(0, length - clamp.length);
    let last = tcText.length - 1;

    while (last > 0 && tcText[last] !== ' ' && tcText[last] !== clamp[0]) {
        last -= 1;
    }

    // Fix for case when text doesn't have any `space`
    last = last || length - clamp.length;

    tcText =  tcText.slice(0, last);

    return tcText + clamp;
}
Vue.filter('truncate', truncate)


/**
 * @param {String} value
 */
export const sluggify = value => {
    if (typeof value !== 'string') {
        return value
    }

    return value.toLowerCase()
        .replace(/[^\w\s-]/g, '')
        .replace(/[\s_-]+/g, '-')
        .replace(/^-+|-+$/g, '')
}
Vue.filter('sluggify', sluggify)


/**
 * @param {String} value
 * @param format
 * @param inFormat
 */
export const date = (value, format = null, inFormat = null) => {
    if (typeof value !== 'string') {
        return value
    }

    const parsed = moment.utc(value, inFormat)

    if (!parsed.isValid()) {
        return value
    }

    if (format === 'iso') {
        return parsed.toISOString()
    }

    if (format) {
        return parsed.format(format)
    }

    return parsed.fromNow()
}
Vue.filter('date', date)


/**
 * @param {Number} value
 */
Vue.filter('toCurrency', function (value, { format = 'en-US', currency = 'USD' } = {}) {
    if (typeof value !== 'number') {
        return value
    }

    const formatter = new Intl.NumberFormat(format, {
        style: 'currency',
        currency,
        minimumFractionDigits: 2,
    });
    return formatter.format(value)
})


/**
 * @param {Number} value
 */
Vue.filter('numberFormat', function (value) {
    if (typeof value !== 'number') {
        return value
    }

    const formatter = new Intl.NumberFormat('en-US');
    return formatter.format(value)
})


/**
 * @param {HTMLString} value
 */
Vue.filter('stripTags', function (value) {
    if (typeof value !== 'string') {
        return value
    }

    return value.replace(/<\/?[^>]+(>|$)/g, '')
})


/**
 * @param {String} value
 */
Vue.filter('convertEntities', function(value) {
    if (typeof value !== 'string') {
        return value
    }

    const element = document.createElement('div');

    if (value) {
        value = value.replace(/<script[^>]*>([\S\s]*?)<\/script>/gmi, '');
        value = value.replace(/<\/?\w(?:[^"'>]|"[^"]*"|'[^']*')*>/gmi, '');
        element.innerHTML = value;
        value = element.textContent;
        element.textContent = '';
    }

    return value
})
