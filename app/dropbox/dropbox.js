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
