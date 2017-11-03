module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    cssmin: {
      options: {
        banner:
          "/*\n"+
           " * ShiftNav \n" +
           " * http://shiftnav.io \n" +
           " * Copyright 2013-2015 Chris Mavricos, SevenSpark \n" +
           " */"
      },
      minify: {
        files: {
          'assets/css/shiftnav.min.css' : ['assets/css/shiftnav.css'],
          'pro/assets/css/shiftnav.min.css' : ['pro/assets/css/shiftnav.css']
        }
      }
      /*
      minify: {
          expand: true,
          cwd: 'assets/css/',
          src: ['ubermenu.css'],
          dest: 'assets/css/',
          ext: '.min.css'
        }
      */
    },

    'closure-compiler': {
      frontend: {
        closurePath: '/usr/local/lib/closure-compiler',
        js: 'assets/js/*.js',
        jsOutputFile: 'assets/js/shiftnav.min.js',
        maxBuffer: 500,
        options: {
          compilation_level: 'SIMPLE_OPTIMIZATIONS',
          language_in: 'ECMASCRIPT5_STRICT'
        }
      }
    },

    less: {
      development: {
        options: {
          compress: false,
        },
        files: [
          {
            "assets/css/shiftnav.css": "assets/css/shiftnav.less"
          },
          {
            "pro/assets/css/shiftnav.css": "pro/assets/css/shiftnav.less"
          },
        ]
      }
    },

    makepot: {
      target: {
        options: {
          mainFile: 'shiftnav.php',
          domainPath: '/languages',
          // include: [
          //   'path/to/some/file.php'
          // ],
          type: 'wp-plugin', // or `wp-theme`
          potHeaders: {
            poedit: true
          }
        }
      }
    }
  });



  // Load the plugin that provides the "uglify" task.
  //grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks( 'grunt-contrib-less' );
  grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
  grunt.loadNpmTasks( 'grunt-closure-compiler' );
  grunt.loadNpmTasks( 'grunt-wp-i18n' );

  grunt.registerTask('css', ['less','cssmin']);
  //grunt.registerTask('css', ['less']);

  grunt.registerTask('compile', ['closure-compiler']);

  // Default task(s).
  grunt.registerTask('default', ['less','closure-compiler','makepot']);

};