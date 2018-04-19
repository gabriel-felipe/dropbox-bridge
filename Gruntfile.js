module.exports = function(grunt) {

  grunt.initConfig({
    concat: {
      app: {
        src: ['app/*/*.js','app/*/*/*.js','app/app.js'],
        dest: 'dist/app.js',
      },
      html: {
        src: ['app/**/*.html'],
        dest: 'dist/templates.html',
      },
      vendorjs: {
        src: [
          'node_modules/vue/dist/vue.js',
          'node_modules/vue-resource/dist/vue-resource.min.js',
          'node_modules/vue-router/dist/vue-router.min.js'
        ],
        dest: 'dist/vendor.min.js',
      },
      less: {
        src: ['app/app.less', 'app/*/*.less'],
        dest: 'dist/app.less',
      }
    },
    less: {
      app: {
        options: {
          paths: ['app/*'],
          plugins: [
            new (require('less-plugin-autoprefix'))({browsers: ["last 2 versions"]})
          ]
        },
        files: {
          'dist/app.css': 'dist/app.less'
        }
      }
    },
    watch: {
      app: {
        files: ['app/**/*.js'],
        tasks: ['dist'],
        options: {
          spawn: false,
        },
      },
      template: {
        files: ['app/**/*.html'],
        tasks: ['concat:html'],
        options: {
          spawn: false,
        },
      },
      less: {
        files: ['app/**/*.less'],
        tasks: ['concat:less','less:app','cssmin'],
        options: {
          spawn: false,
        },
      },
    },
    cssmin: {
      options: {
        mergeIntoShorthands: false,
        roundingPrecision: -1
      },
      target: {
        files: {
          'dist/app.min.css': ['dist/app.css']
        }
      }
    },
    uglify: {
      app: {
        files: {
          'dist/app.min.js': ['dist/app.js']
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-cssmin');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.registerTask('dist', ['concat:app', 'uglify:app']);
};
