export const API_URL = 'http://localhost:8000';

export function closeModal(modal) {
    $('#' + modal).modal('hide')
};

export function showModal(modal) {
    $('#' + modal).modal('show')
};