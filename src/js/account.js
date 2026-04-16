document.addEventListener('DOMContentLoaded', () => {
    initInputFormatting();
});

// ==================== INPUT FORMATTING ====================
const LOWERCASE_WORDS = ['de', 'del', 'la', 'las', 'los', 'el', 'y', 'e'];

const toTitleCase = (str) => {
    return str.toLowerCase().split(' ').map((word, index) => {
        if (index > 0 && LOWERCASE_WORDS.includes(word)) return word;
        return word.charAt(0).toUpperCase() + word.slice(1);
    }).join(' ');
}

const toSentenceCase = (str) => {
    if (!str.length) return str;
    return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
}

const onlyDigits = (str, maxLength) => {
    const digits = str.replace(/\D/g, '');
    return maxLength ? digits.slice(0, maxLength) : digits;
}

const initInputFormatting = () => {
    const fields = document.querySelectorAll('[data-format]');

    if (fields.length === 0) return;

    fields.forEach(field => {
        const format = field.dataset.format;

        field.addEventListener('input', () => {
            const cursorPos = field.selectionStart;
            const prevLength = field.value.length;

            switch (format) {
                case 'titlecase':
                    field.value = toTitleCase(field.value);
                    field.setSelectionRange(cursorPos, cursorPos);
                    break;
                case 'sentencecase':
                    field.value = toSentenceCase(field.value);
                    field.setSelectionRange(cursorPos, cursorPos);
                    break;
                case 'digits': {
                    const maxLen = field.dataset.maxlength ? parseInt(field.dataset.maxlength) : null;
                    field.value = onlyDigits(field.value, maxLen);
                    const newPos = Math.min(cursorPos, field.value.length);
                    field.setSelectionRange(newPos, newPos);
                    break;
                }
            }
        });
    });
}
