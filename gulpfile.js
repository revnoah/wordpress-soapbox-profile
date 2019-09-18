/*
  This is a gulp workflow based around a sass file called app.scss
  It compiles and then watches for changes in themes using the folder
  structure shown below. The source and destination are set to the
  specific theme name.

  To make it work, you make to install:

  $ npm install
  
  To install the gulp requirements individually:

  $ npm install gulp gulp-zip gulp-notify

  To execute, change to project root:

  $ gulp
*/

var gulp = require('gulp');
var zip = require('gulp-zip');
var notify = require('gulp-notify');

var testPath = '../wptest/wp-content/plugins';
var sourcePath = 'source';
var buildPath = 'build';
var fileName = 'soapbox-profile';

gulp.task('zippy', async function() {
  gulp.src(sourcePath + '/**')
    .pipe(zip(fileName + '.zip', {
      createSubFolders: true
    }))
    .pipe(gulp.dest(buildPath))
    .pipe(notify({ title: "Gulp - Zip", message: "WordPress plugin zipped" }));
});

gulp.task('copy', async function () {
  gulp.src(sourcePath + '/**')
    .pipe(gulp.dest(buildPath + '/' + fileName + '/'))
    .pipe(notify({ title: "Gulp - Copy", message: "WordPress plugin copied" }));
  gulp.src('license.txt')
    .pipe(gulp.dest(buildPath + '/' + fileName + '/'))
});

gulp.task('test', async function () {
  gulp.src(sourcePath + '/**')
    .pipe(gulp.dest(testPath + '/' + fileName + '/'));
});

gulp.task('default', gulp.series('copy', 'zippy'));

gulp.task('watch', async function() {
  gulp.watch(sourcePath + '/**/*.php', gulp.series('test'));
});
