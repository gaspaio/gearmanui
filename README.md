# Gearman UI [![Build Status](https://api.travis-ci.org/gaspaio/gearmanui.png?branch=master)](https://travis-ci.org/gaspaio/gearmanui)

**Gearman UI** is a PHP monitoring dashboard for [Gearman Job Servers](http://gearman.org).

It is built on [Silex](http://silex.sensiolabs.org/) and [AngularJS](http://angularjs.org/) and aims to be a solid, extensible and fast way to visually follow the activity on your job queues.

**More information: [gaspaio.github.com/gearmanui](http://gaspaio.github.com/gearmanui)**

# TODO

## code modifs
X - put configuration in root, erase app dir.
X - set default log file & the possibility to configure an abs filepath in the config file.
X - use silex traits in controller (PHP 5.4)
X - autoloader PSR4
- front deps package manager (bower ?)
- angular 1 latest
- Correct issues in Github
- test in Travis
- Release new version
- Make it available on Packagist
- log service provider to wrap monolog config

## update doc
- propose  a zip package with a full build (for those that don't want to install composer & bower)
- run composer install without dev dependencies : composer install --no-dev
- Add dev & tests procedures in README file
- Document version bug changes : log level config & log file directory

