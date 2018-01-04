# Create collections

The `CollectionElement` can be used to group multiple elements together.

```php
<?php
use ChriCo\Fields\Element\CollectionElement;
use ChriCo\Fields\Element\Element;

$collection = new CollectionElement( 'my-collection' );

$text_element = new Element( 'my-text' );
$text_element->set_attribute( 'type', 'text' );

$number_element = new Element( 'my-number' );
$number_element->set_attribute( 'type', 'number' );

$collection->add_elements( [ $text_element, $number_element ] );
```

This way we have later on in our view for each element the prefix from our collection to the `id`- and `name`-attribute:

| element | name | id |
| ------------- | ------------- | ------------- |
| `$text_element` | `my-collection[my-text]` | `my-collection_my-text` |
| `$number_element` | `my-collection[my-number]` | `my-collection_my-number` |

**Note:** The manipulation of `id`- and `name`-attribute will only happen when creating the view. 

## Create collection via spec

To create multiple elements for a collection, we can easily use the `ElementFactory`:

```php
<?php
use ChriCo\Fields\ElementFactory;

$collection = ( new ElementFactory() )->create( 
	[
		'attributes' => [
			'type' => 'collection',
			'name' => 'my-collection',
		],
		'elements' => [
			[
				'attributes' => [
					'type' => 'text',
					'name' => 'my-text',
				]
			],
			[
				'attributes' => [
					'type' => 'number',
					'name' => 'my-number',
				]
			],
		]
	]
);
```