# pan-be.com — portfolio theme

Custom WordPress theme behind [pan-be.com](https://pan-be.com), my portfolio site.

I built the site as a static HTML + SCSS + JavaScript project first, then turned it into a WordPress theme so its content can be edited from the admin panel without touching code.

## Stack

- **WordPress** theme in PHP, using the classic editor
- **SCSS** split into `global/` (colours, typography, layout), `components/` (header, hero, skills, projects, pricing, contact, …) and `util/` (functions, mixins), compiled to `scss/style.css`
- **Vanilla JavaScript** for the accessible mobile menu (`inert`, `aria-expanded`, focus handling)
- **ACF** (Advanced Custom Fields) for page content: hero, skills, projects, pricing plans, contact links
- **Polylang** for the Polish and English versions, including translation of ACF field labels
- **Contact Form 7** with a custom AJAX submit handler
- A `portfolio-project` custom post type for portfolio entries

## Structure

```
page-panbe-homepage.php   homepage template (hero, skills, projects, contact)
page-pricing.php          pricing page template
modal.php                 modal markup
functions.php             theme setup, assets, Polylang/ACF helpers, CF7 AJAX handler
scss/                     SCSS sources + compiled style.css
js/script.js              mobile navigation
acf-json/                 ACF field group definitions (ACF Local JSON)
```

## Workflow

- **Staging:** a Docker WordPress instance on my home server with the same WordPress and PHP versions as production. This repo is mounted into it as the theme directory.
- **SCSS** is compiled locally and the compiled `scss/style.css` is committed, so deployment doesn't need a build step.
- **Deploy:** the hosting pulls this repository into the theme directory. Content (pages, posts, uploads) lives in the production database and isn't part of this repo. ACF field definitions are versioned in `acf-json/`.

## Credits

Started from the [BlankSlate](https://github.com/bhadaway/blankslate) starter theme by Bryan Hadaway (GPL). Everything visual (layout, styles, templates, scripts) is my own work.

## Licence

GNU General Public License v3 or later, inherited from BlankSlate.
