# Setup
This application is setup to run with PHP 8.2. If the php version is below that, it show an error.

## Getting Started

Assuming that the server is installed with latest PHP version (8.2), please start the server by running following command in the terminal.

```
php -S localhost:8000
```

## Endpoints

There are five endponts available for this application

Assuming that the server is running on http://localhost:8000

### / - Home
```
http://localhost:8000/
```
Home page with welcome message

Example response 
```
{
    "status": 1,
    "code": 200,
    "errors": [],
    "data": {
        "message": "Welcome to the Books API"
    }
}
```

### /books - books list from the XML file
```
http://localhost:8000/books
```
Book list with the pagination details. This endpoint supports search, page, limit attributes
When search query string is given, result will filter based on title and author.

```
/books?search=Corets
```

```
/books?page=2
```

```
/books?limit=5
```

```
/books?search=Corets&limit=5&page=2
```

search = search terms (string)
limit = records per request (integer)
page = current page to navigate, default is 1 (integer)

Example response 
```
{
    "status": 1,
    "code": 200,
    "errors": [],
    "data": {
        "total": 12,
        "total_pages": 2,
        "limit": 10,
        "current_page": 1,
        "results": [
            {
                "id": "bk101",
                "author": "Gambardella, Matthew",
                "title": "XML Developer's Guide",
                "price": 44.95
            },
        ...
        ]
    }
}
```
If there are no books then
```
{
    "status": 0,
    "code": 500,
    "errors": [
        "No books found for given request"
    ]
}
```

### /books/:id - book details for the given id
```
http://localhost:8000/books/:id 
```
List the book for the given id. If not found, error message will show 

Example response 
If book found
```
{
    "status": 1,
    "code": 200,
    "errors": [],
    "data": {
        "id": "bk101",
        "author": "Gambardella, Matthew",
        "title": "XML Developer's Guide",
        "genre": "Computer",
        "price": 44.95,
        "publish_date": "2000-10-01",
        "description": "An in-depth look at creating applications \n      with XML.",
        "size": 308
    }
}
```

If book not found
```
{
    "status": 0,
    "code": 404,
    "errors": [
        "Book not found for id bk101s"
    ]
}
```

### /books/stats - Stats of the book collection
Example response 
```
{
    "status": 1,
    "code": 200,
    "errors": [],
    "data": {
        "no_of_books": 12,
        "average_book_price": 17.87,
        "average_book_size": 360,
        "file_size": 4429
    }
}
```

### /books/download - Downloads the source xml file
This will download the original xml file