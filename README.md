# DigiByte-DigiFacts-Wordpress-Plugin
A Wordpress plugin to display a random DigiFact every 60 seconds using AJAX and jquery.

## Instructions

Upload the digibyte-digifacts.zip plugin file to Wordpress and activate it.

## How this works

1. DigiFacts, are retrieved from the remote service (digifacts.digibyte.help) and stored in localStorage with a timestamp of when they were saved.
2. Each time the page loads (or after 60 minutes), the timestamp is checked.
3. If more than 60 minutes have passed, new DigiFacts are fetched via an AJAX call.
4. If less than 60 minutes have passed, the DigiFacts from localStorage are used.
