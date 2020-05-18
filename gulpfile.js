// Initialize modules
// Importing specific gulp API functions lets us write them below as series() instead of gulp.series()
const { src, dest, watch, series, parallel } = require('gulp');
// Importing all the Gulp-related packages we want to use
const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const concat = require('gulp-concat');
const uglify = require('gulp-uglify');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const cssnano = require('cssnano');
const rename = require('gulp-rename');
var replace = require('gulp-replace');


// File paths
const paths = {
    'dev': {
        'scss': './resources/sass/**/*.scss',
        'js': './resources/js/**/*.js'        
    },
    'production': {
        'css': './public/css/',
        'js': './public/js/'         
    }
}


// Sass task: compiles the style.scss file into style.css
function scssTask(){    
    return src(paths.dev.scss)
        .pipe(sourcemaps.init()) // initialize sourcemaps first
        .pipe(sass({
			errorLogToConsole:true				
		   })) 
		   .on('error',console.error.bind(console))// compile SCSS to CSS
		   .pipe(dest(paths.production.css))

           .pipe(sass({
			errorLogToConsole:true				
		   })) 
		   .on('error',console.error.bind(console))// compile SCSS to CSS		    
           .pipe(rename({suffix: '.min'}))
        .pipe(postcss([ autoprefixer(), cssnano() ])) // PostCSS plugins
        .pipe(sourcemaps.write('.')) // write sourcemaps file in current directory
        .pipe(dest(paths.production.css)
    ); // put final CSS in dist folder
}

// JS task: concatenates and uglifies JS files to script.js
function jsTask(){
    return src([
		paths.dev.js,
        paths.dev.js + 'bootstrap.js',
        './node_modules/jquery/dist/jquery.slim.js',
        './node_modules/popper.js/dist/umd/popper.js',
        './node_modules/bootstrap/dist/js/bootstrap.js',
        ])
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(dest(paths.production.js)
    );
}

// Cachebust
function cacheBustTask(){
    var cbString = new Date().getTime();
    return src(['index.html'])
        .pipe(replace(/cb=\d+/g, 'cb=' + cbString))
        .pipe(dest('.'));
}

// Watch task: watch SCSS and JS files for changes
// If any change, run scss and js tasks simultaneously
function watchTask(){
    watch([paths.dev.scss, paths.dev.js],
        {interval: 1000, usePolling: true}, //Makes docker work
        series(
            parallel(scssTask, jsTask),
            cacheBustTask
        )
    );    
}

// Export the default Gulp task so it can be run
// Runs the scss and js tasks simultaneously
// then runs cacheBust, then watch task
exports.default = series(
    parallel(scssTask, jsTask), 
    cacheBustTask,
    watchTask
);