# ChriCo WP-Fields 

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

## Motivation
I'm really tired of writing fields by hand again and again and again. Since WordPress does not provide any kind of "help" here, you've to write everything from scratch for each Plugin over and over again.

There are several different locations in WordPress, where you can use form fields:

- Post edit screen in MetaBoxes
- Settings pages
- User edit screen
- Term edit screen
- Comment edit screen
- Customizer

Each location has different possibilities to add custom fields. For example in "Post edit"-screen you're already within a `<form>`, on a "Settings page" you're forced to write everything from scratch.

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

That's a lot of code for just creating 1 single input field. And the field has also to be created completely by hand. No help, no API anywhere. So, this package will provide an elegant way to create form fields via easy configuration to save some time.

---

## What this package not does
This package will only provide form fields *for WordPress* and the way to bind everything (data, validation, filtering) to that element. 

Nothing more and nothing less. Everything related to:
 
- Validation of data - see [Inpsyde-Validator](https://github.com/inpsyde/inpsyde-validator)
- Filtering data - see [Inpsyde-Filter](https://github.com/inpsyde/inpsyde-filter)

will be done in separate packages and just used in this one.

---

## Creating elements
To work later in view with Elements, you have first to create the Element itself. There are following different types of Elements available:


| name | extends | implements | description |
| ------------- | ------------- | ------------- | ------------- |
| `Element` | `BaseElement` | `LabelAwareInterface, ErrorAwareInterface, DescriptionAwareInterface` | default element for inputs, textareas, buttons |
| `ChoiceElement` | `BaseElement` | `LabelAwareInterface, ChoiceElementInterface, ErrorAwareInterface, DescriptionAwareInterface` | allows us to set and get choices for checkboxes or radios |
| `CollectionElement` | `BaseElement` | `LabelAwareInterface, CollectionElementInterface, DescriptionAwareInterface, ErrorAwareInterface` | allows us to group multiple elements into one namespace together |
| `Form` | `CollectionElement` | `FormInterface` | allows us to work with data and delegate them to containing elements |

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

### Adding choices
The `ChoiceElement` allows us to add different choices. This package ships 2 implementations of choices:

- `ArrayChoiceList` - gets choices assigned via constructor.
- `CallbackChoiceList` - loads the choices based on a given `callable` first time it is accessed in view.

To show the differences for both, here's a short example:

```php
<?php
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\ChoiceList\ArrayChoiceList;
use ChriCo\Fields\ChoiceList\CallbackChoiceList;

// normal ArrayChoiceList
$feature_select = new ChoiceElement( 'active-feature' );
$feature_select->set_attributes( [ 'type' => 'select' ] );
$feature_select->set_choices( new ArrayChoiceList( [ '0' => 'Enable the feature X', '1' => 'Disable the feature' ] ) );

/**
 * A CallbackChoiceList which loads posts.
 * @return array( int => string )
 */
$post_choices = function() {

	return array_reduce( 
		get_posts(), 
		function( $data, \WP_Post $post ) {
			$data[ $post->ID ] = "#{$post->ID} {$post->post_title}";

			return $data;
		}, 
		[]
	);
};

$post_select = new ChoiceElement( 'my-post-select' );
$post_select->set_attributes( [ 'type' => 'select' ] );
$post_select->set_choices( new CallbackChoiceList( $post_choices ) );
```

The main difference here is: The `CallbackChoichList` only loads the choices, when they are first accessed in view. The `ArrayChoiceList` already has assigned the complete choices in it's constructor.

### Adding a description
 All elements are implementing the `DescriptionAwareInterface` which allows us to add a description to each field:
 
```php
<?php
use ChriCo\Fields\Element\Element;
 
$text = new Element( 'my-text' );
$text->set_description( 'Some additional description for our form field.' );
  
echo $text->get_description(); 
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
		'type' => 'collection'
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

To render our `$elements` from above into a complete form, we just assign them to a `Element\Form` and use the `ViewFactory` to create a `View\Form`. 

```php
<?php
use ChriCo\Fields\Element\Form;
use ChriCo\Fields\ViewFactory;

$form = new Form( 'my-form' );
$form->add_elements( $elements );

echo ( new ViewFactory() )->create( 'form' )->render( $form );
```

To render just specific elements, there are a lot of classes available. Just have a look at `ChriCo\Fields\AbstractFactory::$type_to_view`.

---

## Working with data, validation and filters.
After knowing how to create an element and render them, we now want to work with some real data which was send via our form.

```php
<?php
use ChriCo\Fields\Element\Form;
use Inpsyde\Validator\NotEmpty;
use Inpsyde\Filter\WordPress\StripTags;

$form = new Form( 'my-form' );
$form->add_element( $text );

// add a validator to ensure, the element is not empty.
$form->add_validator( 'my-text', new NotEmpty() );

// add a filter to remove all HTML-tags
$form->add_filter( 'my-text', new StripTags() );

// ...and last but not least: add some data to the form
$form->bind_data( [ 'my-text' => '<strong>my text</strong>' ] );

$form->is_valid(); // TRUE

$form->get_element( 'my-text' )->get_value(); // "my text" --> the HTML-tags are stripped because of our filter.
```

---

## Frequently Asked Questions

> Why are you not just using existing packages like the [Symfony Forms](http://symfony.com/doc/current/forms.html) or [Zend Form](https://framework.zend.com/manual/2.4/en/modules/zend.form.intro.html)?

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
