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
