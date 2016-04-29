Opauth-Wunderlist
=============
[Opauth][1] strategy for Wunderlist authentication.

Implemented based on https://developer.wunderlist.com/documentation/concepts/authorization

Getting started
----------------
1. Install Opauth-Wunderlist:

   Using git:
   ```bash
   cd path_to_opauth/Strategy
   git clone https://github.com/t1mmen/opauth-wunderlist.git wunderlist
   ```

  Or, using [Composer](https://getcomposer.org/), just add this to your `composer.json`:

   ```bash
   {
       "require": {
           "t1mmen/opauth-wunderlist": "*"
       }
   }
   ```
   Then run `composer install`.


2. Create Wunderlist application at https://developer.wunderlist.com/apps/new

3. Configure Opauth-Wunderlist strategy with at least `Client ID` and `Client Secret`.

4. Direct user to `http://path_to_opauth/wunderlist` to authenticate

Strategy configuration
----------------------

Required parameters:

```php
<?php
'Wunderlist' => array(
	'client_id' => 'YOUR CLIENT ID',
	'client_secret' => 'YOUR CLIENT SECRET'
)
```

License
---------
Opauth-Wunderlist is MIT Licensed
Copyright © 2016 Timm Stokke (http://timm.stokke.me)

[1]: https://github.com/opauth/opauth
