const mix = require('laravel-mix');
const lodash = require("lodash");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */


const folder = {
    src: "resources/", // source files
    dist: "public/", // build files
    dist_assets: "public/assets/" //build assets files
};

mix.sass('resources/scss/app.scss', folder.dist_assets + "css").options({processCssUrls: false});
mix.sass('resources/icons/icons.scss', folder.dist_assets + "icons").options({processCssUrls: false});
mix.js('resources/js/app.js', folder.dist_assets + "js/app.js").options({processCssUrls: false});

let folderJs = folder.dist_assets + "js/";
let folderCss = folder.dist_assets + "css/";
let app_pages_assets = {
    js: [
        folder.src + "js/components/custom.js",
        folder.src + "js/components/left-menu.js",
        folder.src + "js/components/perfect-scrollbar.js",
        folder.src + "js/components/pscroll.js",
        folder.src + "js/components/pscroll-1.js",
        folder.src + "js/components/sidebar.js",
        folder.src + "js/components/sidemenu.js",
        folder.src + "js/components/sticky.js",
        folder.src + "js/components/show-password.min.js",
    ]
};


lodash(app_pages_assets).forEach(function (assets, type) {
    for (let i = 0; i < assets.length; ++i) {
        mix.copy(assets[i], folderJs + "components");
    }
});

let cssStyles = {
    css: [
        // folder.src + "scss/styles/colors/color.scss",
        folder.src + "scss/color1.scss",
        // folder.src + "scss/styles/colors/color2.scss",
        // folder.src + "scss/styles/colors/color3.scss",
        // folder.src + "scss/styles/colors/color4.scss",
        // folder.src + "scss/styles/colors/color5.scss",
        // folder.src + "scss/styles/boxed.scss",
        // folder.src + "scss/styles/dark-boxed.scss",
        // folder.src + "scss/styles/dark-style.scss",
        // folder.src + "scss/styles/default.scss",
        // folder.src + "icons/icons.scss",
    ]
};

lodash(cssStyles).forEach(function (assets, type) {
    for (let i = 0; i < assets.length; ++i) {
        mix.sass(assets[i], folder.dist_assets + "css");
    }
});

mix.copyDirectory(folder.src + "plugins", folder.dist + "assets/plugins");

// copy all fonts
mix.copyDirectory(folder.src + "icons", folder.dist_assets + "icons");
mix.copyDirectory(folder.src + "img", folder.dist_assets + "img");
// mix.css(folder.src + "css/switcher.css", folder.dist_assets + "css");
