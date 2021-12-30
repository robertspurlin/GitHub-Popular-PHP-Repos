require('./bootstrap');

Vue.config.devtools = process.env.NODE_ENV === 'development';

Vue.component('github', require('./components/GitHub.vue').default);

const app = new Vue({
	el: "#app"
});
