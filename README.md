# Imaginet's Starter @3.0

### Release Notes  

Enhance theme support and security features

added home template @1.02

Add theme.json configuration for WordPress theme

Refactor functions and improve theme support

Refactor SCSS styles

Restructor theme layout - removed old off-canvas layout

Update mobile menu styles

js updates

Rename enqueue functions and update styles/scripts

Add taxonomies and REST support to news post type

Enhance WooCommerce theme support settings 

Refactor mobile menu toggle and update event handlers

Add mobile-first styles for main menu drawer

Refactor header.php for improved structure and semantics

Refactor footer structure and accessibility links

Remove redundant header content and fix structure

Revise home.php template for better layout 

Update package.json with new dependencies and URLs

Refactor gulpfile.js for improved structure and tasks

## Welcome,

***Using This Template Guarantees You The Latest WordPress, Bootstrap, JQuery Versions***

1. Clone the repository
2. Cd into the cloned folder
3. Install dependencies by typing in the console `npm i`
4. run `npm run gulp init`
5. After the build has finished you shall see a `wordpress` directory.
6. Zip the newly created `wordpress` folder
7. Upload the zip file to the host
10. Install WordPress
11. After installation **connect to the admin and choose Imaginet as your theme**
12. Connect the theme via FTP
13. Using your editors SASS compiler cd into `/wp-content/themes/starter-template/assets/scss`
14. Save your style.css => it should compile all the relevant files


***Adding new pages / templates***

Each page/template should have a wrapping div for consistency, the best example is the homepage which is located
in page-templates
