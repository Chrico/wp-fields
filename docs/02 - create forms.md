# Create forms
The `Element\Form` represents the complete form where data can be set, submitted, validated and filtered.

Here's the same example for an `<input type="text" />` and `<select>` which are assigned to a form:

```php
<?php
use ChriCo\Fields\ElementFactory;

$factory = new ElementFactory(); 

// The text element
$text = $factory->create(
	[
		'attributes' => [
			'name' => 'my-text',
			'type' => 'text'
		],
		'label'             => 'My label',
		'label_attributes'  => [ 'for' => 'my-id' ],
		'errors'            => [ 'error-id' => 'Error message' ]
	]
);

// the select element
$select = $factory->create(
	[
		'attributes' => [
			'name' => 'my-select',
			'type' => 'select' 
		],
		'label'     => 'My Label',
		'choices'   => [ 'for' => 'my-id' ]
	]
);

$form = $factory->create(
	[
    	'attributes' => [
    		'name' => 'my-form',
    		'type' => 'form'
    	]
    ]
);
$form->withElement($text, $select)
```

----

## Create complete Form via `ElementFactory`

And now the complete example with 1 specification which could be placed into an own config file:

```php
<?php
use ChriCo\Fields\ElementFactory;

$form = (new ElementFactory())->create(
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

----

## Preset data
To set data without validation which is e.G. loaded from database, you can use the `Form::withData`-method. We're going to re-use the `$form` from above and assign data to the `my-text`-Element.

```php
<?php

// snip - see instance creation above

$form->withData([ 'my-text' => '<p>value of my-text</p>' ]);

echo $form->element('my-text')->value(); // '<p>value of my-text</p>'
```

----

## Submit data
The second way to bind data to elements is the usage of `Form::submit` which also validates the data and binds errors to the element.


```php
<?php

// snip - see instance creation above

$form->isSubmitted(); // FALSE

$form->submit([ 'my-text' => '<p>value of my-text</p>' ]);

$form->isSubmitted(); // TRUE
$form->isValid(); // TRUE - no validators are set.

echo $form->element('my-text')->value(); // '<p>value of my-text</p>'
```