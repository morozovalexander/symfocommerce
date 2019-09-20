Symfocommerce
=====

[![Join the chat at https://gitter.im/morozovalexander/symfocommerce](https://badges.gitter.im/morozovalexander/symfocommerce.svg)](https://gitter.im/morozovalexander/symfocommerce?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)
[![License: MIT](https://img.shields.io/badge/License-MIT-blue.svg)](https://opensource.org/licenses/MIT)

Symfony3 Standart Edition based ecommerce project.

Demo version: [http://symfocommerce.webgrowth.xyz](http://symfocommerce.webgrowth.xyz)

- Products with related categories and manufacturers.
- Advanced and convenient admin panel
- Products sorting by name or price
- Disabling products by set zero quantity
- Ability to add products to favourites
- Seo friendly navigation, meta tags and description on each page, routes using slug, 
not id's, slug generator in admin section
- Different product measures, e.g. grammes, pieces, oz., ml.
- Category and manufacturer images
- Several product images, fancybox, image uploading by dropzone in admin
- Interactive javascript cart, online cart edition
- News section, last news displayed on main page
- Static pages and links to static pages in top menu
- Slides on top page, slides management in admin section
- Summernote editor for all descriptions
- Sitemap autogeneration

Print /admin to access admin section.

To start use project run next console commands:

- $ composer update
- $ php bin/console doctrine:database:create
- $ php bin/console doctrine:migrations:migrate

You can load some test data and admin account (admin/admin) if you want

- $ php bin/console doctrine:fixtures:load

Do not forget to set permissions for var/cache/, var/logs/, var/sessions/, web/media/ to run symfony ;)
