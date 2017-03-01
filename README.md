# ChriCo Fields
ChriCo Fields is a Composer package (not a plugin) that allows to generate form fields in WordPress.

---

## Minimum requirements and dependencies

ChriCo Fields requires:

* PHP 5.6+
* WordPress latest - 0.1
* Composer to be installed

When installed for development, via Composer, ChriCo Fields also requires:

* "phpunit/phpunit" (BSD-3-Clause)
* "brain/monkey" (MIT)

---

## Motivation
I'm really tired of writing fields by hand again and again and again. Since WordPress does not provide any kind of "help" here, you've to write everything from scratch for each Plugin over and over again.

There are several different locations in WordPress, where you can use form fields:

- Post edit screen in MetaBoxes
- Settings pages
- User edit screen
- Term edit screen
- Comment edit screen
- Customizer

Each location has different possibilities to add custom fields. For example in "Post edit"-screen you're within a form within a `<form>`, on a "Settings page" you're forced to write everything from scratch.

Here's a short example of writing a so called "Metabox" to the "Post edit"-screen:

```php
<?php
add_action( 
	'plugins_loaded', 
	function() {
	
		add_action( 
			'add_meta_boxes', 
			/**
			 * @param string $post_type
			 *
			 * @return bool
			*/ 
			function( string $post_type ) : bool {
			
				// some checks if we're allowed to print our fields.
				if ( $post_type !== 'post' ) {
					return FALSE;
				}

				add_meta_box(
					'maybe-unique-id',
					'Here\'s a title for the MetaBox',
					function ( \WP_Post $post ) {
		
						// The MetaBox-content with your fields are going here...
						// so in theory something like: <input type="text" /> and so on
					},
					$post_type
		        );
				
				return TRUE;				
			}
		);

	} 
);
```

That's a lot of code for just creating 1 single input field. So, this package will provide an elegant way to create form fields via configuration or in an OOP-way. This can save a lot of time when and solves the most common problems in WordPress.

---

## What this package not does
This package will only provide form fields *for WordPress*. Nothing more and nothing less. Everything related to:
 
- Validation of data - use [Inpsyde-Validator](https://github.com/inpsyde/inpsyde-validator)
- Filtering data - use [Inpsyde-Filter](https://github.com/inpsyde/inpsyde-filter)
- Creating MetaBoxes - coming soon
- creating Settings pages - coming soon
- Saving fields to database(s) - coming soon

will be done in separate packages.

---

## Creating elements
To save writing and creating objects by hand, you can use the `ElementFactory` to create elements via specification.

There are by default 3 different types of Elements:

- `Element` - the default implementation with attributes, labels and errors.
- `ChoiceElement` - extends the `Element` and allows us to set and get choices.
- `CollectionElement` - extends the `Element` and allows us to group multiple elements together.

Here's a short example of creating...

- 1 text element
- 1 select element
- 1 collection of both

```php
<?php
use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\ChoiceList\ArrayChoiceList;

$text = new Element( 'my-text' );
$text->set_attributes( [ 'type' => 'text', 'id' => 'my-id' ] );

$select = new ChoiceElement( 'my-select' );
$select->set_attributes( [ 'type' => 'select', 'id' => 'my-select' ] );
$select->set_choices( new ArrayChoiceList( [ 'value1' => 'label 1', 'value 2' => 'label 2'] ) );

$collection = new CollectionElement( 'my-collection' );
$collection->add_elements( [ $text, $select ] );
```

### Adding a label
All elements are implementing the `LabelAwareInterface` which allows us to add a `<label>` and label attributes:

```php
<?php
use ChriCo\Fields\Element\Element;

$text = new Element( 'my-text' );
$text->set_label( 'My label' );
$text->set_label_attributes( [ 'for' => 'my-id' ] );

echo $text->get_label(); // 'My label'
print_r( $text->get_label_attributes() ); // [ 'for' => 'my-id' ] 
```

### Adding errors
Elements are implementing by default the `ErrorAwareInterface` which allows us to assign errors to the element which can be later re-used in view:

```php
<?php
use ChriCo\Fields\Element\Element;

$text = new Element( 'my-text' );
$text->set_errors( [ 'error-id' => 'Error message' ] );

print_r( $text->get_errors() ); // [ 'error-id' => 'Error message' ]
```

---

## Creating elements - advanced
To save some writing and creating objects by hand, you can use the `ChriCo\Fields\ElementFactory` to generate elements.

Here's the same example as above for our 3 elements:

```php
<?php
use ChriCo\Fields\ElementFactory;

$factory = new ElementFactory(); 

// The text element
$text_spec = [
	'attributes' => [
		'name' => 'my-text',
		'type' => 'text'
	],
	'label'             => 'My label',
	'label_attributes'  => [ 'for' => 'my-id' ],
	'errors'            => [ 'error-id' => 'Error message' ]
];
$text = $factory->create( $text_spec );

// the select element
$select_spec = [
	'attributes' => [
		'name' => 'my-select',
		'type' => 'select' 
	],
	'label'     => 'My Label',
	'choices'   => [ 'for' => 'my-id' ]
];
$select = $factory->create( $select_spec );

// the collection element
$collection_spec = [
	'attributes' => [
		'name' => 'my-collection',
		'type' => 'colection'
	],
	'elements' => [ $text_spec, $select_spec ]
];
$collection = $factory->create( $collection_spec );
```

--- 

## Creating elements - like a pro
And now the complete example with 1 specification which could be placed into a own config file:

```php
<?php
use ChriCo\Fields\ElementFactory;

$specs      = [ $input_spec, $select_spec, $collection_spec ];
$elements   = ( new ElementFactory() )->create_multiple( $specs );
```

---

## Render elements
Creating elements is only 1 part of this package. The most important one is to render elements into HTML.

To render our `$elements` from above, we just simple use the `ViewFactory` to create a `FormRow`. 

```php
<?php
use ChriCo\Fields\ViewFactory;
use ChriCo\Fields\View;

$form_row = ( new ViewFactory() )->create( View\FormRow::class );

foreach ( $elements as $element ) :
	echo $form_row->render( $element );
endforeach;
```

To render specific elements, there are a lot of classes available. Just have a look at `ChriCo\Fields\AbstractFactory::$type_to_view`.

---

## Frequently Asked Questions

> Why are you not just using existing Packages like the [Symfony Forms](http://symfony.com/doc/current/forms.html) or [Zend Form](https://framework.zend.com/manual/2.4/en/modules/zend.form.intro.html)?

That's a good question! As already mentioned above, WordPress has different pages and scenarios where to use form fields. Both packages are excellent and i worked a lot with them in past. 

*But:* They need a ton of dependencies. 

As example, if you're going to use Symfony Forms, you've to use other Symfony Packages like Event Dispatcher, Intl, Options Resolver, Property Access. And thats not all, if you're serious, you'll probably end up using as well [Symfony Validation](https://symfony.com/doc/current/validation.html), [Symfony CSRF](http://symfony.com/doc/current/form/csrf_protection.html), [Twig](http://twig.sensiolabs.org/). And that's the whole point..when we load half of Symfony and we're not far away from including Doctrine as well...why do we still use WordPress?

> What about WordPress Field-Plugins like [Advanced Custom Fields](https://www.advancedcustomfields.com/)?

Really? I guess you're wrong here.

> What about the upcoming [WordPress Fields API](https://github.com/sc0ttkclark/wordpress-fields-api)?

Nothing. There's a lot of weird and quirky code in it. No interfaces, no real abstraction, no strict return types, PHP 5.2, missing Unit Tests, ... _the list is endless, so i'll stop here._ 

In fact, the API will eventually appear in WordPress in near - or far - future. But until then, everything is fine. If the API is really released, i'll adapt this code to fit on top of the Fields API.

---

## License
Copyright (c) 2017 ChriCo.

ChriCo Fields code is licensed under [MIT license](https://opensource.org/licenses/MIT).

```
   _____ _          _  _____      
  / ____| |        (_)/ ____|     
 | |    | |__  _ __ _| |     ___  
 | |    | '_ \| '__| | |    / _ \ 
 | |____| | | | |  | | |___| (_) |
  \_____|_| |_|_|  |_|\_____\___/ 
                                  
```