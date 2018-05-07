# PDC Elasticsearch

### How do I get set up? ###

* Unzip and/or move all files to the /wp-content/plugins/pdc-elasticsearch directory
* Log into WordPress admin and activate the ‘PDC Elasticsearch’ plugin through the ‘Plugins’ menu


### Filters & Actions

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

##### Action for changing main Plugin object.
```php
'owc/pdc-elasticsearch/plugin'
```

See OWC\Elasticsearch\Config->set method for a way to change this plugins config.

Via the plugin object the following config settings can be adjusted
- metaboxes
- rest_api_fields

##### Filters the settings array.


Allow the ElasticPress config array to be altered.
```php
owc/pdc-elasticsearch/config/elasticpress
```

Allow the Settings config array to be altered.
```php
owc/pdc-elasticsearch/config/settings
```

Allow the Settings-pages config array to be altered.
```php
owc/pdc-elasticsearch/config/settings_pages
```

Allow the postargs meta array to be altered.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/pdc-elasticsearch/elasticpress/postargs/meta
```

Allow the postargs terms array to be altered.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/pdc-elasticsearch/elasticpress/postargs/terms
```

Allow the post_author be inserted in the postArgs.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/pdc-elasticsearch/elasticpress/postargs/remote-author
```

Allow the postargs array to be altered.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/pdc-elasticsearch/elasticpress/postargs/all
```

### Translations ###

If you want to use your own set of labels/names/descriptions and so on you can do so.
All text output in this plugin is controlled via the gettext methods.

Please use your preferred way to make your own translations from the /wp-content/plugins/pdc-elasticsearch/languages/pdc-elasticsearch.pot file

Be careful not to put the translation files in a location which can be overwritten by a subsequent update of the plugin, theme or WordPress core.

We recommend using the 'Loco Translate' plugin.
https://wordpress.org/plugins/loco-translate/

This plugin provides an easy interface for custom translations and a way to store these files without them getting overwritten by updates.

For instructions how to use the 'Loco Translate' plugin, we advice you to read the Beginners's guide page on their website: https://localise.biz/wordpress/plugin/beginners
or start at the homepage: https://localise.biz/wordpress/plugin

### Running tests ###
To run the Unit tests go to a command-line.
```bash
cd /path/to/wordpress/htdocs/wp-content/plugins/pdc-elasticsearch/
composer install
phpunit
```

For code coverage report, generate report with command line command and view results with browser.
```bash
phpunit --coverage-html ./tests/coverage
```

### Contribution guidelines ###

##### Writing tests
Have a look at the code coverage reports to see where more coverage can be obtained.
Write tests
Create a Pull request to the OWC repository

### Who do I talk to? ###

If you have questions about or suggestions for this plugin, please contact <a src="mailto:hpeters@Buren.nl">Holger Peters</a> from Gemeente Buren.
