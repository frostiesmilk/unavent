module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        bowercopy: {
            options: {
                srcPrefix: 'bower_components',
                destPrefix: 'web/assets'
            },
            scripts: {
                files: {
                    'js/jquery.js': 'jquery/dist/jquery.js',
                    'js/bootstrap.js': 'bootstrap/dist/js/bootstrap.js',
                    'fonts': 'bootstrap/dist/fonts/**',
                }
            },
            stylesheets: {
                files: {
                    'css/bootstrap.css': 'bootstrap/dist/css/bootstrap.css',
                    'css/bootstrap.css.map': 'bootstrap/dist/css/bootstrap.css.map',
                    'css/font-awesome.css': 'fontawesome/css/font-awesome.css'
                }
            },            
            fonts: {
                files: {
                    'fonts': 'fontawesome/fonts'
                }
            }
        },
        less: {
            developement: {
                options: {
                    compress: true,
                    yuicompress: true,
                    optimization: 2
                },
                files: {
                    "web/css/main.css": [
                        "src/Wybe/FrontOfficeBundle/Resources/public/less/main.less"
                    ],
                    "web/css/signin.css": [
                        "src/Wybe/FrontOfficeBundle/Resources/public/less/signin.less"
                    ],
                    "web/css/frontoffice-common.css": [
                        "src/Wybe/FrontOfficeBundle/Resources/public/less/frontoffice-common.less"
                    ],
                }
            },
            production: {
                options: {
                    compress: true,
                    yuicompress: true,
                    optimization: 2,
                    cleancss: true
                },
                files: {
                    "web/css/main.css": [
                        "src/Wybe/FrontOfficeBundle/Resources/public/less/main.less"
                    ],
                    "web/css/signin.css": [
                        "src/Wybe/FrontOfficeBundle/Resources/public/less/signin.less"
                    ],
                    "web/css/frontoffice-common.css": [
                        "src/Wybe/FrontOfficeBundle/Resources/public/less/frontoffice-common.less"
                    ],
                }
            }
        },
        watch: {
            styles: {
                files: ['less/**/*.less'], // which files to watch
                tasks: ['less'],
                options: {
                    nospawn: true
                }
            }
        },
        /*uglify: {
            options: {
                mangle: false,
                sourceMap: true
            },
            dist: {
                files: {
                    'web/js/main.js': [
                        'src/Wybe/FrontOfficeBundle/Resources/public/js/main.js'
                    ]
                }
            }
        }*/
        copy:{
            main: {
                files:[
                    // includes files within path and its sub-directories
                    {
                        expand: true, 
                        cwd: 'src/Wybe/FrontOfficeBundle/Resources/public/js/',
                        src: ['**'], 
                        dest: 'web/js/',
                        flatten: true,
                        filter: 'isFile',
                    },                    
                ],                
            },
        }
                
    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-uglify');   

    grunt.registerTask('default', ["less", "uglify", "watch"]);
    grunt.registerTask('bower', ["bowercopy"]);
};