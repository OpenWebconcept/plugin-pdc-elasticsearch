# OWC Elasticsearch

## Setup
Add your elasticsearch instance config to the settings tab of the PDC base plugin.

### Filters

There are various [hooks](https://codex.wordpress.org/Plugin_API/Hooks), which allows for changing the output.

##### Filters the settings array.

Allow the settings array to be altered.
```php
owc/elasticsearch/elasticpress/settings
```

Allow the postargs meta array to be altered.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/elasticsearch/elasticpress/postargs/meta
```

Allow the postargs terms array to be altered.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/elasticsearch/elasticpress/postargs/terms
```

Allow the post_author be inserted in the postArgs.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/elasticsearch/elasticpress/postargs/remote-author
```

Allow the postargs array to be altered.
This postArgs will be sent to the Elasticsearch instance.
```php
owc/elasticsearch/elasticpress/postargs/all
```
