module.exports = function(grunt) { 

    var requirejs   = grunt.config('requirejs') || {};
    var clean       = grunt.config('clean') || {};
    var copy        = grunt.config('copy') || {};

    var root        = grunt.option('root');
    var libs        = grunt.option('mainlibs');
    var ext         = require(root + '/tao/views/build/tasks/helpers/extensions')(grunt, root);
    var out         = 'output';


    /**
     * Remove bundled and bundling files
     */
    clean.taocssdevkitbundle = [out];
    
    /**
     * Compile tao files into a bundle 
     */
    requirejs.taocssdevkitbundle = {
        options: {
            baseUrl : '../js',
            dir : out,
            mainConfigFile : './config/requirejs.build.js',
            paths : { 'taoCssDevKit' : root + '/taoCssDevKit/views/js' },
            modules : [{
                name: 'taoCssDevKit/controller/routes',
                include : ext.getExtensionsControllers(['taoCssDevKit']),
                exclude : ['mathJax', 'mediaElement'].concat(libs)
            }]
        }
    };

    /**
     * copy the bundles to the right place
     */
    copy.taocssdevkitbundle = {
        files: [
            { src: [out + '/taoCssDevKit/controller/routes.js'],  dest: root + '/taoCssDevKit/views/js/controllers.min.js' },
            { src: [out + '/taoCssDevKit/controller/routes.js.map'],  dest: root + '/taoCssDevKit/views/js/controllers.min.js.map' }
        ]
    };

    grunt.config('clean', clean);
    grunt.config('requirejs', requirejs);
    grunt.config('copy', copy);

    // bundle task
    grunt.registerTask('taocssdevkitbundle', ['clean:taocssdevkitbundle', 'requirejs:taocssdevkitbundle', 'copy:taocssdevkitbundle']);
};
