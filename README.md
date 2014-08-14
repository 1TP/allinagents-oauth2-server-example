allinagents-oauth2-server-example
=================================

a sample oauth2 server written in php

## QUICK EXPLANATION

First, it is important that you understand the basics of oauth. Some good reading:

http://oauth2.thephpleague.com/
http://oauth.net/

This demo uses the Client Credentials Grant Type where the client uses their credentials to retrieve an access token directly, which allows access to resources under the client's control. This demo app will also act as two different services:

1. An Authentication Server - A server which issues access tokens after successfully authenticating a client and resource owner, and authorizing the request.

2. A Resource Server  - A server which sits in front of protected resources (for example "tweets", users' photos, or personal data) and is capable of accepting and responsing to protected resource requests using access tokens.

## INSTALLATION

1. Download or clone this repo to your local machine

2. Create a DB and run /my_oauth2_db.sql

3. Configure your DB settings in /server.php

## USAGE

1. CREATE AN OAUTH USER:

INSERT INTO oauth_users (username, password, first_name, last_name) VALUES ("username", SHA1("password"), "firstname", "lastname")

note: This could be seperate from your applications users. In this case, if one of your applications users needs API access, you could have your application additionally add them as an oAuth user as well and just reference the oAuth user in your applications user entry.

----------

2. CREATE A CLIENT:

INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES ("testclient", "testpass", "http://fake/");

note: again, a client represents an external application that will be accessing your applications API on the users behalf. After creating the client, you should share the client_id and client_secret with the 3rd party privately.

----------

3. CREATE A PERMANENT USER TOKEN:

curl -u testclient:testpass http://localhost/my-oauth2-walkthrough/token.php -d 'grant_type=password&username=username&password=password&scope=permanent'

note: testclient and testpass are the client_id and client_secret, since this request will be coming from the client and they need to be authorized. Since this is the first time we are creating a token and we are using the User Credentials grant type, we will also need user credentials.

4. RETRIEVE RESOURCE:

curl http://localhost/my-oauth2-walkthrough/resource.php -d 'access_token=60a776e186087d26d7f6dd549e12857852e61e0e'

note: Now that we have an access token we can retrieve data form the resource server from the client on the users behalf. cool stuff.

## NOTES

This example was created with the following oauth library:
http://bshaffer.github.io/oauth2-server-php-docs/

I modified the library to allow permanent, non expiriring auth tokens. This is for example purposes only so implment (or not) this type of functionality however you wish. To make a permanent token simply add the permanent scope when creating it. Again, not the best practice and for demo purposes only.
