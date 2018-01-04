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

## Adding choices
The `ChoiceElement` allows us to add different choices. This package ships 2 implementations of choices:

- `ArrayChoiceList` - choices assigned via constructor.
- `CallbackChoiceList` - loading choices based on a given `callable` first time it is accessed in view.

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
 * 
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

The main difference here is: The `CallbackChoiceList` only loads the choices, when they are first time accessed in view. The `ArrayChoiceList` has already assigned the complete choice-values in it's constructor.

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