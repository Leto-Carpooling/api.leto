# Leto APIs
This repo holds the APIs that powers the front end of Leto Car pooling E-hailing application

# Setting up for development
- Install `XAMPP` or its equivalent
- clone this repo in the `htdocs` folder or its equivalent
- Install php composer for dependency management
- Set up your `.env` file following the `.env-example` provided. Only change the variable values. This is because the `Utility::getEnv()` function will return an Object with properties, for example, `dbName` for `DB_NAME`, in the `.env` file.
- Set up your `/api/includes/passwords.inc.php` following the `/api/includes/example-passwords.inc.php`. You will notice that your need the password for Leto's email and your own twilio account SID and auth token. Also, the firebase and google maps credentials too.
- create a database in your `MySQL` server to hold the information from the API. Put the database credentials in the .env file.
- Turn on your servers and from the root directory, run `php ./api/database/migration.php` to create the database. Each time you run this command, the database is dropped and recreated... be cautions, no database seeding is available.
- You can install the VS Code extension `Thunder Client` for making api requests and testing. You can use `Postman` also.
