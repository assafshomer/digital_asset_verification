# digital_asset_verification

## Installation
```Batchfile	
	sudo chmod -R 777 domain/certs
	sudo chmod 777 social/twitter_bearer_token.txt
	sudo chmod 777 domain/*.sh
```

in prod, go to `api.php` and replace
```Batchfile	
	$json_file_name = $_GET['name'];
	$json = file_get_contents('../verify/test/fixtures/'.$json_file_name.'.json');
```

for 

```Batchfile	
	$json = $_POST["json"];
```
