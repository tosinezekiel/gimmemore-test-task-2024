// webpack.config.js

const Encore = require('@symfony/webpack-encore');
const path = require('path');

Encore
  // directory where compiled assets will be stored
  .setOutputPath('public/build/')
  // public path used by the web server to access the output path
  .setPublicPath('/build')
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')

  .addAliases({
    '@': path.resolve(__dirname, './assets'), // Adjust this path to where your Vue files are located
  })
  
  /*
   * ENTRY CONFIG
   *
   * Add 1 entry for each "page" of your app
   * (including one that's included on every page - e.g. "app")
   */
  .addEntry('app', './assets/ts/main.ts')

  // enables the Symfony UX Stimulus bridge (used in assets/controllers.json)
  .enableStimulusBridge('./assets/controllers.json')
  
  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js but, you probably want this, unless you're building a single-page app
  .enableSingleRuntimeChunk()

  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction())

  // enables @babel/preset-env polyfills
  .configureBabel(() => {}, {
    useBuiltIns: 'usage',
    corejs: 3
  })

  // enables Vue loader
  .enableVueLoader(() => {}, { runtimeCompilerBuild: false })

  // enables TypeScript
  .enableTypeScriptLoader()

  // enables Tailwind CSS
  .enablePostCssLoader()

  // uncomment if you use Sass/SCSS files
  //.enableSassLoader()

  // uncomment if you're having problems with a jQuery plugin
  //.autoProvidejQuery()

  // configure the script or link tags added to your HTML
//   .configureHtmlPlugin(options => {
//     // customize the options here
//   })

  // copy files to your public directory
  .copyFiles({
    from: './assets/images',

    // optional target path, relative to the output dir
    // to: 'images/[path][name].[ext]',

    // if versioning is enabled, add the file hash too
    // to: 'images/[path][name].[hash:8].[ext]',

    // only copy files matching this pattern
    // pattern: /\.(png|jpg|jpeg)$/
  })
;

// export the final configuration
module.exports = Encore.getWebpackConfig();
