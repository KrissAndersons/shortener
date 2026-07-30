To create this project I used visual studio code built-in AI chat tool.
I asked it to create a project as a start point:
"I need to create url shortener, with docker laravell mariadb nginx vue"
Then it created some things :) I allowed it to do some extra things.
I got my project scaffolding.
I started docker from terminal.
Tested - local test environment was up, but the shortener was not working properly.
This was a good place to start edit and update project.
Made my first commit.

AI was not able to find and fix the problem.
It made some changes in config files.
Two problems:
1:It turns out that docker consistently run into problem with race condition.
Laravel was not able to perform a conection to DB while docker setup was running because DB was not fully ready.
I implemented healthcheck in docker-compose.yml file.
2:Frontend app was making API calls to itself on port 5173 not backend 8080
So i created .env file for frontend to implement variable for VITE_API_BASE_URL which I used to enforce API call to backend.

Then I asked AI to add links after input field with counters. It did a prety good job with it.
But it showed only shrtened code not shortened link, I changed it to show shortened link.

Basiclly everything was done by AI. Except debuggin and small changes.

What would I have done differently, if had dedicated more time to this task:
1. Clean up. I would have deleted things like default migrations.
2. I would have gone through all AI generated stuff more strictly.
3. It seems that AI had made logical changes to gitignore files, but they should have been made after first commit. I would have redone begining off setup, posibly without AI.

To set up project on linux machine you shoold clone repo:

git clone https://github.com/KrissAndersons/shortener.git

And in run command in project dir:

docker compose up --build

frontend should be up running on:
http://localhost:5173/
backend: 
http://localhost:8080/

