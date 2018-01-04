# Create elements - advanced
To save some writing and creating objects by hand, you can use the `ChriCo\Fields\ElementFactory` to generate elements.

Here's the same example for our 3 elements:

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

$form_spec = [
	'attributes' => [
		'name' => 'my-form',
		'type' => 'form'
	]
];

$form = $factory->create( $form_spec );
$form->add_element( $text );
$form->add_element( $select );
```

And now the complete example with 1 specification which could be placed into an own config file:

```php
<?php
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
			[
				'attributes' => [
					'name' => 'my-select',
					'type' => 'select' 
				],
				'label'     => 'My Label',
				'choices'   => [ 'for' => 'my-id' ]
			],
		],
	]
);
```
