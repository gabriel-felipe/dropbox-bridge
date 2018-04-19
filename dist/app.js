var Dropbox = Vue.component('dropbox',{
  template: '#dropbox-template',
  props: {
    user: {
      type: Object,
      default: {}
    }
  },
  data: function(){
    return {
      ready: false
    };
  },
  created: function() {
    var accountEndpoint = apiEndPoint+"/account";
    var options = {
      url: accountEndpoint,
      method: 'GET'
    };
    this.$http(options).then(function(response){
      if (response.status === 200) {
        if (response.body.authenticated) {
          var user = response.body.user;
          this.$emit("found-user",user);
          if (response.body.folder) {
            this.ready = true;
          } else {
            this.$router.push("/select-folder");
          }
        } else {
          this.$router.push("/");
        }
      }
    });
  }
});

var SelectFolder = Vue.component('selectFolder',{
  template: '#select-folder-template',
  props: {
    user: {
      type: Object,
      default: {}
    }
  },
  data: function(){
    return {
      ready: false,
      folders: [],
    };
  },
  created: function() {
    this.updateFolders();
  },
  methods: {
    updateFolders: function(folder){
      if (folder === undefined) {
        folder = "/";
      }
      var folderEndpoint = apiEndPoint+"/folders/list";
      var options = {
        url: folderEndpoint,
        method: 'GET',
        params: {
          "folder": folder
        }
      };
      this.$http(options).then(function(response){
        if (response.status === 200) {
          if (response.body.folders) {
              this.ready = true;
              this.folders = response.body.folders;
          } else {
            this.$router.push("/");
          }
        }
      });
    }
  }
});

var Login = Vue.component('login',{
  template: '#login-template',
  props: {

  },
  data: function(){
    return {
      ready: false,
      loginUrl: null
    };
  },
  created: function(){
    var loginUrlEndpoint = apiEndPoint+"/login-url";
    var options = {
      url: loginUrlEndpoint,
      method: 'GET'
    };
    this.$http(options).then(function(response){
      if (response.status === 200) {
        if (response.body.authenticated) {
          this.$router.push("/dropbox");
        } else {
          this.ready = true;
          this.loginUrl = response.body.url;
        }
      }
    });
  },
  methods: {

  }
});
console.log(Login);

var Loader = Vue.component('loader',{
  template: '#loader-template',
  props: {

  },
  data: function(){
    return {

    };
  },
  methods: {

  }
});

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
