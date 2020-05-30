# BCMart
This project is made to complete quiz from web service class.   
This project is about the system call BCMart which is e-store with e-wallet payment. Inside BCMart have many stores who sell it own products.

# Documentation
This system have 3 roles with different access to the system.

### All Roles
* Login  
    Request Method: Post  
    Request Url:
    ```
    {{host}}/api/v1/login
    ```  
    Request Body:
    ```json
    {
        "email": "admin@mail.com",
        "password": "admin"
    }
    ```
* Register  
    Request Method: Post  
    Request Url:
    ```
    {{host}}/api/v1/register
    ```  
    Request Body:
    ```json
    {
        "name" : "Test",
        "email" : "test@mail.com",
        "password" : "asdqwe123",
        "password_confirmation" : "asdqwe123",
        "role_id": 3
    }
    ```
* Logout  
    Request Method: Post  
    Request Url:
    ```
    {{host}}/api/v1/register
    ```  

### Admin
* Store
