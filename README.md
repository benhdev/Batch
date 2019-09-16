# Batch Project

### Usage
- `config.php` needs to be created in the root of this project `/`
- `config.php` should look like the following;

![](https://cdn.discordapp.com/attachments/591985994971217921/623150831272984581/unknown.png)
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
