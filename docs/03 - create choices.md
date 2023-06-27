# Create Choices
The `ChoiceElement` allows us to add different choices. This package ships 2 implementations of choices:

- `ArrayChoiceList` - choices assigned via constructor.
- `CallbackChoiceList` - loading choices based on a given `callable` first time it is accessed in view.

To show the differences for both, here's a short example for a `<select>` which shows posts:

## `ArrayChoiceList`
```php
<?php
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\ChoiceList\ArrayChoiceList;

use function ChriCo\Fields\createElement;

$data = array_reduce(
	get_posts(), 
	static function($data, \WP_Post $post): array {
        	$data[ $post->ID ] = "#{$post->ID} {$post->post_title}";

        	return $data;
	}, 
	[]
);

// normal ArrayChoiceList
$select = (new ChoiceElement('post-select'))
	->withAttributes([ 'type' => 'select' ])
	->withChoices(new ArrayChoiceList($data));
	
// or
$select = createElement(
    [
        'attributes' => [
            'name' => 'post-select',
            'type' => 'select',
        ],
        'choices' => $data,
    ]
);
```

## `CallbackChoiceList`
```php
<?php
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\ChoiceList\CallbackChoiceList;

use function ChriCo\Fields\createElement;

/**
 * A callable closure which loads posts.
 * 
 * @return array(int => string)
 */
$data = static function(): array {

	return array_reduce(
		get_posts(), 
		static function($data, \WP_Post $post): array {
			$data[ $post->ID ] = "#{$post->ID} {$post->post_title}";

			return $data;
		}, 
		[]
	);
};

$select = (new ChoiceElement('post-select'))
	->withAttributes([ 'type' => 'select' ])
	->withChoices(new CallbackChoiceList($data));

// or
$select = createElement(
    [
        'attributes' => [
            'name' => 'post-select',
            'type' => 'select',
        ],
        'choices' => new CallbackChoiceList($data),
    ]
);
```

The main difference is: The `CallbackChoiceList` only loads the choices, when they are first time accessed in view. The `ArrayChoiceList` has already assigned the complete choice-values in it's constructor.

## Structure of choices

The choices have a new structure since version 2.1 which will be used internally and allows more flexibility.

**Old structure** (still works)

```php
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\ChoiceList\ArrayChoiceList;
$data = [
    'M' => 'Male',
    'F' => 'Female',
    'D' => 'Diverse'
];

// normal ArrayChoiceList
$select = (new ChoiceElement('select'))
	->withAttributes([ 'type' => 'select' ])
	->withChoices(new ArrayChoiceList($data));
```

**New structure**
```php
use ChriCo\Fields\Element\ChoiceElement;
use ChriCo\Fields\ChoiceList\ArrayChoiceList;
$data = [
    'M' => [
        'label' => 'Male',
        'disabled' => true,
    ],
    'F' => [
        'label' => 'Female',
    ]
    'D' => [
        'label' => 'Diverse'
    ]
];

// normal ArrayChoiceList
$select = (new ChoiceElement('select'))
	->withAttributes([ 'type' => 'select' ])
	->withChoices(new ArrayChoiceList($data));
```

As you can see we now have instead of `array<string|int, string>` mapping a more complex structure `array<string|int, array<{ label: string, disabled?:boolean }>`, which will allow us to additionally set `disabled`. The result is the same, but the "old structure" will be internally converted to "new structure".