/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('flash', require('./components/FlashComponent.vue').default);
Vue.component('reply', require('./components/ReplyComponent.vue').default);
Vue.component('favorite', require('./components/FavoriteComponent.vue').default);
Vue.component('replies', require('./components/RepliesComponent.vue').default);
Vue.component('thread-view', require('./pages/Thread.vue').default);
Vue.component('new-reply', require('./components/NewReply.vue').default);
Vue.component('paginator', require('./components/PaginatorComponent.vue').default);
Vue.component('subscribe-button', require('./components/SubscribeButton.vue').default);
Vue.component('user-notifications', require('./components/UserNotifications.vue').default);
Vue.component('avatar-form', require('./components/AvatarComponent.vue').default);
Vue.component('search', require('./components/SearchComponent.vue').default);
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
 import InstantSearch from 'vue-instantsearch';

 Vue.use(InstantSearch);
 
 window.events = new Vue();
 window.flash = function(message, level='success'){window.events.$emit('flash', {message, level})};
 
 Vue.prototype.authorize = function(handler){
 	let user = window.App.user;

 	return user ? handler(user) : false;
 };

 Vue.prototype.signedIn = window.App.signedIn;

const app = new Vue({
    el: '#app',
});
