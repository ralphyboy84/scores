// include gulp
var gulp = require('gulp'); 
var jshint = require('gulp-jshint');

// JS hint task
gulp.task('jshint', function() {
  gulp.src('./js/scripts.js')
    .pipe(jshint())
    .pipe(jshint.reporter('default'));
});

var filesToMove = [
        './ajax/**/*.*',
        './classes/**/*.*',
        './css/**/*.*',
        './img/**/*.*',
        './js/**/*.*',
        'index.php',
        'includes.php',
        'calculateScores.php'
    ];

gulp.task('movelive', function(){
  // the base option sets the relative root for the set of files,
  // preserving the folder structure
  gulp.src(filesToMove, { base: './' })
  .pipe(gulp.dest('_build'));
});

gulp.task('default', ['jshint', 'movelive'], function() {

});