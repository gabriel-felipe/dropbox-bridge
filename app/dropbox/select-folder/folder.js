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
