const gulp = require('gulp');
const gulpCopy = require('gulp-copy');
const del = require('del');
const fs = require('fs');

gulp.task('default', ['copy']);

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
