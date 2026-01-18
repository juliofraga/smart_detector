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
                message: errors.response.data.message,
                data: errors.response.data.errors
            };
            cleanFeedbackMessage(obj);
        })
}

export function axiosPatch(url, data, obj) {
    axios.patch(url, data)
        .then(response => {
            obj.status = 'success';
            obj.feedbackTitle = "Dados atualizados com sucesso";
            closeModal('modalUpdate');
            if (typeof obj.loadList === 'function') {
                obj.loadList();
            }
            if (typeof obj.cleanAddFormData === 'function') {
                obj.cleanAddFormData();
            }
        })
        .catch(errors => {
            obj.status = 'error';
            obj.feedbackTitle = "Erro ao atualizar dados";
            closeModal('modalUpdate');
            obj.feedbackMessage = {
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

export function axiosGet(url, obj, attr, callback = null) {
    axios.get(url)
        .then(response => {
            obj[attr] = response.data;
            obj.loaded = true;
            if (callback) callback(response.data);
        })
        .catch(errors => {
            if (errors.response?.status == 500) {
                obj.feedbackTitle = "Erro no servidor";
                obj.status = 'error';
                obj.feedbackMessage = {message: "Desculpe, não conseguimos processar a sua requisição, tente novamente ou entre em contato com a equipe de suporte"}
            } else {
                obj.feedbackTitle = "Houve um erro";
                obj.status = 'error';
                obj.feedbackMessage = errors;
            }
        });
}

export function cleanFeedbackMessage(obj) {
    setTimeout(() => {
        obj.feedbackMessage =  {};
        obj.feedbackTitle = '';
        obj.status = '';
    }, 10000);
};

export function cleanAddFormData(obj, attribute) {
    attribute.forEach(attr => {
        if (obj.hasOwnProperty(attr)) {
            obj[attr] = '';
        }
    });
}

export function showRequiredFieldMessage(el) {
    document.getElementById(el).classList.add('is-invalid');
}

export function removeRequiredFieldMessage(elements) {
    elements.forEach(el => {
        if (document.getElementById(el).classList.contains('is-invalid')) {
            document.getElementById(el).classList.remove('is-invalid');
        }
    });   
}

export function getCurrentDateTime() {
    const now = new Date();
    const year   = now.getFullYear();
    const month  = String(now.getMonth() + 1).padStart(2, '0');
    const day    = String(now.getDate()).padStart(2, '0');
    const hour   = String(now.getHours()).padStart(2, '0');
    const minute = String(now.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day} ${hour}:${minute}`
}

export function getDateTimeOneWeekAgo() {
    const now = new Date();
    const oneWeekAgo = new Date(now.getTime() - 7 * 24 * 60 * 60 * 1000);
    const year   = oneWeekAgo.getFullYear();
    const month  = String(oneWeekAgo.getMonth() + 1).padStart(2, '0');
    const day    = String(oneWeekAgo.getDate()).padStart(2, '0');
    const hour   = String(oneWeekAgo.getHours()).padStart(2, '0');
    const minute = String(oneWeekAgo.getMinutes()).padStart(2, '0');
    return `${year}-${month}-${day} ${hour}:${minute}`;
}

export function loadTranslations(obj, domain, attr) {
    let url = API_URL + '/api/translation/' + domain;
    axiosGet(url, obj, attr);
}