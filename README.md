Package Name Here
===================================

![CI](https://github.com/renoki-co/horizon-exporter/workflows/CI/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/renoki-co/horizon-exporter/branch/master/graph/badge.svg)](https://codecov.io/gh/renoki-co/horizon-exporter/branch/master)
[![StyleCI](https://github.styleci.io/repos/:styleci_code/shield?branch=master)](https://github.styleci.io/repos/:styleci_code)
[![Latest Stable Version](https://poser.pugx.org/renoki-co/horizon-exporter/v/stable)](https://packagist.org/packages/renoki-co/horizon-exporter)
[![Total Downloads](https://poser.pugx.org/renoki-co/horizon-exporter/downloads)](https://packagist.org/packages/renoki-co/horizon-exporter)
[![Monthly Downloads](https://poser.pugx.org/renoki-co/horizon-exporter/d/monthly)](https://packagist.org/packages/renoki-co/horizon-exporter)
[![License](https://poser.pugx.org/renoki-co/horizon-exporter/license)](https://packagist.org/packages/renoki-co/horizon-exporter)

Export Laravel Horizon metrics using this Prometheus exporter.

## 🤝 Supporting

If you are using one or more Renoki Co. open-source packages in your production apps, in presentation demos, hobby projects, school projects or so, spread some kind words about our work or sponsor our work via Patreon. 📦

You will sometimes get exclusive content on tips about Laravel, AWS or Kubernetes on Patreon and some early-access to projects or packages.

[<img src="https://c5.patreon.com/external/logo/become_a_patron_button.png" height="41" width="175" />](https://www.patreon.com/bePatron?u=10965171)

## 🚀 Installation

You can install the package via composer:

```bash
composer require renoki-co/horizon-exporter
```

Publish the config:

```bash
$ php artisan vendor:publish --provider="RenokiCo\HorizonExporter\HorizonExporterServiceProvider" --tag="config"
```

## 🙌 Usage

```php
$ //
```

## 🐛 Testing

``` bash
vendor/bin/phpunit
```

## 🤝 Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## 🔒  Security

If you discover any security related issues, please email alex@renoki.org instead of using the issue tracker.

## 🎉 Credits

- [Alex Renoki](https://github.com/rennokki)
- [All Contributors](../../contributors)
