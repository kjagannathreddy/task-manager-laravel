Requirements
php 8.1 or higher, I have used php8.2

Setup instructions

1. run this command "composer update"
2. create a copy of ".env.example" file and rename it as ".env".
3. provide your mysql database credentials in .env file
4. run "php artisan migrate"
5. run "php artisan db:seed --class=UserSeeder", it will create sample user with credentials
		email => 'sampleuser@example.com'
        'password' => 'Sample@123'
6. run "php artisan serve" it will run application on port 8000
http://localhost:8000/

base url = 'http://localhost:8000/'


below are the api endpoints with payload
1. register api "http://localhost:8000/api/register"
post method
body -> raw json
{
    "name": "Jagan Reddy",
    "email": "jagan@reddy.com",
    "password": "Password@123"
}
2. login api "http://localhost:8000/api/login"
post method
body -> raw json
{
    "email": "jagan@reddy.com",
    "password": "Password@123"
}
it will return jwt token, use it in below apis
3. create task api "http://localhost:8000/api/tasks"
post method
body -> form data
headers -> Authorization:Bearer {token from login api}
note: form data is used as we need to send files

subject:Task hello subject
description:Task description
start_date:2024-03-20
due_date:2024-03-25
status:New
priority:High
notes[0][subject]:Note 1 subject
notes[0][note]:Note 1 content
notes[0][attachments][]: select multiple files
notes[1][subject]:Note 2 subject
notes[1][note]:Note 2 content
notes[1][attachments][]: select multiple files

4. get tasks api "http://localhost:8000/api/get-tasks"
post method
body raw json
headers -> Authorization:Bearer {token from login api}
{
    "filter": {
        "status": "Complete",
        "due_date": "2024-03-20",
        "priority": "High",
        "notes": true/false
    }
}
all filters are optional, you can send one or more or nothing at all.