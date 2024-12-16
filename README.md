# Maintenance Bundle

## Install

This Symfony bundle shows a maintenance page if mode is enabled via environment variable.
The maintenance page can be bypassed via a configurable cookie or a configurable allowed IP address. This ensures, that the maintenance mode is only visible for the audience.

**Install this bundle via Composer:**

`composer require php-translation/symfony-bundle`

**Add configuration yaml file**
Then, configure the bundle. An example configuration looks like this:

```
maintenance:
  enabled: '%env(bool:MAINTENANCE_ENABLED)%'
  bypass_token: 'bypass'
  ip_addresses: ["127.0.0.1"]
```

**Add bundle to your bundles.php**

```
Larisch\MaintenanceBundle\MaintenanceBundle::class => ['all' => true],
```

**Clear cache**

Clear cache with following command.

```
bin/console cache:clear
```

**Bypass with cookie**

Create a cookie with name `maintenance_bypass` and add value of your `bypass_token`
