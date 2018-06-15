# Create Choices
The `ChoiceElement` allows us to add different choices. This package ships 2 implementations of choices:

- `ArrayChoiceList` - choices assigned via constructor.
- `CallbackChoiceList` - loading choices based on a given `callable` first time it is accessed in view.

To show the differences for both, here's a short example for a `<select>` which shows posts:

**1. ArrayChoiceList**
```php
<?php
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\ChoiceList\ArrayChoiceList;

$data = array_reduce( 
	get_posts(), 
	function( $data, \WP_Post $post ) {
        	$data[ $post->ID ] = "#{$post->ID} {$post->post_title}";

        	return $data;
	}, 
	[]
);

// normal ArrayChoiceList
$select = (new ChoiceElement( 'post-select' ))
	->withAttributes( [ 'type' => 'select' ] )
	->withChoices( new ArrayChoiceList( $data ) );

```

**2. CallbackChoiceList**
```php
<?php
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\ChoiceList\CallbackChoiceList;

/**
 * A callable closure which loads posts.
 * 
 * @return array( int => string )
 */
$data = function() {

	return array_reduce( 
		get_posts(), 
		function( $data, \WP_Post $post ) {
			$data[ $post->ID ] = "#{$post->ID} {$post->post_title}";

			return $data;
		}, 
		[]
	);
};

$select = (new ChoiceElement( 'post-select' ))
	->withAttributes( [ 'type' => 'select' ] )
	->withChoices( new CallbackChoiceList( $data ) );

```

The main difference is: The `CallbackChoiceList` only loads the choices, when they are first time accessed in view. The `ArrayChoiceList` has already assigned the complete choice-values in it's constructor.
