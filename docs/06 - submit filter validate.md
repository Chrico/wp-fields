# Working with data, filters and validation
After knowing how to create an element and render them, we now want to work with some real data which was send via our website.

```php
<?php
use ChriCo\Fields\Element\Element;
use ChriCo\Fields\Element\Form;
use Inpsyde\Validator\NotEmpty;
use Inpsyde\Filter\WordPress\StripTags;

$form = new Form( 'my-form' );
$form->add_element( new Element( 'my-text' ) );

// add a validator to ensure, the element is not empty.
$form->add_validator( 'my-text', new NotEmpty() );

// add a filter to remove all HTML-tags
$form->add_filter( 'my-text', new StripTags() );

$form->is_submitted(); // FALSE

// ...and last but not least: add some data to the form
$form->submit( [ 'my-text' => '<strong>my text</strong>' ] );

$form->is_valid(); // TRUE
$form->is_submitted(); // TRUE

$form->get_element( 'my-text' )->get_value(); // "my text" --> the HTML-tags are stripped because of our filter.
```