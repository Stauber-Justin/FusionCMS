# FusionCMS Notes

## Core Manager Classes

### Template Library
FusionCMS uses the `Template` library to load module data. When a page is built, the library calls `loadModuleManifest()` which validates the module folder and reads the module's `manifest.json` file. The manifest is decoded into an array and stored in `$this->module_data`.

### Administrator Library
The `Administrator` class scans all folders under `application/modules` when `loadModules()` is executed. It reads each `manifest.json` and ensures fields such as `name`, `enabled` and `description` exist. If a manifest is missing the `name` field the folder name is used instead. The method also records whether a module has configuration files.

## Event Setup
`application/config/Events.php` hooks into the `pre_controller` event. After preparing output buffering and debug listeners it validates the current module:
- the module must be enabled,
- `min_required_version` is patched if missing and checked against the core version,
- each module's `config/Event.php` file is loaded.

If the user library exists and the visitor is not already logged in, cookie based login is attempted using the `fcms_username` and `fcms_password` cookies.

## Modules
- Admin panel|Default set of tools and pages for the admin panel
- armory|Armory search page
- auth|Auth Module for Login
- changelog|Allows developers to post changelog messages
- Character viewer|Displays the character stats and items
- Donate panel|PayPal donation system
- Error handler|Displays permission-related errors and 404
- GM panel|Provides web tools for game masters
- Guild viewer|Displays guild members
- Icon provider|Required by the armory to display icons
- Installer|Installer FusionCMS
- Item viewer|Requires the tooltip. Displays the tooltip content on a direct-linkable page
- Levelup|Enables your users to use blizzlike Levelup.
- Private messages|Allows users to communicate privately with in your site
- news|Displays server news to the users. Acts as front page
- Online players|Displays all online characters
- Custom pages|Allows you to create custom pages
- User profiles|Displays user info and their characters
- PVP Statistics|Statistics about the top players in your realms.
- Register|Allows users to sign up
- Sidebox: Countdown|Displays a spotlight
- Sidebox: custom|Allows you to create sideboxes with custom text
- Sidebox: Discord|Allows you to create sideboxes Dicsord
- Sidebox: donation goal|Displays a progress bar based on monthly donations and the goal
- Sidebox: user info & log in|Displays some user information if logged in and provides a log in form if they aren't.
- Sidebox: language picker|Let your users select language
- Sidebox: Extended Server Status|Automated system
- Sidebox: poll|Allows your users to vote on certain questions
- Sidebox: shoutbox|Let users express themselves in the public shoutbox
- Sidebox: spotlight|Displays a spotlight
- Sidebox: server status|Displays online players for each realm
- Sidebox Top|Statistics about the top players; achievements and guilds in your realms.
- Sidebox: PvP ladder|Shows top X PvP players
- Sidebox: Top Voters|
- Sidebox: online visitors|Shows the amount of visitors in the past 5 minutes
- Item store|Lets your users purchase ingame items from a web store
- Teleport hub|Lets your users teleport - either for free or for the cost of vp/dp/gold
- Item tooltip|Required by the armory and character viewer to display item stats
- User panel|Displays user info and provides them with a set of links
- Unstuck|Enables your users to use blizzlike Unstuck.
- Vote panel|Obtain vote points by voting

## Notable Formulas
- **Vote cooldown**: `getNextTime()` calculates the next allowed vote time using `hour_interval`. It checks recent `vote_log` entries and returns the formatted time until a user can vote again.
- **Monthly vote tally**: `updateMonthlyVotes()` increments the current month row in `monthly_votes` or inserts a new row if none exists.
- **PvP ranking suffix**: `addNumberSuffix()` appends `st`, `nd`, `rd` or `th` based on the ranking position.
- **Top guild averaging**: in `getTopGuild()` the achievement points for a guild are averaged by dividing total points by the number of members and rounding.

## Enabling Modules
1. Log into the admin panel and open **Modules**. Each module has an **Enable** or **Disable** button that toggles the `enabled` flag in its `manifest.json`.
2. Alternatively, edit `application/modules/your_module/manifest.json` and set `"enabled": true`. Saving the file activates the module the next time the site loads.
