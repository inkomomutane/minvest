import {
    CalendarDate,
    CalendarDateTime,
    ZonedDateTime,
    getLocalTimeZone,
} from '@internationalized/date';

import currency from 'currency.js';
import { ref } from 'vue';


export const NUMBER = (
    value: any,
    precision: number = 6,
    symbol = '',
): currency => {
    return currency(value, {
        symbol: symbol + ' ',
        precision: precision,
    });
};

export function capitalizeText(text: string) {
    if (text) {
        // lowercase the text
        text = text.toLowerCase();
        // remove underscores and slashes
        text = text.replace(/[_/]/g, ' ');

        return text.charAt(0).toUpperCase() + text.slice(1);
    }

    return '';
}

export const formatDate = (
    date: CalendarDate,
    opts: Intl.DateTimeFormatOptions = {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    },
) =>
    new Intl.DateTimeFormat('en-GB', opts).format(
        date.toDate(getLocalTimeZone()),
    );

export const formatDateTime = (
    date: CalendarDateTime | ZonedDateTime,
    opts: Intl.DateTimeFormatOptions = {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    },
) =>
    new Intl.DateTimeFormat('en-GB', opts).format(
        date.toDate(getLocalTimeZone()),
    );

export const enumToArray = (enumObject: any) => {
    return Object.keys(enumObject).map((key) => enumObject[key]);
};

export const enumToArrayObjects = (enumObject: any) => {
    return Object.keys(enumObject).map((key) => {
        return {
            key: key,
            value: enumObject[key],
        };
    });
};

export const enumKeyOf = (enumObject: any, value: any) => {
    let response = null;

    for (const element of enumToArrayObjects(enumObject)) {
        if (element.value == value) {
            console.log('returning', element.key);
            response = element.key;
            break;
        }
    }

    return response;
};

export function crudManager<T>() {
    const isModalOpen = ref(false);
    const model = ref<T | null>();
    const open = (modelValue?: T) => {
        isModalOpen.value = true;
        model.value = modelValue;
    };

    const close = () => {
        model.value = null;
        isModalOpen.value = false;
    };

    return {
        isModalOpen,
        model,
        open,
        close,
    };
}
