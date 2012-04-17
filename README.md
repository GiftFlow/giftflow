# Giftflow

Giftflow is a web application where people can share resources, meet each others' needs and reduce waste, all without spending money. To learn more about the concept behind it and to see the project in action please visit [Giftflow.org](http://www.giftflow.org).

The source code for this project was released per request of the users. We put it here so people can deploy their own gift economy web site, and so that developers can continue to contribute and discuss the future of this application.

If you are looking to contribute or need help setting up a development environment see the [Development Wiki](https://github.com/GiftFlow/giftflow/wiki/Development).


## Installation

### Requirements

This application is based on the CodeIgniter (version 2.0.0) framework which requires the following:

* Apache with 'mod_rewrite' and 'mcrypt' modules enabled.

* PHP 5.3 with the 'GD' or 'iMagick' extensions. Depending on your PHP version, you might need to set date.timezone to something like 'America/New_York' in your php.ini file.

* MySQL

### Configuration

*Database setup*

In MySQL create a database called 'gift'. Import the database structure from 'database/stable/gift.sql', and the default values from 'database/stable/defaults.sql'. If you need to populate the database with test data, see below.

*Application files*

* application/config/database.php - Make sure it matches your MySQL credentials.

* application/config/config.php - Set `$config['base_url']` and `$config['base_path']` to reflect your installation.

* application/config/postmark.php - In order to send automated emails your will need a [Postmark account](http://postmarkapp.com/). Your Postmark API key goes in this file. The same must be done in 'application/libraries/Postmark.php'.

* application/libraries/Alert.php - Set `$config["from_email"]` to your postmark signature email address.

* application/libraries/geo.php - Set to your own [IPInfoDB](http://ipinfodb.com/) API key in order to geolocate users.

* .htaccess - If you are installing this in a place different then your web root, you might need to change your RewriteRule accordingly.

* uploads/ - Make sure this directory has writable so it can accept photo uploads. The same needs to be done for uploads/thumb/.

