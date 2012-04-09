# Giftflow

Giftflow is a web application where people can share resources, meet each others' needs and reduce waste, all without spending money. To learn more about the concept behind it and to see the project in action please visit Giftflow.org.

The source code for this project was released per request of the users. We put it here so people can deploy their own gift economy web site, and so that developers can continue to contribute and discuss the future of this application.

## Install

If you need help figuring out how to download this project, There is a good [introduction to using Git here](http://learn.github.com/). 

### Requirements

This application is based on the CodeIgniter framework which requires PHP 5 and MySQL installed on your web server.

### Configuration

In MySQL create a database called 'gift'. Import the database structure from database/stable/gifts.sql, and the default values from database/stable/defaults.sql.

Then edit the following files:

* application/config/database.php - Make sure it matches your MySQL credentials.

* application/config/config.php - Set `$config['base_url']` and `$config['base_path']` to reflect your installation.

* application/config/postmark.php - In order to send automated emails your will need a [Postmark account](http://postmarkapp.com/). Your Postmark API key goes in this file.

* application/libraries/geo.php - Set to your own Google Maps api key.

* application/models/user.php - All the places where we encrypt user passwords and salts need to be addressed. Look for “ENCRYPT HERE” messages.

* application/libraries/auth.php - Here I also removed the encryption we do of forgotten password codes and more. The “ENCRYPT " messages will show you where.


### Generating test data

If you are not deploying to a production server and would like to generate some fake data for your database, you can use our Summoner application.

To use the Summoner, enter the database/stable/Summoner directory and edit summon.php and set the totals to what you need. Try to stick to the same relative proportions. No more transactions than goods. Save summon.php and run it in a terminal. It should generate a file called Balrog.sql which can be imported into your existing empty database.

IMPORTANT TIP: If you use the Summoner to create a fake database -- the password to all the fake accounts is 'giftflow'.

