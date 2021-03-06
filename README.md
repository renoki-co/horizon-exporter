Laravel Horizon Prometheus Exporter
===================================

![CI](https://github.com/renoki-co/horizon-exporter/workflows/CI/badge.svg?branch=master)
[![codecov](https://codecov.io/gh/renoki-co/horizon-exporter/branch/master/graph/badge.svg)](https://codecov.io/gh/renoki-co/horizon-exporter/branch/master)
[![StyleCI](https://github.styleci.io/repos/409155353/shield?branch=master)](https://github.styleci.io/repos/409155353)
[![Latest Stable Version](https://poser.pugx.org/renoki-co/horizon-exporter/v/stable)](https://packagist.org/packages/renoki-co/horizon-exporter)
[![Total Downloads](https://poser.pugx.org/renoki-co/horizon-exporter/downloads)](https://packagist.org/packages/renoki-co/horizon-exporter)
[![Monthly Downloads](https://poser.pugx.org/renoki-co/horizon-exporter/d/monthly)](https://packagist.org/packages/renoki-co/horizon-exporter)
[![License](https://poser.pugx.org/renoki-co/horizon-exporter/license)](https://packagist.org/packages/renoki-co/horizon-exporter)

Export Laravel Horizon metrics using this Prometheus exporter. This package leverages [Exporter Contracts](https://github.com/renoki-co/laravel-exporter-contracts).

## 🤝 Supporting

**If you are using one or more Renoki Co. open-source packages in your production apps, in presentation demos, hobby projects, school projects or so, sponsor our work with [Github Sponsors](https://github.com/sponsors/rennokki). 📦**

[<img src="https://github-content.s3.fr-par.scw.cloud/static/29.jpg" height="210" width="418" />](https://github-content.renoki.org/github-repo/29)

## 🚀 Installation

You can install the package via composer:

```bash
composer require renoki-co/horizon-exporter
```

Publish the config:

```bash
$ php artisan vendor:publish --provider="RenokiCo\HorizonExporter\HorizonExporterServiceProvider" --tag="config"
$ php artisan vendor:publish --provider="RenokiCo\LaravelExporter\LaravelExporterServiceProvider" --tag="config"
```

## 🙌 Usage

This package is pretty straightforward. Upon installing it, it will register the route at `/exporter/group/horizon-metrics` and you can point Prometheus towards it for scraping.

Please keep in mind that the metrics are not calculated by-process, but as a whole across all supervisors. Point your Prometheus scraper to one of the instances for horizontally-scaled environments.

```
# HELP laravel_horizon_master_status That status of the Master Horizon process. 0 = inactive, 1 = paused, 2 = running.
# TYPE laravel_horizon_master_status gauge
laravel_horizon_master_status{name="master-1",pid="10082"} 2

# HELP laravel_horizon_queue_runtime Get total jobs runtime by queue.
# TYPE laravel_horizon_queue_runtime gauge
laravel_horizon_queue_runtime{queue="default"} 41.085

# HELP laravel_horizon_queue_throughput Get total jobs throughput by queue.
# TYPE laravel_horizon_queue_throughput gauge
laravel_horizon_queue_throughput{queue="default"} 4

# HELP laravel_horizon_job_runtime Get total workload runtime by job name.
# TYPE laravel_horizon_job_runtime gauge
laravel_horizon_job_runtime{job="RenokiCo\\HorizonExporter\\Test\\Jobs\\BasicJob"} 53.666666666667
laravel_horizon_job_runtime{job="RenokiCo\\HorizonExporter\\Test\\Jobs\\BasicJob2"} 3.34

# HELP laravel_horizon_job_throughput Get total workload throughput by job name.
# TYPE laravel_horizon_job_throughput gauge
laravel_horizon_job_throughput{job="RenokiCo\\HorizonExporter\\Test\\Jobs\\BasicJob"} 3
laravel_horizon_job_throughput{job="RenokiCo\\HorizonExporter\\Test\\Jobs\\BasicJob2"} 1

# HELP laravel_horizon_jobs_by_type Get total processed jobs into all queues by specific type (i.e. completed, pending, etc.).
# TYPE laravel_horizon_jobs_by_type gauge
laravel_horizon_jobs_by_type{type="completed"} 4
laravel_horizon_jobs_by_type{type="failed"} 1
laravel_horizon_jobs_by_type{type="pending"} 1
laravel_horizon_jobs_by_type{type="recent"} 6
laravel_horizon_jobs_by_type{type="recent_failed"} 1

# HELP php_info Information about the PHP environment.
# TYPE php_info gauge
php_info{version="8.0.10"} 1
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
