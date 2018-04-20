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
