/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;

import Vuex from 'Vuex';
Vue.use(Vuex);
const store = new Vuex.Store({
    state: {
        item: {
            profile: {
                id: null,
                description: ''
            }
        }
    }
});

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('account-component', require('./components/Account.vue').default);
Vue.component('alert-component', require('./components/Alert.vue').default);
Vue.component('classification-component', require('./components/Classification.vue').default);
Vue.component('event-summary-component', require('./components/EventSummary.vue').default);
Vue.component('event-table-component', require('./components/EventTable.vue').default);
Vue.component('event-modal-component', require('./components/EventModal.vue').default);
Vue.component('event-detailed-component', require('./components/EventDetailed.vue').default);
Vue.component('events-component', require('./components/Events.vue').default);
Vue.component('login-component', require('./components/Login.vue').default);
Vue.component('home-component', require('./components/Home.vue').default);
Vue.component('table-component', require('./components/Table.vue').default);
Vue.component('modal-component', require('./components/Modal.vue').default);
Vue.component('modal-delete-component', require('./components/ModalDelete.vue').default);
Vue.component('no-itens-component', require('./components/NoItens.vue').default);
Vue.component('search-component', require('./components/Search.vue').default);
Vue.component('users-component', require('./components/Users.vue').default);
Vue.component('paginate-component', require('./components/Paginate.vue').default);
Vue.component('spinner-component', require('./components/Spinner.vue').default);
Vue.component('type-component', require('./components/Type.vue').default);
Vue.component('updates-button-component', require('./components/UpdatesButton.vue').default);
Vue.component('welcome-component', require('./components/Welcome.vue').default);

Vue.filter('formatDateTime', function(valor){
    if (!valor) {
        return '-';
    }
    valor = valor.split(' ');
    let data;
    let dataFormatada;

    data = valor[0];
    data = data.split('-');
    dataFormatada = data[2] + '/' + data[1] + '/' + data[0];

    return dataFormatada + ' - ' + valor[1];
});

Vue.filter('formatDateTimeStamp', function(valor){
    if (!valor) {
        return '-';
    }
    valor = valor.split('T');
    
    let data = valor[0];
    let hora = valor[1];
    let dataFormatada;

    data = data.split('-');
    dataFormatada = data[2] + '/' + data[1] + '/' + data[0];

    hora = hora.split('.')[0];
    return dataFormatada + ' - ' + hora;
})

Vue.filter('formatNextPrevButton', function(valor) {
    if (valor === '&laquo; Previous') {
        return '<< Anterior';
    } else if (valor === 'Next &raquo;') {
        return 'Próximo >>';
    }
    return valor;
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store
});
