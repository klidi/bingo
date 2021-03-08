# Bingo

## Some words 

First. thank you for your time. Here I will do some explanation about what i have done and why.

As as someone who has absolutely no idea about Bingo i had to do some research before starting. 
Problem is i got hooked up to it and i did more then i should have done :P.
Please keep in mind everything is basic and no where close to a real world application. The objects have only enough code
to provide the functionality that was required and the extra functionality i decided to add.

I am aiming for code readability , simplicity and some performance also.
There is more things i could have done , or things i could have done or could have done better but
on the other hand im also trying to not invest an insane amount of hrs on it. I am a big fan of rich models and i am totally against
the idea of having rich service classes and anemic models/entites which u find plenty today that have only getters and setters. Services in my code are only a medium between application and domain model. Holding very little logic.

So things like casting classes , DTOs/resource classes are missing.

U will noticed that php doc comments are not in all methods. this is on purpose. I think with php 7.4 and above there is not need for them.
I use type hinting and return type + i try to make the method name as explicit and descriptive as possible. 


## Domain Modeling

User Game Stripe Card Cells
This are the main models

Game - User : many to many relationship. Basically users can participate in multiple games;

User - Stripe : One to many
Game - Stripe : One to many

Stripe - Card : One to many
Stripe in 90-ball has 6 cards all with unique numbers 1-90. The card here comply to the 90-ball rules : like 
no more then 2 empty cells in a row etc. I have decided to keep the number of cards per Stripe to 4 and not 6
cuz i did not want to spend any more time to tweak the code that picks the pattern ex: (10011101 1 filled , 0 empty cells) that will be applied for each row.

The logic behind building the card row patterns is simple. Card has 9 cols which are basically filled or empty (1 and 0); 111111111 is 512.
So what i do is i generate all permutations iterating from 1 to 512 and then filtering those patterns that dont comply with rules of 90-ball card.

Card - Cell : One to many

Stripe - Card - Cell is an aggregate. The creation of a Stripe with its cards and cells is atomic and the creation cascades through
the relationship tree. Its not exactly a DDD aggregate cuz in case of matching the bingo ball to card cells im just calling the Cell model
directly and not going through the aggregate root(stripe), did it to seepd up some developmet :P. My intention was that creation and deletion (to be implemented if needed) should also be atomic.

Also Cells in domain model are those parts of the card that have a value. The positionin of thell in the card is defined by row and col value. So empty cells are not saved or rapresented at all
in backend. I think they are more important in ui where they need to be visualized;

## Database design

The database is build around the above domain models
I have provided migration files and seeding data.

Indexing is pretty basic. I usually tend to not rush into indexing without having dome some heavy testing. Its hard to guess right the indexing
just based on cardinality. Its more like a case by case things depending on queries etc.

## Project Structure

I have followed the standard lumen folder structure almost. Normally i opt for a better artchitecture and structure.
U will see also things like Interfaces categorized/organized as Interfaces and Contracts.
Those that i add to Interfaces folder have more a generic nature , with multiple methods defined.
Those that are defined as contracts have a very limited scope and have one max 2 methods defined.

I also tried to add swooletw/laravel-swoole to do websockets and give a nicer solution when simulating a game
but 1. in lumen takes to much time to config , countrary to laravel. 2. I switched like a month ago to php 8 and i needed to add swoole again on my mac.

## Setup
    composer install
    php artisan migrate
    php artisan db:seed
    php -S localhost:8000 -t public
    
    php 7.4 or higher

I hope i did not forget something    

## Usage
After u run db:seed u will get one game , 1k users, 1k stripes (1 per user), 4k cards and 60 000 cells. All this attached to single game.
U can go inside the Seed class and change the 1k to 5k 15k whatever ... 

Personally i have tried with 1 game 15k users 15k stripes 60k cards 900 000 cells. When running the game , between each ball draw, which
includes marking all matching cells and checking if there is a Single Line win takes 2 seconds (very limited resources on my mac). This can be optimised further, my implementation is basic. Its unrealistic that u have more the 1k players per game (i have no idea whats the upper limit, just throwing a number out there :P)
but still given the nature of a bingo game 2 seconds from ball/number to the next one its fine i think. Again this is the opinnion of a bingo noob

To create a stripe manually 

    POST | http://{hostname}/games/1/users/2/stripe
    
To run the game and get a Single Line winner/s

    php artisan game:start    

It will ask for input , just give the game id 1 after u have done the seeding.
The command will work correctly only one time for a given game. I am not stting the game to completed or anything due to time.
to try again just to db:seed again and give the next game id, ex: 2 or recreate the db with seeds, migrate:fresh and db:seed.

U can do the seeding command multiple times to give different game id inputs to game:start
Game is not complete is basically just the first round of a 90-ball bingo game.

## Testing
Sorry ... i left it for last ... i know i know ... should have been first , but there was other things i wanted to deliver in the task.
Anyway i was going to use https://pestphp.com/ instead of directly using php unit. If u guys have not used it before , give it a try
its a blast.
