# Installation

To run this code, you will need:

1. A Webserver
2. PHP
3. A MySQL-Instance
4. The [npm](https://npmjs.com) package manager
5. The [Composer Package Manager](https://getcomposer.org/) for PHP dependencies

## Set up the Database

To set up the database, you'll only have to run the prepared `database_setup.sql` script. It creates a database `leberkasrechner` with all the neccessary tables, indexes, constraints etc.

Now, if you wish to fill up your database with butcher data, run the `update_butchers.py` script:

```bash
python3 update_butchers.py
py update_butchers.py
```

The `database_setup.sql` script above does _not_ set up the database user which does all the queries for website visitors not logged in. You have to do that yourself. Run the following SQL snipped **from your DB root account:**

```sql
CREATE USER 'lview'@'localhost' IDENTIFIED WITH mysql_native_password BY 'YOURSTRONGPASSWORD';
GRANT USAGE ON *.* TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.author TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.license TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.image TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.image_butcher TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.butchers TO 'lview'@'localhost';
GRANT SELECT ON leberkasrechner.blog_posts TO 'lview'@'localhost';
GRANT SELECT, INSERT ON leberkasrechner.comments TO 'lview'@'localhost';
GRANT SELECT (`id`, `edit`, `admin`) ON `leberkasrechner`.`users` TO 'lview'@'localhost';
ALTER USER 'lview'@'localhost' ;
```

**Don't forget to replace the password with your own, strong one.** This db user will be used for the sql-queries needed for the readonly-part of the website (_not the internal part of it_). You can also change the username (`lview` in the snippet), but that's not neccessary.

Now, we'll need a second account which can create new database accounts and has the rights to do so. With this account, the code will be able to create new database accounts with `SELECT`, `INSERT`, `UDPATE` and `DELETE` rights for _website frontend accounts_. To create this user, run the following SQL-snippet **from your DB root account:**

```sql
CREATE USER 'lusercreate'@'localhost' IDENTIFIED WITH mysql_native_password BY 'ANOTHER_STRONGPASSWORD';
GRANT INSERT ON leberkasrechner.users TO 'lusercreate'@'localhost';
GRANT CREATE USER ON *.* TO 'lusercreate'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.butchers TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image_butcher TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.image TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.license TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.comments TO 'lusercreate'@'localhost' WITH GRANT OPTION;
GRANT SELECT, INSERT, UPDATE, DELETE ON leberkasrechner.blog_posts TO 'lview'@'localhost' WITH GRANT OPTION;
GRANT SELECT (id, username, email, edit, admin) ON leberkasrechner.users TO lusercreate@localhost WITH GRANT OPTION;
ALTER USER lusercreate@localhost ;
```

Again, don't forget to replace the password with your own, strong one and **do NOT use the same password as for the `lview` account!**

## Storing Database Credentials

Create a `.env` file by copying the `.env.example` file. This file will store your MySQL database credentials.

Here's what each variable in the `.env` file represents:

- `DBSERVER`: The IP address of your database server. Typically, this is `127.0.0.1` for a local database.
- `DBPORT`: The port number your database server is listening on. The default for MySQL and MariaDB is usually `3306`.
- `DBUSER`: The username for your database _view_ user (`lview` in this example) .
- `DBPASSWORD`: The password for your database _view_ user. Keep this secure!
- `UC_DBUSER`: The username for your database user _with extended rights_ (`lcreateuser` in this example)
- `UC_DBPASSWORD`: The password for your database user _with extended rights_. Keep this secure!
- `DBNAME`: The name of the database you want to connect to. (Note: Currently, only the name `leberkasrechner` is supported due to some bad programming)

Here’s an example of what your `.env` file should look like:

```ini
DBSERVER = "127.0.0.1"
DBPORT = 3306
DBUSER = "DBUSER"
DBPASSWORD = "YOURSTRONGPASSWORD"
UC_DBUSER = "ANOTER_DBUSER"
UC_DBPASSWORD = "ANOTHER_STRONGPASSWORD"
DBNAME = "leberkasrechner"
```

## Set up your PHP installation

With the Leberkasrechner, file are possible to do via web. If you wish to allow that on your instance, you'll have to enable these in your PHP config if they're not enabled by default. To do so, in your `php.ini` file, just set `file_uploads` to `On`. Further, you can specify your temporary upload folder and the maximum upload file size.

```ini
file_uploads=On
upload_max_filesize=60M
```

## Installing the dependencies

Last, you will have to install the node modules given in the `package.json` file:

```bash
npm install
```

6.  A PHP package manager, preferably Composer. Requirements are listet in `composer.json`. To install the requirements, run

```bash
composer install
```
