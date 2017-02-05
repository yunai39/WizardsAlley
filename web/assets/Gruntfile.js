module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        // configuration des tâches grunt
        // Concaténation
        concat: {
            options: {
                separator: ';',
            },
            dist: {
                src: ['js/plugins/*'],
                dest: 'js/app.js'
            }
        },
        sass: {
            dist: {
                options: {
                    style: 'expanded'
                },
                files: {
                    'css/main.css': 'sass/main.sass',
                }
            }
        }

    });

    // Les tâches sont enregistrées ici
    grunt.registerTask('default', ['concat', 'sass']);

};
