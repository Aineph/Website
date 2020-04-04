# Website

This is the source code for my personal website, written in PHP using the framework Symfony.

# Summary

* Build system
* User Manual
* Specifications
* Testing

# Build system

The project uses symfony.
Before being able to run it on your machine, you need to replace this line in your "*.env*" file by your database
configuration.

```
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7
```

Now, you can run the following commands to install all the dependencies.

```
$> composer install
$> yarn install
```

Depending on the *db_name* you will use, you will need to create the database manually.
If you also use mysql, you can run the following command in the interpreter:

```
mysql> CREATE DATABASE db_name;
```

To fill this database, you need to create a migration. Run the following commands:

```
$> php bin/console make:migration
$> php bin/console doctrine:migrations:migrate
```

Now you can finally launch the server.

```
$> symfony serve
```

# User Manual

This website contains several sections:

* Home page
* Blog
* Portfolio
* Client space
* Administration

In order to become an administrator, your user account must possess the role "ROLE_ADMIN".
Once it's done you can create articles on the blog, add some projects to the portfolio and read all the messages
created from the administration interface.

# Specifications

This website has been created using Symfony 4. For the frontend part, the webpack-encore.js has been used. This
webpack provides some features to dynamically load frameworks such as bootstrap, font-awesome and so forth.

# Testing

This section is not yet completed. It will be available in a future release.
