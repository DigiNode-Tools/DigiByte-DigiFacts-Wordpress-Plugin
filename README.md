# DigiByte-DigiFacts-Wordpress-Plugin
A Wordpress plugin to display a random DigiFact every 60 seconds using AJAX and jquery.

## Instructions

Upload the digibyte-digifacts.zip plugin file to Wordpress and activate it.

## How this works

- The current set of DigiFacts are retrieved from the remote web service (digifacts.digibyte.help) and cached on the Wordpress server once per hour
- When a user visits the website, the current set of DigiFacts are cached in the browser's local storage for 60 minutes. A random DigiFact is displayed.
- Every 60 seconds the DigiFact displayed on the screen is refreshed with another random one.
- If more than 60 minutes have passed, new DigiFacts are fetched via an AJAX call to the web server.
- If less than 60 minutes have passed, the DigiFacts from localStorage are used.
