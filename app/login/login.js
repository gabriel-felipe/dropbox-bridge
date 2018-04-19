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
