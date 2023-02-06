## Purchases system
## [Server requirements](https://laravel.com/docs/9.x/deployment#server-requirements)

## Steps to set up the project:

### If you have  docker installed
- ```cp .env.example .env``` and update with your data like database and redis
- ``` docker-compose up ```
- Inside the web container execute the following commands:
#### To go inside the container purchase_system_php (php) run  (this line only for docker)
### Change the value of UID in .env User id number in ubuntu in the terminal just write id and get uid value in case of using docker this is very important step to avoid permission issue
- ```composer install```
- ```php artisan key:generate```
- ```php artisan migrate --seed```
## You need to install node & npm
- ```npm install```
- ```npm run build```
### To run code Fixer
- ``` ./vendor/bin/pint ```
### Technologies and tools
- [PHP](https://www.php.net)
- [Laravel9](https://laravel.com/docs/9.x)
- [Laravel Breeze](https://laravel.com/docs/9.x/starter-kits#laravel-breeze)
- [Vue.js3](https://vuejs.org)
- [Nodejs](https://nodejs.org/en)
- [Inertia.js](https://inertiajs.com)
- [TailwindCSS](https://tailwindcss.com)
- [Docker](https://docs.docker.com) & [docker-compose](https://docs.docker.com/compose)
- [Mysql](https://www.mysql.com)
- [UI](https://github.com/justboil/admin-one-vue-tailwind)

## Project requirements
* Create sample website to manage company's customers and their purchases where each customer has multiple purchases as per below:
* authenticate users by username and password (stateless authentication).
* provide CRUD operations on customers and purchases.
* only admin users will be able to add, update or delete customers data.
* The website should be built using Laravel framework and vue.js.

## Tech notes:
- As the system is a blog then we should do server-side rendering allowing your visitors to see your website prior to the JavaScript fully loading. It also makes it easier for search engines to index your site.
- Docker-compose setup has:
    - webserver nginx web server
    - php:8.2
    - database (mysql server)
    - phpmyadmin

## About each container
- webserver: server(nginx web server)
- database_server: (blogging-platform_database): it has mysql database server
- phpmyadmin: (blogging-platform_phpmyadmin): ui for database (database client)

## Links of local dev for docker
- [Purchases system](http://localhost)
- [phpmyadmin](http://localhost:8080)

## Links of local dev for built-in server
- [Purchases system](http://localhost:8000)

## Note:
- You need to log in as Admin with username: admin password: password
- You need to create some customers
- You then can create purchase but first add some products in create purchase page there is a button to add products
- In create purchase page you can search from the customer then select it 
- In create purchase page after you added some products you can add record to the purchase with the products you recently added or from previous add
- Mainly purchase order record first field is search you need to search with product name then select it from results
- Create/edit/delete/show/list for purchase
- Create/edit/delete/list for customers
- Api for search for products/customers
- Api for create product
