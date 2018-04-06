const $ = require('gulp-load-plugins')();
const gulp = require('gulp');
const gulpCopy = require('gulp-copy');
const del = require('del');
const fs = require('fs');
const importOnce = require('node-sass-import-once');
const extend = require('extend');

var options = {};

options.gulpWatchOptions = {};

options.rootPath = {
    project     : __dirname + '/',
    theme       : __dirname + '/'
};

options.theme = {
    root       : options.rootPath.theme,
    scss       : options.rootPath.theme + 'scss/',
    css        : options.rootPath.theme + 'css/'
};

// Define the node-scss configuration.
options.scss = {
    importer: importOnce,
    outputStyle: 'expanded',
    includePaths: [],
};

var scssFiles = [
    options.theme.scss + '**/*.scss',
    // Do not open scss partials as they will be included as needed.
    '!' + options.theme.scss + '**/_*.scss',
];

// The default task.
gulp.task('default', ['build']);

// Build everything.
gulp.task('build', ['sass']);

// Default watch task.
gulp.task('watch', ['watch:css']);

// Watch for changes for scss files and rebuild.
gulp.task('watch:css', ['sass'], function () {
    return gulp.watch(options.theme.scss + '**/*.scss', options.gulpWatchOptions, ['sass']);
});

// Build CSS for development environment.
gulp.task('sass', ['clean:css'], function () {
    return gulp.src(scssFiles)
        .pipe($.sass(extend(true, {
            noCache: true,
            outputStyle: options.scss.outputStyle,
            sourceMap: true
        }, options.scss)).on('error', $.sass.logError))
        .pipe(gulp.dest(options.theme.css));
});

// Clean CSS files.
gulp.task('clean:css', function () {
    return del([
        options.theme.css + '**/*.css',
        options.theme.css + '**/*.map'
    ], {force: true});
});

/**
 * Task used to update build folder with the corresponding one present in Github.
 *
 * For the specific version we are using see bower.json.
 */
gulp.task('copy', function () {
  var build_path = 'sources/ita-web-toolkit/build'
  if (fs.existsSync(build_path)) {
    console.log('Build subfolder exists. Start files copy...')
    gulp.src(build_path + '/**')
      .pipe(
        gulpCopy('build', { prefix: 3 })
      );
  } else {
    console.log('\n##############################################')
    console.log('# It seems the build folder does not exists. #')
    console.log('# Please run "npm run build" in the          #')
    console.log('# sources/ita-web-toolkit folder and than    #')
    console.log('# come back ad run again the copy command.   #')
    console.log('##############################################\n')
  }


});

/**
 * Task used to delete the IWT directory inside sources folder.
 */
gulp.task('delete', function () {
  del('sources');
});
