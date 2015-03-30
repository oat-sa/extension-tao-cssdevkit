module.exports = function(grunt) { 

    var sass    = grunt.config('sass') || {};
    var watch   = grunt.config('watch') || {};
    var notify  = grunt.config('notify') || {};
    var root    = grunt.option('root') + '/taoCssDevKit/views/';

    sass.taocssdevkit = { };
    sass.taocssdevkit.files = { };
    sass.taocssdevkit.files[root + 'css/css-sdk.css'] = root + 'scss/css-sdk.scss';

    watch.taocssdevkitsass = {
        files : [root + 'views/scss/**/*.scss'],
        tasks : ['sass:taocssdevkit', 'notify:taocssdevkitsass'],
        options : {
            debounceDelay : 1000
        }
    };

    notify.taocssdevkitsass = {
        options: {
            title: 'Grunt SASS', 
            message: 'SASS files compiled to CSS'
        }
    };

    grunt.config('sass', sass);
    grunt.config('watch', watch);
    grunt.config('notify', notify);

    //register an alias for main build
    grunt.registerTask('taocssdevkitsass', ['sass:taocssdevkit']);
};
