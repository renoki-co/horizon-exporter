<?php

use Illuminate\Support\Facades\Route;
use RenokiCo\HorizonExporter\Http\Controllers\HorizonExporterController;

Route::get('/metrics', HorizonExporterController::class)->name('horizon-exporter.metrics');
