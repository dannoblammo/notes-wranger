# Note Wrangler

## About the Note Wrangler

Note Wrangler is a simple note creation/sharing sample app using [Laravel 5.5](https://github.com/laravel/laravel) and [Vue.js](https://vuejs.org).

A live demo can be viewed on [Heroku](https://note-wrangler.herokuapp.com).

### Using the Note Wrangler

It's pretty simple!  
1. Register an account and log in.
2. Write your ideas for making money.  
3. If you want to share a note with other people, add the people's email addresses.  Please include mine.
4. (Probably other things.)
5. Profit! 


## Installation  

Here are the dry details in order to get you up and running on your development computer.

### Prerequisites

To install and run Note Wrangler on your own computer, you'll need the following software installed: 
 - PHP v7.1.x (v7.2 may have problems with Laravel 5.5) with following extensions installed:
    - OpenSSL
    - PDO
    - MbString
    - Tokenizer
    - XML          
 - Composer 
 - Node
 - NPM and/or Yarn
 - an empty PostgresSql (preferred) or MySQL database
 - Git 

More information on PHP extensions and/or virtual environments for Laravel using Vagrant can be found [here](https://laravel.com/docs/5.5/installation#server-requirements).


### Setting Up Locally 

1. Clone the repository to your local machine, on your terminal type `git clone https://github.com/dannoblammo/notes-wranger.git`
2. Install all the vendor libraries:
    - run `composer install`
    - run `yarn` (or `npm install`) 
3. Configure your local settings for Laravel by following the instructions [here](https://laravel.com/docs/5.5/configuration)
4. Configure the app to connect to your local database by following the instructions [here](https://laravel.com/docs/5.5/database)
5. Run `php artisan migrate` in order to create the necessary tables in your local database
6. In another terminal start your built-in PHP server `php artisan serve --port=8080` (or use any port number you want other than 8080)
7. Run `npm run watch` in order for all the app's Javascript and CSS to be compiled
8. Open a browser and head to `http://localhost:8080`


### Setting Up On Heroku

If you're interested in transforming your amazing clone of this app into money, you can sign up for a free Heroku account and deploy this app there for public consumption. 

1. Assuming you've signed up for Heroku, create a new Heroku app following [their instructions](https://devcenter.heroku.com/articles/getting-started-with-php#introduction) 
2. Add a free Postgres database plan to your new Heroku app.
3. Push your local master branch to the new app. (If you haven't already, see step 1.) 
4. Populate the database by running `heroku run php artisan migrate`
5. View it in your browser `heroku open`
6. Profit!


## Contributing
This software won't be under further development, it's just a personal project for demonstration purposes.  Feel free to clone and conquer!  


   
