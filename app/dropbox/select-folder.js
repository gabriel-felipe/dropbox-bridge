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
