# digital_asset_verification

## Installation
```Batchfile	
	sudo chmod -R 777 domain/certs
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
* For each asset that you want to verify (e.g. asset ids `U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP` and `LKUYHRCMbqUNgfNCGFnXv1AvB5Pv8Lkk2EjoF`) add a line to the gist like so:
```
...
Verifying issuance of colored coins asset with ID #U3uPyQeyNRafPy7popDfhZui8Hsw98B5XMUpP
Verifying issuance of colored coins asset with ID #LKUYHRCMbqUNgfNCGFnXv1AvB5Pv8Lkk2EjoF
...

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