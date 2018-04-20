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
