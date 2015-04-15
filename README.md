# [Worpress Stack for Google AppEngine](https://cloud.google.com/)
[![Build Status](https://travis-ci.org/roots/bedrock.svg)](https://travis-ci.org/roots/bedrock)

Based on Bedrock, a modern WordPress stack that helps you get started with the best development tools and project structure.

# ToC

* [Quick Start](#quick-start)
* [Features](#features)
* [Required](#requirements)
* [Installation/Usage](#installationusage)
  * [via Composer](#using-create-project)
  * [Manually](#manually)
* [Deploys](#deploys)
* [Documentation](#documentation)
  * [Folder Structure](#folder-structure)
  * [Configuration Files](#configuration-files)
  * [Environment Variables](#environment-variables)
  * [Composer](#composer)
  * [WP-CLI](#wp-cli)
  * [mu-plugins Autoloader](#mu-plugins-autoloader)
* [Contributing](#contributing)
* [Support](#support)

## Quick Start

Or run `composer create-project roots/bedrock <path>` (see [Installation/Usage](#installationusage) for more details) to just get a new copy of Bedrock locally.

## Features

* Dependency management with [Composer](http://getcomposer.org)
* Better folder structure
* Environment variables in app.yaml 
* Autoloader for mu-plugins (let's you use regular plugins as mu-plugins)

Bedrock is meant as a base for you to fork and modify to fit your needs. It is delete-key friendly and you can strip out or modify any part of it. You'll also want to customize Bedrock with settings specific to your sites/company.

Much of the philosphy behind Bedrock is inspired by the [Twelve-Factor App](http://12factor.net/) methodology including the [WordPress specific version](https://roots.io/twelve-factor-wordpress/).

Note: While Bedrock is a project from the guys behind the [Sage starter theme](https://roots.io/sage/), Bedrock isn't tied to Sage in any way and works with any theme.

## Requirements

* PHP >= 5.4

## Installation/Usage

See [Documentation](#documentation) for more details on the steps below.

### Using `create-project`

Composer's `create-project` command will automatically install the Bedrock project to a directory and run `composer install`.

The post-install script will automatically copy `.env.example` to `.env` and you'll be prompted about generating salt keys and appending them to your `.env` file.

Note: To generate salts without a prompt, run `create-project` with `-n` (non-interactive). You can also change the `generate-salts` setting in `composer.json` under `config` in your own fork. The default is `true`.

1. Run `composer create-project fuoricentrostudio/appengine-wordpress-stack <path>` (`path` being the folder to install to)
2. Edit `app.yaml` and update environment variables:
  * `DB_NAME` - Database name
  * `DB_USER` - Database user
  * `DB_PASSWORD` - Database password
  * `DB_HOST` - Database host
3. Add theme(s)
5. Access WP Admin at `https://example.com/wp-admin`

### Manually

1. Clone/Fork repo
2. Run `composer install`
2. Edit `app.yaml` and update environment variables:
  * `DB_NAME` - Database name
  * `DB_USER` - Database user
  * `DB_PASSWORD` - Database password
  * `DB_HOST` - Database host
4. Add theme(s)
5. Access WP Admin at `https://example.com/wp-admin`


### Deploying with AppEngine

See Wordpress Starter Project [Documentation on Deplyments] (https://github.com/fuoricentrostudio/appengine-php-wordpress-starter-project#deploy)

## Documentation

### Folder Structure

```
├── composer.json
├── config
│   ├── application.php
│   └── environments
│       ├── development.php
│       ├── staging.php
│       └── production.php
├── vendor
└── web
    ├── app
    │   ├── mu-plugins
    │   ├── plugins
    │   └── themes
    ├── wp-config.php
    ├── index.php
    └── wp
```

The organization of Bedrock is similar to putting WordPress in its own subdirectory but with some improvements.

* In order not to expose sensitive files in the webroot, Bedrock moves what's required into a `web/` directory including the vendor'd `wp/` source, and the `wp-content` source.
* `wp-content` (or maybe just `content`) has been named `app` to better reflect its contents. It contains application code and not just "static content". It also matches up with other frameworks such as Symfony and Rails.
* `wp-config.php` remains in the `web/` because it's required by WP, but it only acts as a loader. The actual configuration files have been moved to `config/` for better separation.
* Capistrano configs are also located in `config/` to make it consistent.
* `vendor/` is where the Composer managed dependencies are installed to.
* `wp/` is where the WordPress core lives. It's also managed by Composer but can't be put under `vendor` due to WP limitations.

### Configuration Files

The root `web/wp-config.php` is required by WordPress and is only used to load the other main configs. Nothing else should be added to it.

`config/application.php` is the main config file that contains what `wp-config.php` usually would. Base options should be set in there.

For environment specific configuration, use the files under `config/environments`. By default there's is `development`, `staging`, and `production` but these can be whatever you require.

The environment configs are required **before** the main `application` config so anything in an environment config takes precedence over `application`.

Note: You can't re-define constants in PHP. So if you have a base setting in `application.php` and want to override it in `production.php` for example, you have a few options:

* Remove the base option and be sure to define it in every environment it's needed
* Only define the constant in `application.php` if it isn't already defined.

### Environment Variables

Currently, the following env vars are required:

* `DB_USER`
* `DB_NAME`
* `DB_PASSWORD`
* `WP_HOME`
* `WP_SITEURL`

### Composer

[Composer](http://getcomposer.org) is used to manage dependencies. Bedrock considers any 3rd party library as a dependency including WordPress itself and any plugins.

See these two blogs for more extensive documentation:

* [Using Composer with WordPress](https://roots.io/using-composer-with-wordpress/)
* [WordPress Plugins with Composer](https://roots.io/wordpress-plugins-with-composer/)

Screencast ($): [Using Composer With WordPress](https://roots.io/screencasts/using-composer-with-wordpress/)

#### Plugins

[WordPress Packagist](http://wpackagist.org/) is already registered in the `composer.json` file so any plugins from the [WordPress Plugin Directory](http://wordpress.org/plugins/) can easily be required.

To add a plugin, add it under the `require` directive or use `composer require <namespace>/<packagename>` from the command line. If it's from WordPress Packagist then the namespace is always `wpackagist-plugin`.

Example: `"wpackagist-plugin/akismet": "dev-trunk"`

Whenever you add a new plugin or update the WP version, run `composer update` to install your new packages.

`plugins`, and `mu-plugins` are Git ignored by default since Composer manages them. If you want to add something to those folders that *isn't* managed by Composer, you need to update `.gitignore` to whitelist them:

`!web/app/plugins/plugin-name`

Note: Some plugins may create files or folders outside of their given scope, or even make modifications to `wp-config.php` and other files in the `app` directory. These files should be added to your `.gitignore` file as they are managed by the plugins themselves, which are managed via Composer. Any modifications to `wp-config.php` that are needed should be moved into `config/application.php`.

#### Updating WP and plugin versions

Updating your WordPress version (or any plugin) is just a matter of changing the version number in the `composer.json` file.

Then running `composer update` will pull down the new version.

#### Themes

Themes can also be managed by Composer but should only be done so under two conditions:

1. You're using a parent theme that won't be modified at all
2. You want to separate out your main theme and use that as a standalone package

Under most circumstances we recommend NOT doing #2 and instead keeping your main theme as part of your app's repository.

Just like plugins, WPackagist maintains a Composer mirror of the WP theme directory. To require a theme, just use the `wpackagist-theme` namespace.

### wp-cron

Bedrock disables the internal WP Cron via `define('DISABLE_WP_CRON', true);`. If you keep this setting, you'll need to manually set a cron job like the following in your crontab file:

`*/5 * * * * curl http://example.com/wp/wp-cron.php`

## WP-CLI

Bedrock works with [WP-CLI](http://wp-cli.org/) just like any other WordPress project would. Previously we required WP-CLI in our `composer.json` file as a dependency. This has been removed since WP-CLI now recommends installing it globally with a `phar` file. It also caused conflicts if you tried using a global install.

The `wp` command will automatically pick up Bedrock's subdirectory install as long as you run commands from within the project's directory (or deeper). Bedrock includes a `wp-cli.yml` file that sets the `path` option to `web/wp`. Use this config file for any further [configuration](http://wp-cli.org/config/).

## Vagrant/Ansible

Vagrant and Ansible integration with Bedrock can now be found in the separate [bedrock-ansible](https://github.com/roots/bedrock-ansible) project. Basic instructions exist in that project's `README`, but if you want a Vagrant box tied to a specific Bedrock based WP application, copy the example `Vagrantfile` into your app's repo and edit the necessary file paths.

Note that using Ansible you no longer need to manually create/edit a `.env` file (or use `composer create-project` to generate one). Ansible will generate a `.env` based on its config and automatically generate salts/keys.

## mu-plugins autoloader

Bedrock includes an autoloader that enables standard plugins to be required just like must-use plugins.
The autoloaded plugins are included after all mu-plugins and standard plugins have been loaded.
An asterisk (\*) next to the name of the plugin designates the plugins that have been autoloaded.
To remove this functionality, just delete `web/app/mu-plugins/bedrock-autoloader.php`.

This enables the use of mu-plugins through Composer if their package type is `wordpress-muplugin`. You can also override a plugin's type like the following example:

```json
"installer-paths": {
  "web/app/mu-plugins/{$name}/": ["type:wordpress-muplugin", "roots/soil"],
  "web/app/plugins/{$name}/": ["type:wordpress-plugin"],
  "web/app/themes/{$name}/": ["type:wordpress-theme"]
},
```

[Soil](https://github.com/roots/soil) is a package with its type set to `wordpress-plugin`. Since it implements `composer/installers` we can override its type.

## Contributing

Everyone is welcome to help [contribute](CONTRIBUTING.md) and improve this project. There are several ways you can contribute:

* Reporting issues (please read [issue guidelines](https://github.com/necolas/issue-guidelines))
* Suggesting new features
* Writing or refactoring code
* Fixing [issues](https://github.com/roots/bedrock/issues)
* Replying to questions on the [forum](https://discourse.roots.io/)

## Support

Use the [Roots Discourse](https://discourse.roots.io/) forum to ask questions and get support.
