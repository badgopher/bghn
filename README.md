# BadGopher Headline News
This repo contains the archived source for BadGopher Headline News, an RSS aggregation website created in 2003 by a naïve Web 1.0 era webmaster who'd just learned PHP and MySQL and figured this was a good idea.

**This code is a historical artifact** and does not represent its creator's contemporary understanding of design and security principles or best practices. It is no longer running in any live environment, and that should not change for any reason. There's a lot of _bad_ ideas implemented in here.

## The database
The database was designed for MySQL 5. It was last successfully run on MariaDB 10.6.
- `db/bghn_schema.sql` contains the database schema required to run the site
- `db/bghn_full.sql` contains the database schema and sample data

## Initial setup
1. Create the database.
1. Edit **dbStringBuilder.php** and enter the specifics for your database connection then run the script.
1. Take the resulting string and update the `DB_CONNECT` definition in **inc.config.php**.
1. Create 2 jobs:
   - `*/15 * * * * /path/to/php "/path/to/webroot/parser/import.php"`

   - `0 0 * * 0 /path/to/php "/path/to/webroot/dbcleanup.php"`

## Security issues, bug reports, and support
**This code unsupported.** It contains poorly implemented functionality that is prone to security vulnerabilities. There is no avenue for help with or updates to the code in this repo by its original author. It really should not be used by anyone, anywhere.
