# QueryStack - Q&A Website
> Project developed  by:
> GROUP 0111 _(lbaw2311)_, 26/09/2023

## Description 
QueryStack is a platform for technology enthusiasts, professionals, and learners to share their knowledge and solve their problems. QueryStack allows users to ask, answer, vote, comment, and edit questions and answers related to various topics in technology, such as programming languages, frameworks, tools, algorithms, data structures, design patterns, etc. QueryStack also provides features such as tags, badges, reputation points, and leaderboards to categorize, reward, and rank the users and their contributions.
## Project Artifacts
* [ER: Requirements Specification]
* [EBD: Database Specification]
* [EAP: Architecture Specification and Prototype]
* [PA: Product and Presentation] - Not implemented for this delivery

## Local Setup
The project can be modified and tested locally.
### Minimum Prerequisits
* Docker (or another container application)
* PostgreSQL 11 and pgAdmin 4 version 7.
* Git
* Composer 
* Php php-mbstring php-xml php-pgsql php-curl (recommended version 8.1)
### Installation
* Clone the repository locally using:
    ```bash
    git clone https://git.fe.up.pt/lbaw/lbaw2324/lbaw2311.git
    ```
* Start the containers from the project root.
    ```bash
    docker compose up -d
    # To stop the container run:
    # docker compose down
    ```
* Initiate pgAdmin

    Open your browser and navigate to http://localhost:4321. If you encounter any issues with 'localhost,' you may need to use the IP address provided by the virtual machine running Docker.
    Credentials to login:

        Email: postgres@lbaw.com
        Password: pg!password

    In the first use of the development database, you will need to add a new server using the following settings:

        hostname: postgres
        username: postgres
        password: pg!password


### Usage
* Before seting up the website, you should create the .env file with the configurations of the test version not the production one.

    ```bash
    # Create a new .env file based on the .env.thingy reference file.
    cp .env.thingy .env
    ```
* Install the local PHP dependencies
    ```bash 
    composer update
    ```
* To start the development server from the project's root run:
    ```bash 
    # Seed database from the SQL file.
    # Needed on first run and every time the database script changes.
    php artisan db:seed

    # Start the development server
    php artisan serve
    ```

## Product
The live version of the producut can be accessed in:
```
http://lbaw2311.lbaw.fe.up.pt/
```

### Website
Include images

# Features implemented (US)
> For more information about the user stories defined, see the [ER artifact](../../wiki/er).

- [x] US01 - Sign-in
- [x] US02 | Sign-up 
- [x] US11 | Home Page 
- [x] US12 | Search 
- [ ] US13 | Filter 
- [x] US14 | About Page 
- [x] US15 | Contacts Page 
- [ ] US16 | Personal scoreboard and badges 
- [ ] US17 | View scoreboards and badges 
- [x] US201 | Post Question 
- [x] US202 | Post Answer 
- [ ] US203 | Post comment 
- [ ] US204 | Rate/Vote 
- [ ] US205 | Personal Feed 
- [ ] US206 | Follow 
- [x] US207 | Log out 
- [x] US208 | Edit profile 
- [x] US209 | Delete account 
- [x] US31 | Edit question 
- [x] US32 | Delete question 
- [x] US33 | Edit answer 
- [x] US34 | Delete answer 
- [ ] US35 | Edit comment 
- [ ] US36 | Delete comment 
- [ ] US41 | Delete content 
- [ ] US42 | Edit question tags 
- [ ] US43 | Manage reports 
- [ ] US51 | Manage tags 
- [x] US52 | Assign moderator
- [x] US53 | Remove moderator
- [ ] US54 | Manage members 

## Credentials for test purposes
||Administrator|Moderator|Regular User|
|-----|-----|-----|------|
|Email|admin@example.com|moderator@example.com|member1@example.com|
|Password|pass|pass|pass|

## Project Status
The project is currently still in development. The present delivery corresponds to the EAP artifact(A7 e A8 artifacts) and it constitutes the intial prototype of the website.

## Team
* António José Salazar Correia, up201804832@up.pt
* Gonçalo Nuno Leitão Pinho da Costa, up202103336@up.pt
* Tomás Pereira Maciel, up202006845@up.pt
* Ricardo Miguel Matos Oliveira Peralta, up2206392@up.pt