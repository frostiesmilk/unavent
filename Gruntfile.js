module.exports = function (grunt) {
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({
        less: {
            dist: {
                options: {
                    compress: true,
                    yuicompress: true,
                    optimization: 2
                },
                files: {
                    "web/css/main.css": [
                        "bower_components/bootstrap/dist/css/bootstrap.css",
                        "src/Wybe/MyBundle/Resources/public/css/main.less"
                    ]
                }
            }
        },
        uglify: {
            options: {
                mangle: false,
                sourceMap: true
            },
            dist: {
                files: {
                    'web/js/main.js': [
                        'src/Namespace/MyBundle/Resources/public/js/main.js',
                    ]
                }
            }
        }
    });

    grunt.registerTask('default', ["less", "uglify"]);
};