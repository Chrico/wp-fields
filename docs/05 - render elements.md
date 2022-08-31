# Render elements
Creating elements is only 1 part of this package. The most important one is to render elements into HTML.


## Render a single element
To render our `$element` into HTML we can use the provided `ViewFactory`. 

```php
<?php
use ChriCo\Fields\ViewFactory;
use ChriCo\Fields\ElementFactory;

use function ChriCo\Fields\createElement;
use function ChriCo\Fields\renderElement;

$text_spec = [
	'attributes' => [
		'name' => 'my-text',
		'type' => 'text'
	],
	'label'     => 'My label'
];

$element = ( new ElementFactory() )->create( $text_spec );
echo ( new ViewFactory() )->create( 'text' )->render( $element );

// or shorter:
$element = createElement($text_spec);
echo renderElement($form);
```

This will output the `<input type="text" />` with `<label>`. To render just specific elements, there are a lot of classes available. Just have a look at `ChriCo\Fields\AbstractFactory::$type_to_view`.


## Render complete forms

To render complete forms with elements, you can use again the `ViewFactory` in combination with `Element\Form`:

```php
<?php
use ChriCo\Fields\ViewFactory;
use ChriCo\Fields\ElementFactory;

$form = ( new ElementFactory() )->create( 
	[
		'attributes' => [
			'name' => 'my-form',
			'type' => 'form'
		],
		'elements' => [
			[
				'attributes' => [
					'name' => 'my-text',
					'type' => 'text'
				],
				'label'             => 'My label',
				'label_attributes'  => [ 'for' => 'my-id' ],
				'errors'            => [ 'error-id' => 'Error message' ]
			],
		],
	]
);

echo ChriCo\Fields\renderElement($form);
```