# Changelog

## 0.3.0 (NOT RELEASED)

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