# Changelog

## 0.2.0 (NOT RELEASED)
- Improved binding of data to collection elements for non-existing request vars.
- Ensure, that always the element ID is available (defaults to `name`).
- Added new tests for `AttributeFormatterTrait`.
- Implemented boolean-attributes like `disabled` or `required`.
- Introduced new method `is_disabled` to `ElementInterface`.
- Don't bind data to Elements or validate Elements which are disabled. 

## 0.1.0
- First release