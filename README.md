allinagents-oauth2-server-example
=================================

a sample oauth2 server written in php

## QUICK EXPLANATION

First, it is important that you understand the basics of oauth. Some good reading:

http://oauth2.thephpleague.com/

http://oauth.net/

This demo uses the Client Credentials Grant Type and Refresh Tokens:

http://tools.ietf.org/html/rfc6749#section-4.4

With this grant type, the client uses their credentials to retrieve an access token directly, which then allows access to resources under the client's control. This demo app will also act as two different services:

And 

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

curl -u testclient:testpass http://localhost/my-oauth2-walkthrough/token.php -d 'grant_type=password&username=username&password=password&scope=permanent%20temporary'

Notes:
testclient and testpass are the client_id and client_secret, since this request will be coming from the client and they need to be authorized. Since this is the first time we are creating a token and we are using the User Credentials grant type, we will also need user credentials.

This step creates non expiriring (or at least VERY long lived) access and refresh tokens. Note that the requested scope is permanent AND temporary. This is because both types of tokens will be generated based off of this initial token.

Example return value:
{"access_token":"e779cd50a10aee3dc070359ecef3630d462a4b4a","expires_in":732460774,"token_type":"Bearer","scope":"permanent temporary","refresh_token":"0f6ec51f66f5448c5eba9dfbb0934bbff624d60b"}

2: GENERATE TEMPORARY TOKEN:
Use the refresh token from step 1 to generate a new temporary token/
curl -u testclient:testpass http://localhost/my-oauth2-walkthrough/token.php -d 'grant_type=refresh_token&refresh_token=refresh_token_from_step_1&scope=temporary'

This creates a new access token with a much more short lived expiration. This new token should also take on the attributes (user_id, client_id, etc) of the original token created in step 1. This does NOT create a new refresh token. Since the refresh token from step 1 is considered permanent, we can continue to use that in oder to regenerate new short lived auth tokens.


3. RETRIEVE RESOURCE:

curl http://localhost/my-oauth2-walkthrough/resource.php -d 'access_token=access_token_from_step_2'

note: Now that we have an access token we can retrieve data form the resource server from the client on the users behalf. cool stuff.

## NOTES

This example was created with the following oauth library:
http://bshaffer.github.io/oauth2-server-php-docs/

I modified the library to allow permanent, non expiriring auth tokens. This is for example purposes only so implment (or not) this type of functionality however you wish. To make a permanent token simply add the permanent scope when creating it. Again, not the best practice and for demo purposes only.
