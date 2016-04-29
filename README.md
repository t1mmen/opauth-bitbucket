Opauth-Bitbucket
=============
[Opauth][1] strategy for Bitbucket authentication.

Implemented based on https://confluence.atlassian.com/bitbucket/oauth-on-bitbucket-cloud-238027431.html

Getting started
----------------
1. Install Opauth-Bitbucket:

   Using git:
   ```bash
   cd path_to_opauth/Strategy
   git clone https://github.com/t1mmen/opauth-bitbucket.git bitbucket
   ```

  Or, using [Composer](https://getcomposer.org/), just add this to your `composer.json`:

   ```bash
   {
       "require": {
           "t1mmen/opauth-bitbucket": "*"
       }
   }
   ```
   Then run `composer install`.


2. Create Bitbucket application at https://bitbucket.org/account under "Oauth" in left menu. This implementation requires the "email" scope set.

3. Configure Opauth-Bitbucket strategy with at least `Client ID` and `Client Secret`.

4. Direct user to `http://path_to_opauth/bitbucket` to authenticate

Strategy configuration
----------------------

Required parameters:

```php
<?php
'Bitbucket' => array(
  'client_id' => 'YOUR CLIENT ID',
  'client_secret' => 'YOUR CLIENT SECRET'
)
```

License
---------
Opauth-Bitbucket is MIT Licensed
Copyright © 2016 Timm Stokke (http://timm.stokke.me)

[1]: https://github.com/opauth/opauth
