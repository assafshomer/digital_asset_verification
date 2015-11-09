# digital_asset_verification

## Installation
```Batchfile	
	sudo chmod -R 777 domain/certs
	sudo chmod 777 social/twitter_bearer_token.txt
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
* Search for the "Colored Coins Asset Verification" page
* Post something on the "Colored Coins Asset Verification" page, preferably relevant to your asset, but it is not important. However, remember that customers may be looking at this post.
* Find your post on the page and **make a comment** with the following text 
```Text
	Verifying issuance of colored coins asset with ID #assetid
```
* Right click on the comment timestamp

![Extracting facebook ids from the comment timestamp](/fixtures/images/fb_comment_timestamp.png?raw=true "Extracting facebook ids from the comment timestamp")

* Copy the link location, it should look something like this
<pre>
	https://www.facebook.com/permalink.php?<b>story_fbid=493393697504710&id=486034634907283&comment_id=493393800838033</b>&offset=0&total_comments=1&comment_tracking=%7B%22tn%22%3A%22R%22%7D 
</pre>

* Add the following element under the asset metadata `verifications` key
```JSON
{	
	"social":{		
		"facebook":{
			"story_fbid":493393697504710,
			"id":486034634907283,
			"comment_id":493393800838033						
		}
	}
}
```
* After the asset is issued, grab the asset id (say it is `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP`) and **edit the same comment** to include the asset id, like so:
```Text
	Verifying issuance of colored coins asset with ID #U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP
```

![Adding your asset id into the comment](/fixtures/images/fb_edit_comment.png?raw=true "Adding your asset id into the comment")

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
		"url":"https://www.yourcompany.com",
		"path":"assets/assets.txt"
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