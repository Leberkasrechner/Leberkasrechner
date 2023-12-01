# Installation
To run this code, you will need:
1. A Webserver
2. PHP
3. A MySQL-Instance

   Create a database ```leberkasrechner``` and a table ```butcher``` in it:

        CREATE DATABASE IF NOT EXISTS `leberkasrechner` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
        USE `leberkasrechner`;

        CREATE TABLE `butchers` (
                `id` bigint NOT NULL,
                `lat` double DEFAULT NULL,
                `lon` double DEFAULT NULL,
                `tags` text NOT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
   
   To fill the database with data, run the ```update_butchers.py``` script:

        python3 update_butchers.py
        py update_butchers.py

   The MySQL Password in the code is set to ```xxxyyy```. If you want to run your instance publicly, create a database user with only the needed rights (```select``` in the ```butchers``` table) and insert their credentials in the ```components/conn.py``` file. Note: The user given in the python code (also change that) needs ```select```, ```insert``` and ```update``` permissions.


4. Install the node modules given in the ```package.json``` file:

        npm install

5. A PHP package manager, preferably Composer. Requirements are listet in ```composer.json```. To install the requirements, run

        composer install

# To do

- [ ] Add opening hours panel
- [ ] Interaction options for OpenStreetMap
- [ ] Design Landing Page
- [ ] New Marker Icon on Front Page
- [ ] **Image database**
- [ ] Features for vegetarian and vegan alternatives
- [ ] perhaps rating system
- [ ] Page titles

# Ressouces 
Useful notes for dev
- [taginfo](https://taginfo.openstreetmap.org/tags/shop=butcher#combinations)