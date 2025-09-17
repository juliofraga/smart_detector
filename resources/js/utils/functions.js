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

export function axiosPost(url, data, obj) {
    axios.post(url, data)
        .then(response => {
            obj.status = 'success';
            obj.feedbackTitle = "Registro adicionado com sucesso";
            closeModal('modalAdd');
            obj.loadList();
            obj.cleanAddFormData();
        })
        .catch(errors => {
            obj.status = 'error';
            obj.feedbackTitle = "Erro ao registrar dados";
            closeModal('modalAdd');
            obj.feedbackMessage = {
                mensagem: errors.response.data.message,
                data: errors.response.data.errors
            };
            obj.cleanFeedbackMessage();
        })
}

export function axiosPatch(url, data, obj) {
    axios.patch(url, data)
        .then(response => {
            obj.status = 'success';
            obj.feedbackTitle = "Dados atualizados com sucesso";
            closeModal('modalUpdate');
            obj.loadList();
        })
        .catch(errors => {
            this.status = 'error';
            this.feedbackTitle = "Erro ao atualizar dados";
            closeModal('modalUpdate');
            this.feedbackMessage = {
                message: errors.response.data.message,
                data: errors.response.data.errors
            };
        })
}

export function axiosDelete(url, obj) {
    axios.delete(url)
        .then(response => {
            obj.status = 'success';
            obj.feedbackTitle = "Registro deletado com sucesso";
            closeModal('modalConfirmDelete');
            obj.loadList();
        })
        .catch(errors => {
            obj.status = 'error';
            obj.feedbackTitle = "Erro ao deletar registro";
            closeModal('modalConfirmDelete');
            obj.feedbackMessage = {
                message: errors.response.data.message,
                data: errors.response.data.errors
            };
        })

}