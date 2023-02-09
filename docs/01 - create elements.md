# Create elements
To work later in view with Elements, you have first to create the Element itself. There are following different types of Elements available:


| name                | extends             | description                                                          |
|---------------------|---------------------|----------------------------------------------------------------------|
| `Element`           |                     | default element for inputs, textareas, buttons                       |
| `ChoiceElement`     | `Element`           | allows us to set and get choices for checkboxes or radios            |
| `CollectionElement` | `Element`           | allows us to group multiple elements into one namespace together     |
| `Form`              | `CollectionElement` | allows us to work with data and delegate them to containing elements |

Here's a short example of creating...

- 1 text element
- 1 select element
- 1 collection of both

```php
<?php
use ChriCo\Fields\Element\Element;

$text = (new Element('my-text'))
	->withAttributes([ 'type' => 'text']);

$number = (new Element('my-number'));
	->withAttributes([ 'type' => 'number' ]);
```

## Adding a description
All elements are implementing the `DescriptionAwareInterface` which allows us to add a description to each field:
 
```php
<?php
use ChriCo\Fields\Element\Element;
 
$text = (new Element('my-text'))
	->withDescription('Some additional description for our form field.');
  
echo $text->description(); 
```
 
 
## Adding a label
All elements are implementing the `LabelAwareInterface` which allows us to add a `<label>` and label attributes:

```php
<?php
use ChriCo\Fields\Element\Element;

$text = (new Element('my-text'))
	->withLabel('My label')
	->withLabelAttributes([ 'for' => 'my-id' ]);

echo $text->label(); // 'My label'
print_r($text->labelAttributes()); // [ 'for' => 'my-id' ] 
```

## Adding errors
Elements are implementing by default the `ErrorAwareInterface` which allows us to assign errors to the element which can be later re-used in view:

```php
<?php
use ChriCo\Fields\Element\Element;

$text = (new Element('my-text'))
	->withErrors([ 'error-id' => 'Error message' ]);

print_r($text->errors()); // [ 'error-id' => 'Error message' ]
```

## Adding Validators and Filters
Validation callbacks can be used to validate the Element value, while Filters are being used to sanitize the Element value.

```php
use ChriCo\Fields\Element\Element;

$text = (new Element('my-text'))
    ->withValidator(static fn(string $value): bool => is_email($value))
    ->withFilter(static fn($value): string => sanitize_text_field($value));
```

## Using the `ElementFactory`
To save some writing and creating objects by hand, you can use the `ChriCo\Fields\ElementFactory` to generate elements.

```php
<?php
use ChriCo\Fields\ElementFactory;

// The text element
$spec = [
	'attributes' => [
		'name' => 'my-text',
		'type' => 'text'
	],
	'label'             => 'My label',
	'label_attributes'  => [ 'for' => 'my-id' ],
	'errors'            => [ 'error-id' => 'Error message' ]
];

$text = (new ElementFactory())->create($spec);
// or
$text = \ChriCo\Fields\createElement($spec);
```
