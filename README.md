# digital_asset_verification

## Installation
```Batchfile	
	sudo chmod -R 777 domain/certs
	sudo chmod 777 social/twitter_bearer_token.txt
	sudo chmod 777 domain/*.sh
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
* After the asset is issued, grab the asset id (say it is `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP`) and add it to the gist like so:
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

* You need the twitter handle of your twitter account *@your_twitter_handle*
* Add the following element under the asset metadata `verifications` key
```JSON
{	
	"social":{		
		"twitter":{			
			"username":"your_twitter_handle"
		},
	}
}
* After the asset is issued, grab the asset id (say it is `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP`) and tweet the following text, making sure that the asset ID is used as a *hashtag*:
```Text
	Verifying issuance of colored coins asset with ID #U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP
```