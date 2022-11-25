
# RestoRate

Restorate est une application de notation de restaurant.



## How to run the Project
first you need clone the project to the directory of your choice using this command line

composer require symfony/runtime

## Deployment

 First Clone the project 
```bash
 git clone https://github.com/imastourigit/Restorate.git
```
 Now go inside the directory of the project and Launch symfony runtime 
```bash
composer require symfony/runtime
```
after its completed, start by creating the Database by this command
```bash
symfony console doctrine:database:create
```
Then start by making a migration and then migrate it:

```bash
symfony console make:migration
```
```bash
symfony console doctrine:migrations:migrate
```

After the database is fully created we need to fill it, run the fixture loading command

```bash
 symfony console  doctrine:fixtures:load
```
And now you can run the symfony server by this command 
```bash
 symfony server:start
```

## Environment Variables

To log in, you will need a username and a password

`Password` : 123456

`username`: username_(numbers from 0 to 3 are for Restaurants accounts and 4 - 12 normal users)




## Note

username_1 with password 123456 : has a restaurant account.

Log in, and populate the app with restaurants and reviews.

