# digital_asset_verification

## Installation
```Batchfile	
	sudo chmod -R 777 domain/certs
	sudo chmod 777 domain/*.sh
```

# Setup

## Github
### [Generate API token](https://help.github.com/articles/creating-an-access-token-for-command-line-use/)
* Create an account on Github
* In the top right corner of any page, click your profile photo, then click **Settings**.
* In the user settings sidebar, click **Personal access tokens**.
* Click **Generate new token** and name it.
* Use the default scope, this is enough to read gists which is all we need.
* Copy the token to your clipboard and save it to the file  `social/secrets.php` in the following format:
```PHP
# social/secrets.php
<?php
	define('GITHUB_PERSONAL_TOKEN', '*****************');
?>

```
* This token is [limited](https://developer.github.com/v3/#rate-limiting) to 5000 calls/hour. 

## Facebook
### Generate API token
* Creat an account and sign in
* Navigate to https://developers.facebook.com/
* Click on the `My Apps` tab
* Register as a developer
* Click again on the `My Apps` tab
* Create a new application
* Select the `www` option and click on the top left `skip and create App ID`
 * Display Name: coloredcoins
 * Namespace: <leave blank>
 * Category: Finance
* Answer the kaptcha and create app id
* Navigate to the `Tools & Support` tab and select `Access Token Tools`
* Save the app token in a file `social/secrets.php` in the following format:
```PHP
# social/secrets.php
<?php
	define('FACEBOOK_APP_TOKEN', '**************|************');
?>
```

## Twitter

### Generate API tokens

* Creat a Twitter account and sign in
* You must add your mobile phone to your Twitter profile before creating an application
* Navigate to https://apps.twitter.com
* Create a new application
 * Website: http://colu.co
 * Callback URL: <leave blank>
* Navigate to the "Keys and Access Tokens" tab
* Save the following secrets in a file `social/secrets.php` in the following format:
```PHP
# social/secrets.php
<?php
  define('TWITTER_CONSUMER_KEY','***********');
  define('TWITTER_CONSUMER_SECRET','*************');
  define('TWITTER_OAUTH_ACCESS_TOKEN','****************');
  define('TWITTER_OAUTH_ACCESS_TOKEN_SECRET','****************');
?>

```


# Usage
## Social
### Github
* Create a **PUBLIC** gist for asset verifications
* grab the gist_id from the url, e.g. `https://gist.github.com/username/1d325dd9d1a74133bec3`
* Add the following element under the asset metadata `verifications` key
```JSON
{
	"social":{	
		"github":{			
			"gist_id":"1d325dd9d1a74133bec3"
		}
	}
}	
``` 
* For each asset that you want to verify (e.g. asset ids `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP` and `LKUYHRCMbqUNgfNCGFnXv1AvB5Pv8Lkk2EjoF`) add a line to the gist like so:
```
...
Verifying issuance of colored coins asset with ID #U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP
Verifying issuance of colored coins asset with ID #LKUYHRCMbqUNgfNCGFnXv1AvB5Pv8Lkk2EjoF
...

```

### Facebook
* Log in to your facebook account
* On the top right corner expand the menu and click on "Create Page"

![Creating a user page on Facebook](/fixtures/images/fb_create_user_page.png?raw=true "Creating a user page on Facebook")

* Select the appropriate category 
* Considering that this page should be dedicated to posting asset endorsment messages, you should probably name it properly and consider attaching an image and an appropriate description
* Now that you have created the page, click on the "Settings" button

![Finding the Facebook page settings button](/fixtures/images/fb_page_settings_button.png?raw=true "Finding the Facebook page settings button")

* On the setting page, disable visitor posts

![Disabling visitor posts on a Facebook page](/fixtures/images/fb_disable_visitors_posts.png?raw=true "Disabling visitor posts on a Facebook page")

* Grab the page ID from the url.
The URL looks something like:
<pre>
	https://www.facebook.com/Foobarbuzzquaxx-<b>705379359593101</b>/	
</pre>

The page ID is the number at the end of the URL, in the above case it is `705379359593101`

![Grab Facebook page id from URL](/fixtures/images/grabbing_fb_page_id.png?raw=true "Grab Facebook page id from URL")

* Add the following element under the asset metadata `verifications` key, for example
```JSON
{	
	"social":{		
		"facebook":{
			"page_id": 705379359593101					
		}
	}
}
```

* After the asset is issued, grab the asset id (say it is `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP`) and **post on that page** the following text:
```Text
	Verifying issuance of colored coins asset with ID #U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP
```

### Twitter

* You need the twitter handle of your twitter account **@your_twitter_handle**
* Add the following element under the asset metadata `verifications` key
```JSON
{	
	"social":{		
		"twitter":{			
			"username":"your_twitter_handle"
		},
	}
}
```

* After the asset is issued, grab the asset id (say it is `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP`) and tweet the following text, making sure that the asset ID is used as a **hashtag**:

```Text
Verifying issuance of colored coins asset with ID #U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP
```

## Domain

* Place a text file on your server behind https
* Let's say the path to the file is `https://www.yourcompany.com/assets.txt`
* Add the following element under the asset metadata `verifications` key
```JSON
{	
	"domain":{
		"url":"https://www.yourcompany.com",
		"path":"assets.txt"
	}
}
```
* If The file was in a directory `https://www.yourcompany.com/assets/assets.txt` you should use 
```JSON
{	
	"domain":{
		"url":"https://www.yourcompany.com/path/to/file/filename"		
	}
}
```
For example:

```JSON
{	
	"domain":{
		"url":"https://www.example.com/digital_assets/assets.txt"		
	}
}
```

* For each asset that you want to verify (e.g. asset ids `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP` and `LKUYHRCMbqUNgfNCGFnXv1AvB5Pv8Lkk2EjoF`) add a line to the text file
```
...
Verifying issuance of colored coins asset with ID #U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP
Verifying issuance of colored coins asset with ID #LKUYHRCMbqUNgfNCGFnXv1AvB5Pv8Lkk2EjoF
...

```

You can check that the results of this check match what you get from [ssl_checker](https://www.sslshopper.com/ssl-checker.html), or [digicert](https://www.digicert.com/help/)
For example, surprisingly enough "https://www.target.com" doesn't pass the verification, and indeed, we [get the same result](https://www.sslshopper.com/ssl-checker.html#hostname=https://www.target.com) from ssl checker.