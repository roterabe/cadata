# Cadata
This is a simple CRUD API for getting data on popular car models.

# Instructions

In order to setup this API locally, you'd need to first run a server like **Apache**.
Once you've set up a local server on **127.0.0.1 or localhost**, then you may proceed by moving the whole project folder in your server's folder.


Next up, you'll need a browser and a database to work with. To generate a sample database, simply load up `localhost/path-to-folder/Schema/Create.php`. You should get a message that all needed tables have been created successfully.

From there onwards, you may proceed with using the API. Since this is a simple CRUD-based API, you have the standard Create, Update, Delete and etc. functions.
The way you make API calls is via **GET**, **POST** and **PUT** requests. Or in other words, you can type in specific URLs such as: 

`localhost/path-to-folder/index.php/cars/list` to list all car data available in the database.
`localhost/path-to-folder/index.php/cars/list?limit=10&offset=1` to get a rudimentary for of pagination.
`localhost/path-to-folder/index.php/cars/filter?model=E30&orderby=model&order=descending` to filter by car model.

The above were basic **GET** requests which are entirely via URL, but if you wanted to insert data for instance, then you'd need to use a **POST** request with a body. Here is an example:

![Creating data](https://github.com/roterabe/cadata/blob/main/insert-data.png)

And this is an example for updating data by specifying an ID:

![Updating data](https://github.com/roterabe/cadata/blob/main/update-data.png)

And this is an example of doing a soft delete, meaning that the data remains, but is now invisible unless really needed:

![Deleting data](https://github.com/roterabe/cadata/blob/main/delete-data.png)




