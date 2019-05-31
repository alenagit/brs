
require('./bootstrap');

require('./datepicker');

window.Vue = require('vue');

var VueCookie = require('vue-cookie');
Vue.use(VueCookie);

import Vue from 'vue'

import BootstrapVue from 'bootstrap-vue'
Vue.use(BootstrapVue);



import taskcomponent from './components/TaskComponent.vue'




Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('chart-component', require('./components/ChartComponent.vue'));
Vue.component('task-component', require('./components/TaskComponent.vue').default);
Vue.component('task-five-component', require('./components/TaskFiveComponent.vue').default);


const app = new Vue({
    el: '#app',
    data: {
      tasks: [],
      ch_test: false,
      ch_main_test: false,
      total_score: 0,
      total_test_score: 0,
      total_main_test_score: 0,
      total_lesson_score: 0,
      total_columns: 'Укажите значение',
      options: [1,3,5,10,15,20,30],
      comment_op:['Показать','Скрыть'],
      selected: 10,
      selected_com: 'Показывать',
      click_op: ['Да', 'Нет'],
      select_click: 'Да'
    },
    mounted(){
      if(this.$cookie.get('total_columns') != "undefined")
      {
        this.selected = this.$cookie.get('total_columns');
      }

      if(this.$cookie.get('cookies_comment') != "undefined")
      {
        this.selected_com = this.$cookie.get('cookies_comment');
      }

      if(this.$cookie.get('cookies_click') != "undefined")
      {
        this.select_click = this.$cookie.get('cookies_click');
      }

  },
    methods: {
      cookies(){
        this.$cookie.set('total_columns', this.selected, {expires: 1, domain: 'localhost'});
        location.reload();
      },

      cookies_comment(){
        this.$cookie.set('cookies_comment', this.selected_com, {expires: 1, domain: 'localhost'});
        location.reload();
      },

      cookies_click(){
        this.$cookie.set('cookies_click', this.select_click, {expires: 1, domain: 'localhost'});
        location.reload();
      }

    }
})
