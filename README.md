# ChriCo WP-Fields [![Latest Stable Version](https://poser.pugx.org/chrico/wp-fields/v/stable)](https://packagist.org/packages/chrico/wp-fields) [![Project Status](http://opensource.box.com/badges/active.svg)](http://opensource.box.com/badges) [![Build Status](https://travis-ci.org/Chrico/wp-fields.svg?branch=master)](http://travis-ci.org/chrico/chrico-wp-fields) [![License](https://poser.pugx.org/chrico/wp-fields/license)](https://packagist.org/packages/chrico/wp-fields)

ChriCo WP-Fields is a Composer package (not a plugin) that allows to generate form fields in WordPress.

---

## Minimum requirements and dependencies

ChriCo Fields requires:

* PHP 7+
* WordPress latest - 0.1
* Composer to be installed

When installed for development, via Composer, ChriCo Fields also requires:

* "phpunit/phpunit" (BSD-3-Clause)
* "brain/monkey" (MIT)

---

## Docs

Documentation can be found in [`docs/`](docs/).

---

## Frequently Asked Questions

> Why are you not just using existing packages like the [Symfony Forms](http://symfony.com/doc/current/forms.html) or [Zend Form](https://framework.zend.com/manual/2.4/en/modules/zend.form.intro.html)?

That's a good question! As already mentioned above, WordPress has different pages and scenarios where to use form fields. Both packages are excellent and i worked a lot with them in past. 

*But:* They need a ton of dependencies. 

As example, if you're going to use Symfony Forms, you've to use other Symfony Packages like Event Dispatcher, Intl, Options Resolver, Property Access. And that's not all, if you're serious, you'll probably end up using as well [Symfony Validation](https://symfony.com/doc/current/validation.html), [Symfony CSRF](http://symfony.com/doc/current/form/csrf_protection.html), [Twig](http://twig.sensiolabs.org/). And that's the whole point..when we load half of Symfony and we're not far away from including Doctrine as well...why do we still use WordPress?

> What about WordPress Field-Plugins like [Advanced Custom Fields](https://www.advancedcustomfields.com/)?

I guess you're wrong here. ;-)

> What about the upcoming [WordPress Fields API](https://github.com/sc0ttkclark/wordpress-fields-api)?

Nothing. There's a lot of weird and quirky code in it. No interfaces, no real abstraction, no strict return types, PHP 5.2, missing Unit Tests, ... _the list is endless, so i'll stop here._ 

In fact, the API will eventually appear in WordPress in near - or far - future. But until then, everything is fine. If the API is really released, i'll adapt this code to fit on top of the Fields API.

---

## License
Copyright (c) 2017 ChriCo.

ChriCo Fields code is licensed under [GNU General Public License v3.0](/LICENSE).

```
   _____ _          _  _____      
  / ____| |        (_)/ ____|     
 | |    | |__  _ __ _| |     ___  
 | |    | '_ \| '__| | |    / _ \ 
 | |____| | | | |  | | |___| (_) |
  \_____|_| |_|_|  |_|\_____\___/ 
                                  
```
