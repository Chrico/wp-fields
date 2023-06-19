# Intro

ChriCo WP-Fields is a Composer package (not a plugin) that allows to generate form fields in WordPress.

## Motivation

I'm exhausted of writing fields by hand again and again and again. Since WordPress does not provide any kind of "help" here, you've to write everything from scratch for each Plugin over and over again.

There are several different locations in WordPress, where you can use form fields:

- Post edit screen in MetaBoxes
- Settings pages
- User edit screen
- Term edit screen
- Comment edit screen
- Customizer

Each location has different possibilities to add custom fields. For example in "Post edit"-screen you're already within a `<form>`, on a "Settings page" you're forced to write everything from scratch.

Here's a short example of writing a so called "Metabox" to the "Post edit"-screen:

```php
<?php
add_action(
	'plugins_loaded',
	static function(): void {

		add_action(
			'add_meta_boxes',
			/**
			 * @param string $post_type
			 *
			 * @return bool
			 */
			static function(string $post_type) : bool {

				// some checks if we're allowed to print our fields.
				if ($post_type !== 'post') {
					return FALSE;
				}

				add_meta_box(
					'maybe-unique-id',
					'Here\'s a title for the MetaBox',
					function (\WP_Post $post) {

						// The MetaBox-content with your fields are going here...
						// so in theory something like: <input type="text" /> and so on
					},
					$post_type
		       );

				return TRUE;
			}
		);

	}
);
```

That's a lot of code for just creating 1 single input field. And the field has also to be created completely by hand. No help, no API anywhere. So, this package will provide an elegant way to create form fields via easy configuration to save some time.

---

## What this package not does

This package will only provide form fields *for WordPress* and the way to bind everything (data, validation, filtering) to that element.

Nothing more and nothing less. Everything related to:

- Validation of data - see for example [Inpsyde-Validator](https://github.com/inpsyde/inpsyde-validator)
- Filtering data - see for example [Inpsyde-Filter](https://github.com/inpsyde/inpsyde-filter)
- Metaboxes - see for example [Metabox Orchestra](https://github.com/inpsyde/MetaboxOrchestra)

will be done in separate packages and just used in this one.
