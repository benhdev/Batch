# Batch Project

### Usage
1. `config.php` needs to be created in the root of this project `/`
2. `config.php` should look like the following;

```php
<?php

class Config {
	private const FEDEX_API = "https://discordapp.com/api/webhooks/621402327286415370/sfxlIKB_0RFr24RhzExKoE6nnqA-DC3ifcq9OcwQWm8l2cz6LlvOJR6Oxi_OLFNUNM6K";

	public static function getFedexApi() {
		return self::FEDEX_API;
	}
}

?>
```