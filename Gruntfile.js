module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		
		
		// PROJECT
		
		project: {
			name: 'rdr2',
			plugin: '<%= project.name %>-plugin',
			theme: '<%= project.name %>-theme',
			cms: 'wordpress'
		},
    	
    	
    	// SOURCE
    	
		src: {
			dir: 'src',
			js: '<%= src.dir %>/js',
			lib: '<%= src.dir %>/lib',
			sass: '<%= src.dir %>/sass',
			css: '<%= src.dir %>/css',
			themes: '<%= src.dir %>/themes',
			themepatches: '<%= src.dir %>/theme-patches',
			plugins: '<%= src.dir %>/plugins',
			pluginpatches: '<%= src.dir %>/plugin-patches',
			muplugins: '<%= src.dir %>/mu-plugins',
			uploads: '<%= src.dir %>/uploads',
			cms: '<%= src.dir %>/cms',
			projectcms: '<%= src.cms %>/<%= project.cms %>',
			extra: '<%= src.dir %>/extra',
		},
		
		
		
		// THEME
	
		theme: {
			dir: '<%= src.themes %>/<%= project.theme %>',
			assets: '<%= theme.dir %>/assets',
			css: '<%= theme.assets %>/css',
			sass: '<%= theme.assets %>/sass',
			js: '<%= theme.assets %>/js',
			images: '<%= theme.assets %>/images',
		},
		
		
		// PLUGIN
	
		plugin: {
			dir: '<%= src.plugins %>/<%= project.plugin %>',
			assets: '<%= plugin.dir %>/assets',
			css: '<%= plugin.assets %>/css',
			sass: '<%= plugin.assets %>/sass',
			js: '<%= plugin.assets %>/js',
			images: '<%= plugin.assets %>/images',
		},
		
		
		// HTML

		html: {
			dir: 'html',
			images: '<%= html.dir %>/images',
			js: '<%= html.dir %>/js',
			css: '<%= html.dir %>/css',
		},
		
		
		
		// WWW

		www: {
			dir: 'www',
			lib: '<%= www.dir %>/lib',
			wpcontent: '<%= www.dir %>/wp-content/',
			themes: '<%= www.wpcontent %>/themes',
			plugins: '<%= www.wpcontent %>/plugins',
			muplugins: '<%= www.wpcontent %>/mu-plugins',
			uploads: '<%= www.wpcontent %>/uploads',
		},
		
		
		
		// COPY	

		copy: {
			cms: {
				files: [{ cwd: '<%= src.projectcms %>', src: ['**'], dest: '<%= www.dir %>', expand: true }]
			},
		
			extra: {
				files: [{ cwd: '<%= src.extra %>', src: ['**'], dest: '<%= www.dir %>', dot: true, expand: true }]
			},
		
			lib: {
				files: [{ cwd: '<%= src.lib %>', src: ['**'], dest: '<%= www.lib %>', expand: true }],
			},
		
			plugins: {
				files: [{ cwd: '<%= src.plugins %>', src: ['**'], dest: '<%= www.plugins %>', expand: true }]
			},
		
			pluginpatches: {
				files: [{ cwd: '<%= src.pluginpatches %>', src: ['**'], dest: '<%= www.plugins %>', verbose: true, expand: true }]
			},
						
			themes: {
				files: [{ cwd: '<%= src.themes %>', src: ['**'], dest: '<%= www.themes %>', expand: true }]
			},
			
			themepatches: {
				files: [{ cwd: '<%= src.themepatches %>', src: ['**'], dest: '<%= www.themes %>', expand: true }]
			},
			
			theme: {
				files: [{ cwd: '<%= theme.dir %>', src: ['**'], dest: '<%= www.themes %>/<%= project.theme %>', expand: true }]
			},
			
			uploads: {
				files: [{ cwd: '<%= src.uploads %>', src: ['**'], dest: '<%= www.uploads %>', expand: true }]
			},
		
			images: {
				files: [{ cwd: '<%= src.js %>/jquery-ui-1.12.1.custom/images', src: ['**'], dest: '<%= theme.images %>', expand: true }]
			},
		},
	
	
		// SYNC
	
		sync: {
			plugin: {
				files: [{ cwd: '<%= src.plugins %>/<%= project.plugin %>', src: ['**'], dest: '<%= www.plugins %>/<%= project.plugin %>' }],
				verbose: true, 
				pretend: false, 
				failOnError: false,
				//ignoreInDest: "mwf-plugin/**", 
				updateAndDelete: true, 
				compareUsing: "md5" 
			},
			
			plugins: {
				files: [{ cwd: '<%= src.plugins %>', src: ['**'], dest: '<%= www.plugins %>' }],
				verbose: true, 
				pretend: false, 
				failOnError: false, 
				//ignoreInDest: "mwf-plugin/**", 
				updateAndDelete: true, 
				compareUsing: "md5" 
			},
		
			pluginupdates: {
				files: [{ cwd: '<%= www.plugins %>', src: ['**'], dest: '<%= src.plugins %>' }],
				verbose: true, 
				pretend: false, 
				failOnError: false, 
	    		//ignoreInDest: "mwf-plugin/**", 
				updateAndDelete: true, 
				compareUsing: "md5" 
			},
			
			themes: {
				files: [{ cwd: '<%= src.themes %>', src: ['**'], dest: '<%= www.themes %>' }],
				verbose: true, 
				pretend: false, 
				failOnError: false, 
	    		//ignoreInDest: "mwf-plugin/**", 
				updateAndDelete: true, 
				compareUsing: "md5" 
			},
			
			theme: {
				files: [{ cwd: '<%= theme.dir %>', src: ['**'], dest: '<%= www.themes %>/<%= project.theme %>' }],
				verbose: true, 
				pretend: false, 
				failOnError: false, 
	    		//ignoreInDest: "mwf-plugin/**", 
				updateAndDelete: true, 
				compareUsing: "md5" 
			},
			
			themeupdates: {
				files: [{ cwd: '<%= www.themes %>', src: ['**'], dest: '<%= src.themes %>' }],
				verbose: true, 
				pretend: false, 
				failOnError: false, 
	    		//ignoreInDest: "mwf-plugin/**", 
				updateAndDelete: true, 
				compareUsing: "md5" 
			},
			
			extra: {
				files: [{ cwd: '<%= theme.dir %>', src: ['**'], dest: '<%= www.themes %>/<%= project.theme %>' }],
				verbose: true, 
				pretend: false, 
				failOnError: false, 
	    		//ignoreInDest: "mwf-plugin/**", 
				updateAndDelete: true, 
				compareUsing: "md5" 
			},
		},
		
		
		
		// COMPASS
		
		compass: {
			theme: {
				options: {
					cssDir: '<%= theme.css %>', 
					noLineComments: true, 
					outputStyle: 'compressed', 
					sassDir: '<%= src.sass %>', 
					specify: '<%= src.sass %>/theme.scss',
				}
			},
      
			plugin: {
				options: {
					cssDir: '<%= plugin.css %>',
					noLineComments: true,
					outputStyle: 'compressed',
					sassDir: '<%= src.sass %>',
					specify: '<%= src.sass %>/admin.scss',
				}
			}
      
		},
    	
    	
    	
    	// CSSMIN
    
		cssmin: {
			options: {
				shorthandCompacting: false,
				roundingPrecision: -1
			},
			target: {
				files: {
	    	
					'<%= theme.css %>/samujana-theme.css' : [
						'<%= src.js %>/swiper/dist/css/swiper.css',
						'<%= src.js %>/featherlight/src/featherlight.css',
						'<%= src.css %>/styles.css', 
	    			],

					'<%= theme.css %>/samujana-theme-ie.css' : [
						'<%= src.css %>/ie.css', 
					],
				}
			}
		},
    	
    	
    	
    	// UGLIFY
    	
		uglify: {
			options: {
				compress: false,
				mangle: false,
				beautify: true,
			},
			main: { 
				files: {
					'<%= plugin.js %>/main.min.js': [
						'<%= src.js %>/jquery-details/jquery.details.js',
						'<%= src.js %>/main.js',
					],

					'<%= plugin.js %>/admin-top.min.js': [
						//'<%= src.js %>/canvasjs/canvasjs.min.js',
						'<%= src.js %>/chart/Chart.bundle.js',
					],
        
					'<%= plugin.js %>/admin.min.js': [
						'<%= src.js %>/jquery-details/jquery.details.js',
						//'<%= src.js %>/canvasjs/canvasjs.min.js',
						'<%= src.js %>/reports.js',
						'<%= src.js %>/plugin-admin.js',
					],

				}
			}
		},
    
    
		// WATCH

		watch: {
			themes: {
				files: ['<%= src.themes %>/*.php'],
				tasks: ['themes'],
				options: { livereload: false, spawn: false }
			},
			plugins: {
				files: ['<%= src.plugins %>/*.php'],
				tasks: ['plugins'],
				options: { livereload: false, spawn: false }
			},
			extra: {
				files: ['<%= src.extra %>/**/**'],
				tasks: ['extra'],
				options: { livereload: false, spawn: false, dot: true }
			},
			scripts: {
				files: ['<%= src.js %>/*.js'],
				tasks: ['uglify'],
				options: { livereload: false, spawn: false }
			},
			styles: {
				files: ['<%= src.sass %>/**/*.scss'],
				tasks: ['css'],
				options: { livereload: false, spawn: false }
			},
			images: {
				files: ['<%= src.images %>/**/**'],
				tasks: ['images'],
				options: { livereload: false, spawn: false }
			},
		}
	});
  
  
  
	// NPM Tasks
  
	grunt.loadNpmTasks('grunt-sync');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-watch');
  
  
	// Register Tasks

	grunt.registerTask('lib', ['copy:lib']);
	grunt.registerTask('extra', ['copy:extra']);
	grunt.registerTask('uploads', ['copy:uploads']);
	grunt.registerTask('plugin', ['sync:plugin']);
	grunt.registerTask('pluginpatches', ['copy:pluginpatches']);
	grunt.registerTask('plugins', ['sync:plugins', 'pluginpatches']);
	grunt.registerTask('pluginupdates', ['sync:pluginupdates', 'pluginpatches']);
	grunt.registerTask('themepatches', ['copy:themepatches']);
	grunt.registerTask('themes', ['sync:themes', 'themepatches']);
	grunt.registerTask('themeupdates', ['sync:themeupdates', 'themepatches']);
	grunt.registerTask('theme', ['sync:theme']);
	grunt.registerTask('cms', ['copy:cms']);
  
	grunt.registerTask('css', ['compass', 'theme', 'plugin']);
	grunt.registerTask('plugincss', ['compass:plugin', 'plugin']);
	grunt.registerTask('themecss', ['compass:theme', 'theme']);
  
	grunt.registerTask('js', ['uglify', 'theme', 'plugin']);
  
	grunt.registerTask('site', ['themes', 'plugins']);
	grunt.registerTask('build', ['cms', 'extra', 'site', 'uploads']);
	grunt.registerTask('full', ['compass', 'uglify', 'build']);
	grunt.registerTask('default', ['build']);
  
};

