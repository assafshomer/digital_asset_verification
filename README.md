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
