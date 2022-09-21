const gulp =  require('gulp');
const sass = require('gulp-sass')(require('sass'));
const prefix = require('gulp-autoprefixer');
const minify = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const mode = require('gulp-mode')();
const browserSync = require('browser-sync').create();
const concat = require('gulp-concat');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');


// compilation des styles CSS
function styles(){
    // Emplacement des fichiers .scss
    return gulp.src('./scss/style.scss')
    // Source map
        .pipe(mode.development( sourcemaps.init() ))
    // Passer ces fichiers au compilateur + si erreur affiche les log
        .pipe(sass().on('error', sass.logError))
    // Préfixer automatiquement les propriétés nécessaire
        .pipe(prefix('last 2 versions'))
    // Minifier
        .pipe(minify())
    // Source map
        .pipe(mode.development( sourcemaps.write() ))
    // Emplacement du fichier .css généré
        .pipe(gulp.dest('./css/'))
    // changements de flux pour tous les navigateurs
        .pipe(mode.development( browserSync.stream() ));
}

// compilation des scripts JavaScript
function scriptJs(){
    return gulp.src('./js/*.js')
        .pipe(concat('script.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('./'))
        .pipe(mode.development( browserSync.stream() ));
}

// watch
function watch() {
    browserSync.init({
        server: {
            baseDir: './'
        }
    });
    gulp.watch('./scss/**/*.scss', styles);
    gulp.watch('./*.html').on('change', browserSync.reload);
    gulp.watch('./js/**/*.js', scriptJs);
}

// Compilation pour la prod
function build(){
    // Emplacement des fichiers .scss
    return gulp.src('./scss/style.scss')
    // Passer ces fichiers au compilateur + si erreur affiche les log
        .pipe(sass().on('error', sass.logError))
    // Préfixer automatiquement les propriétés nécessaire
        .pipe(prefix('last 2 versions'))
    // Minifier
        .pipe(minify())
    // Emplacement du fichier .css généré
        .pipe(gulp.dest('./'));
}


exports.watch = watch;
exports.build  = build;