# Munchkin Counter
This is a project, for Munchkin enthusiasts. Backend is based on Symfony while the frontend uses Vue.js.
History: After a few rounds playing munchkin I build this little Webapp, because of general confusion about the Player strength.

There is currently no tutorial, if you want to know how the Webapp works try it by your own.

# Requirements
 - [https://symfony.com/doc/current/setup.html#technical-requirements](https://symfony.com/doc/current/setup.html#technical-requirements)
 - [https://yarnpkg.com/en/docs/install](https://yarnpkg.com/en/docs/install)

# Functionalities

 - New game
 - Rankings by level, shows gender and gift
 - Die, simply reset all your equipement points an buffs/debuffs
 - Change your gender
 - Fight together, calculates the strength of the players
 - Fight, resets buffs/debuffs for the next fight
 - Manage Level
 - Manage Equipment points
 - Manage buffs/debuffs for the next fight
 - Shows your strength

# Deployment
Edit the `.env` file for your needs

    ###> project-vars ###
    OFFLINE_KEY=nope
    ###< project-vars ###

    ###> doctrine/doctrine-bundle ###
    DATABASE_URL=mysql://user:password@dbserver:3306/dbname
    ###< doctrine/doctrine-bundle ###

Install all node dependencies

    yarn install

Get all Symfony dependencies and build the classmap for production

    composer install --no-dev --classmap-authoritative --no-progress

Migrate the database

    php bin/console doctrine:migrations:migrate --no-interaction

Build  webpack

    yarn encore production
