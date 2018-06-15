# Changelog

## 1.0
### [1] Breaking changes
There happened a complete rewrite of API, which means we're now going to PSR-2 code style instead of using the underscore methodes from WordPress.

**Removing `set_`***

All `set_ `-methods are now `with`-methods in `camelCase`. So e.G. `set_value` is now `withValue` and also are now returning the object to allow chaining.

**Removing `get_`**

All `get_`-methods are now without this prefix. So e.G. `get_value` is now just `value`, which is way more intuitive.

**`BaseElement` removed**
The `Element\BaseElement` was removed and completly migrated into `Element\Element`.

**Collection add multiple `Element`**
The method `add_elements` is removed. To add multiple `Element`-instances you can either use:

```php
// v1 - chaining
$collection
	->withElement($element1)
	->withElement($element2);
	
// v2 - without chaining
$collection->withElement($element1, $element2);

// v3 - when having elements in an array
$collection->withElement(...[$element1, $element2]);
```

## 0.3.0

### Added
- Introduced new view-class `View\Description` to render the description output.
- Added `wp-coding-standards/wpcs` and automatic code style test via travis-ci.
- `Form::bind_data` is now deprecated and will be removed by `Form::submit` in future.
- Calling `Form::submit` will now set a new state "is_submitted = TRUE".
- Added new method `Form::is_submitted`.
 
### Improvements
- Several smaller improvements in `View\FormRow`.
- `View\Form` now checks for `Element\FormInterface` instead of `Element\Form`.
- `Form::submit` will now automatically trigger validation of elements and binding errors.
- Moved documentation into `docs/`-folder and splitted it into multiple files.

## 0.2.0
- Improved binding and setting data in `Element\Form` by iterating over elements instead of the whole input.
- `Element\Form::set_data` now allows to set data to disabled fields.
- Ensure, that always the element ID is available (defaults to `name`).
- Added new tests for `AttributeFormatterTrait`.
- Implemented boolean-attributes like `disabled` or `required`.
- Introduced new method `is_disabled` to `ElementInterface`.
- Don't bind data to Elements or validate Elements which are disabled. 

## 0.1.0
- First release