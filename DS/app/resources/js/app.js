/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

require('./helperfunctions');

window.Vue = require('vue');
window.Vuetify = require('vuetify');

Vue.use(Vuetify, {
});
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component("Panel", require("./containers/Panel/Panel.vue").default);
Vue.component("Report", require("./containers/Report/Report.vue").default);
Vue.component("Settings", require("./containers/Settings/Settings.vue").default);
Vue.component("Maintenance", require("./containers/Maintenance/Maintenance.vue").default);
Vue.component('h-indicator', require('./components/IndicatorComponent.vue').default);
Vue.component('he-navbar', require('./components/HeNavbar.vue').default);
Vue.component('LoginForm',require('./components/LoginForm.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    created() {
        this.$vuetify.theme.primary= "#344669";
        this.$vuetify.theme.secondary= "#3C8376";

    }
});


// Atribui estilo de fonte padrao para o Chart.js
Chart.defaults.global.defaultFontColor = "#636b6f";
Chart.defaults.global.defaultFontFamily = "'Nunito', sans-serif";
