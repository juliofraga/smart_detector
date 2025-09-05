export const API_URL = 'http://localhost:8000';

export function closeModal(modal) {
    $('#' + modal).modal('hide')
};

export function showModal(modal) {
    $('#' + modal).modal('show')
};

export function fieldsValidate(fields, obj) {
    let isValid = true;

    fields.forEach(field => {
        const element = document.getElementById(field);
        const value = obj[field];
        if (!value || String(value).trim() === '') {
            element.classList.add('is-invalid');
            isValid = false;
        } else if (element.classList.contains('is-invalid')) {
            element.classList.remove('is-invalid');
        }
    });
    goToTop();
    return isValid;
};

export function goToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}