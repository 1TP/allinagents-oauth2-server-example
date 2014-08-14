allinagents-oauth2-server-example
=================================

a sample oauth2 server written in php

# QUICK RUNDOWN


## INSTALLATION

1. Download or clone this repo to your local machine
2. Create a DB and run /my_oauth2_db.sql
3. Configure your DB settings in /server.php

## USAGE

First, it is important that you understand the basics of oauth. Some good reading:

http://oauth2.thephpleague.com/
http://oauth.net/

This demo uses the Client Credentials Grant Type where the client uses their credentials to retrieve an access token directly, which allows access to resources under the client's control. This demo app will also act as two dirrent services:

1. An Authentication Server - A server which issues access tokens after successfully authenticating a client and resource owner, and authorizing the request.
2. A Resource Server  - A server which sits in front of protected resources (for example "tweets", users' photos, or personal data) and is capable of accepting and responsing to protected resource requests using access tokens.



## NOTES

This example was created with the following oauth library:
http://bshaffer.github.io/oauth2-server-php-docs/

I modified the library to allow permanent, non expiriring auth tokens. This is for example purposes only so implment (or not) this type of functionality however you wish. To make a permanent token simply add the permanent scope when creating it. Again, not the best practice and for demo purposes only.
