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
      ready: false,
      folder: null,
      files: [],
      newFileCallbackUrl: null,
      urlError: "",
      savingUrl: false,
      urlSaved: false
    };
  },
  created: function() {
      this.updateFiles();
  },
  methods: {
    saveUrl: function(){
      if (this.$refs.inputUrl) {
          input = this.$refs.inputUrl;
          this.urlSaved = false;
          if(input.validity.valid){
              this.urlError = "";
              this.savingUrl = true;
              var url = this.newFileCallbackUrl;
              var updateFolderEndpoint = apiEndPoint+"url/new-file-callback";
              var options = {
                url: updateFolderEndpoint,
                method: "POST",
                params: {
                  "callbackUrl": url
                }
              };
              this.$http(options).then(function(response){
                this.savingUrl = false;
                if (response.body.status !== "success") {
                    this.urlError = "Please fill a valid url";
                } else {
                    this.urlSaved = true;
                }
              });
          } else {
              this.urlError = "Please fill a valid url";
          }
      }
    },
    updateFiles: function(){
        var accountEndpoint = apiEndPoint+"/account";
        var options = {
          url: accountEndpoint,
          method: 'GET'
        };
        this.$http(options).then(function(response){
          var _self = this;
          window.setTimeout(function(){
            _self.updateFiles();
          },5000);
          if (response.status === 200) {
            if (response.body.authenticated) {
              var user = response.body.user;
              this.$emit("found-user",user);
              if (response.body.folder) {
                this.ready = true;
                this.folder = response.body.folder;
                this.files = response.body.files;
                if (response.body.newFileCallbackUrl && !this.newFileCallbackUrl) {
                    this.newFileCallbackUrl = response.body.newFileCallbackUrl;
                }
              } else {
                this.$router.push("/select-folder");
              }
            } else {
              this.$router.push("/");
            }
          }
        });
    }
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
      selectedFolder: "/",
      saving: false
    };
  },
  created: function() {
  },
  methods: {
    setSelected: function(path){
      this.selectedFolder = path;
    },
    confirm: function(){
      if (this.selectedFolder) {
        this.saving = true;
        var selectedFolder = this.selectedFolder;
        var updateFolderEndpoint = apiEndPoint+"/folders";
        var options = {
          url: updateFolderEndpoint,
          method: 'POST',
          params: {
            "folder": selectedFolder
          }
        };
        this.$http(options).then(function(response){
          this.foldersLoaded = true;
          this.saving = false;
          if (response.status === 200) {
            if (response.body.status === "success") {
              this.$router.push("/dropbox");
            }
          }
        });
      }
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

var File = Vue.component('file',{
  template: '#file-template',
  props: {
    path: String,
    id: String
  },
  data: function(){
    return {
        progress: 0,
        link: null
    };
  },
  created: function() {
      this.updateProgress();
  },
  computed: {

  },
  methods: {
      updateProgress: function(){
        console.log(this.path);
        var id = this.id;
        var fileEndPoint = apiEndPoint+"/file/status?id="+id;
        var options = {
          url: fileEndPoint,
          method: 'GET'
        };
        this.$http(options).then(function(response){
          if (response.status === 200) {
            if (response.body.status !== "not_found") {
                this.progress = response.body.progress;
                if (this.progress < 100) {
                    var _self = this;
                    window.setTimeout(function(){
                        _self.updateProgress();
                    },300);
                } else {
                    this.link = response.body.link;
                }
            } else {
                var _self = this;
                window.setTimeout(function(){
                  _self.updateProgress();
                },300);
            }
          }
        });
      }
  }
});

var Folder = Vue.component('folder',{
  template: '#folder-template',
  props: {
    path: String,
    selectedFolder: String
  },
  data: function(){
    return {
      showChilds: false,
      folders: [],
      foldersLoaded: false,
    };
  },
  created: function() {
    this.updateFolders();
  },
  computed: {
    selected: function(){
      return this.path == this.selectedFolder;
    }
  },
  methods: {

    open: function(){
      this.showChilds = true;
    },
    close: function(){
      this.showChilds = false;
    },
    toggle: function(){
      if(this.showChilds){
        this.close();
      } else {
        this.open();
      }
    },
    select: function(){
      this.emitSelected(this.path)
    },
    emitSelected: function(path){
      this.$emit("selected",path);
    },
    updateFolders: function(){
      folder = this.path;
      var folderEndpoint = apiEndPoint+"/folders/list";
      var options = {
        url: folderEndpoint,
        method: 'GET',
        params: {
          "folder": folder
        }
      };
      this.$http(options).then(function(response){
        this.foldersLoaded = true;

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
  created: function(){
      var accountEndpoint = apiEndPoint+"/account";
      var options = {
        url: accountEndpoint,
        method: 'GET'
      };
      this.$http(options).then(function(response){
        var _self = this;
        window.setTimeout(function(){
          _self.updateFiles();
        },5000);
        if (response.status === 200) {
          if (response.body.authenticated) {
            var user = response.body.user;
            this.updateUser(user);
          } else {
            this.$router.push("/");
          }
        }
      });
  },
  methods: {
    updateUser: function(user){
      console.log(user);
      console.log("UPDATING USER");
      this.user = user;
    }
  }
}).$mount('#app');
