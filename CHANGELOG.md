# Changelog

## 0.3.0 (NOT RELEASED)

### Added
- Introduced new view-class `View\Description` to render the description output.
- Added `wp-coding-standards/wpcs` and automatic code style test via travis-ci.
 
### Improvements
- Several smaller improvements in `View\FormRow`.
- `View\Form` now checks for `Element\FormInterface` instead of `Element\Form`.

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