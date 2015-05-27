# [Wordpress Stack for Google Cloud PHP AppEngine](https://cloud.google.com/)
## Beta

Based on Bedrock, a modern WordPress stack that helps you get started with the best development tools and project structure.

Much of the philosophy behind Bedrock is inspired by the [Twelve-Factor App](http://12factor.net/) methodology including the [WordPress specific version](https://roots.io/twelve-factor-wordpress/).

## Features

* Better folder structure
* Dependency management with [Composer](http://getcomposer.org)
* Easy WordPress configuration with environment variables in app.yaml 
* Autoloader for mu-plugins (let's you use regular plugins as mu-plugins)

Bedrock is meant as a base for you to fork and modify to fit your needs. It is delete-key friendly and you can strip out or modify any part of it. You'll also want to customize Bedrock with settings specific to your sites/company.

## Requirements

* PHP >= 5.4
* Composer - [Install](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-osx)

## Installation

1. Clone the git repo - `git clone https://github.com/roots/bedrock.git`
2. Run `composer install`
2. Edit `app.yaml` and update environment variables:
  * `DB_NAME` - Database name
  * `DB_USER` - Database user
  * `DB_PASSWORD` - Database password
  * `DB_HOST` - Database host
4. Add theme(s) in `web/app/themes` as you would for a normal WordPress site.
5. Access WP Admin at `https://example.com/wp-admin`

### Deploying with AppEngine

To deploy to Google Appengine use the command 

``` composer deploy``` 

which is an alias of 

```appcfg.py --oauth2 update app.yaml```

More on deploys on Wordpress Starter Project [Documentation on Deplyments] (https://github.com/fuoricentrostudio/appengine-php-wordpress-starter-project#deploy)

## Documentation

* [Folder structure](https://github.com/roots/bedrock/wiki/Folder-structure)
* [Configuration files](https://github.com/roots/bedrock/wiki/Configuration-files)
* [Environment variables](https://github.com/roots/bedrock/wiki/Environment-variables)
* [Composer](https://github.com/roots/bedrock/wiki/Composer)
* [wp-cron](https://github.com/roots/bedrock/wiki/wp-cron)
* [mu-plugins autoloader](https://github.com/roots/bedrock/wiki/mu-plugins-autoloader)

## Contributing

Contributions are welcome from everyone. We have [contributing guidelines](CONTRIBUTING.md) to help you get started.

## Community

Keep track of development and community news.

* Participate on the [Roots Discourse](https://discourse.roots.io/)
* Follow [@rootswp on Twitter](https://twitter.com/rootswp)
* Read and subscribe to the [Roots Blog](https://roots.io/blog/)
* Subscribe to the [Roots Newsletter](https://roots.io/subscribe/)
