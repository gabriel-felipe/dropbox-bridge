var apiEndPoint = "http://localhost/dropbox-integration/api/";

var routes = [
  { path: '/', component: Login },
  { path: '/dropbox', component: Dropbox },
  { path: '/select-folder', component: SelectFolder },
];

var router = new VueRouter({
  routes: routes,
  scrollBehavior: function(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { x: 0, y: 0 }
    }
  }
});


var app = new Vue({
  router: router,
  data: {
    user: {
      name: null
    }
  },
  methods: {
    updateUser: function(user){
      console.log(user);
      console.log("UPDATING USER");
      this.user = user;
    }
  }
}).$mount('#app');
