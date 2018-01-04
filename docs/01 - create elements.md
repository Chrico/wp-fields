# Create elements
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

$text = new Element( 'my-text' );
$text->set_attributes( [ 'type' => 'text'] );

$number = new Element( 'my-number' );
$text->set_attributes( [ 'type' => 'number' ] );
```

## Adding a description
All elements are implementing the `DescriptionAwareInterface` which allows us to add a description to each field:
 
```php
<?php
use ChriCo\Fields\Element\Element;
 
$text = new Element( 'my-text' );
$text->set_description( 'Some additional description for our form field.' );
  
echo $text->get_description(); 
```
 
 
## Adding a label
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

## Adding errors
Elements are implementing by default the `ErrorAwareInterface` which allows us to assign errors to the element which can be later re-used in view:

```php
<?php
use ChriCo\Fields\Element\Element;

$text = new Element( 'my-text' );
$text->set_errors( [ 'error-id' => 'Error message' ] );

print_r( $text->get_errors() ); // [ 'error-id' => 'Error message' ]
```

## Using the `ElementFactory`
To save some writing and creating objects by hand, you can use the `ChriCo\Fields\ElementFactory` to generate elements.

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
```