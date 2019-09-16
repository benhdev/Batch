# Batch Project

### Usage
- `config.php` needs to be created in the root of this project `/`
- `config.php` should look like the following;

![](https://cdn.discordapp.com/attachments/591985994971217921/623150831272984581/unknown.png)
```php
<?php

class Config {
	private const FEDEX_API = 'REPLACE_ME';

	public static function getFedexApi() {
		return self::FEDEX_API;
	}
}

?>
```
