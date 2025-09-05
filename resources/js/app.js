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
        item: {}
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

Vue.component('alert-component', require('./components/Alert.vue').default);
Vue.component('login-component', require('./components/Login.vue').default);
Vue.component('home-component', require('./components/Home.vue').default);
Vue.component('list-component', require('./components/List.vue').default);
Vue.component('modal-component', require('./components/Modal.vue').default);
Vue.component('search-component', require('./components/Search.vue').default);
Vue.component('users-component', require('./components/Users.vue').default);

Vue.filter('formatProfile', function(valor) {
    let profile = {
        'admin': 'Administrador',
        'user': 'Usuário'
    };
    return profile[valor];
});

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
})

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    store
});
