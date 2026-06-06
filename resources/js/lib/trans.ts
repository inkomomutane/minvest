import { createI18n } from 'vue-i18n';
import EN from '../../../lang/en.json';
import PT from '../../../lang/pt.json';

type MessageSchema = typeof EN;

const i18n = createI18n({
    legacy: false,
    locale: localStorage.getItem('lang') ?? 'EN',
    messages: {
        EN: EN,
        PT: PT,
    },
});
const t = (key: keyof MessageSchema|undefined|null): string => {
    const keyString = key ? String(key) : '';

    return i18n.global.t(keyString);
};

export { i18n, t };
