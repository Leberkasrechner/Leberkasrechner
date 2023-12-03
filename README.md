# Installation

To run this code, you will need:

1. A Webserver
2. PHP
3. A MySQL-Instance
4. The [npm](https://npmjs.com) package manager
5. The [Composer Package Manager](https://getcomposer.org/) for PHP dependencies

## Creating the Database

Create a database `leberkasrechner` and a table `butcher` in it:

```sql
CREATE DATABASE IF NOT EXISTS `leberkasrechner` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `leberkasrechner`;

CREATE TABLE `butchers` (
        `id` bigint NOT NULL,
        `lat` double DEFAULT NULL,
        `lon` double DEFAULT NULL,
        `tags` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

Then, a fulltext index over the `butchers` table:

```sql
ALTER TABLE `leberkasrechner`.`butchers` ADD FULLTEXT (`tags`);
```

To fill the database with data, run the `update_butchers.py` script:

```bash
python3 update_butchers.py
py update_butchers.py
```

Last, if you wish to run the blog yourself too, you need a blog post database. Here, blog posts and their timestamps are stored. It's a simple format, so no authors are stored and blogposts can (at this time) only be created directly on the database. You can set up the database with the following command:

```sql
CREATE TABLE `blog_posts` (
    `id` int(11) NOT NULL,
    `created` timestamp NOT NULL DEFAULT current_timestamp(),
    `modified` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
    `header` text NOT NULL,
    `content` longtext NOT NULL
) ENGINE=InnoDB;
```

## Storing Database Credentials

Create a `.env` file by copying the `.env.example` file. This file will store your MySQL database credentials.

Here's what each variable in the `.env` file represents:

- `DBSERVER`: The IP address of your database server. Typically, this is `127.0.0.1` for a local database.
- `DBPORT`: The port number your database server is listening on. The default for MySQL is usually `3306`.
- `DBUSER`: The username for your database.
- `DBPASSWORD`: The password for your database. Keep this secure!
- `DBNAME`: The name of the database you want to connect to.

Here’s an example of what your `.env` file should look like:

```
DBSERVER="127.0.0.1"
DBPORT=3306
DBUSER="DBUSER"
DBPASSWORD="YOURSTRONGPASSWORD"
DBNAME="leberkasrechner"
```

## Securing Installation for Production

If you want to run your instance publicly, create a database user with only the needed rights (`select` in the `butchers` table) and insert their credentials in the `.env` file. Note: The user given in the python code (also change that) needs `select`, `insert` and `update` permissions. For example, this could be the SQL query for creating the php user:

```sql
CREATE USER 'yourusername'@'%' IDENTIFIED WITH caching_sha2_password BY 'yourpassword';
GRANT USAGE ON *.* TO 'yourusername'@'%';
GRANT SELECT ON `leberkasrechner`.* TO `yourusername`@`%`;
```

This could be the code for the python user:

```sql
CREATE USER 'yourusername'@'%' IDENTIFIED WITH caching_sha2_password BY 'yourpassword';
GRANT USAGE ON *.* TO 'yourusername'@'%';
GRANT SELECT, INSERT, UPDATE, DELETE ON `leberkasrechner`.* TO 'yourusername'@'%';
```

## Installing the dependencies

5.  Install the node modules given in the `package.json` file:

        npm install

6.  A PHP package manager, preferably Composer. Requirements are listet in `composer.json`. To install the requirements, run

        composer install

# To do

- [x] Add opening hours panel
- [x] Interaction options for OpenStreetMap
- [ ] Design Landing Page
- [x] New Marker Icon on Front Page
- [ ] **Image database**
- [ ] Features for vegetarian and vegan alternatives
- [ ] perhaps rating system
- [ ] Page titles
- [x] Website Footer (Privacy Policy, etc.)
- [ ] get `butcher.json` from `cdn.phipsiart.at`
- [ ] Docker Image

# Ressouces

Useful notes for dev

- [taginfo](https://taginfo.openstreetmap.org/tags/shop=butcher#combinations)
