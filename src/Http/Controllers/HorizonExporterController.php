<?php


namespace RenokiCo\HorizonExporter\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Prometheus\RenderTextFormat;
use RenokiCo\HorizonExporter\HorizonExporter;

class HorizonExporterController extends Controller
{
    /**
     * Display the Prometheus metrics.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        return response(
            HorizonExporter::exportAsPlainText(),
            200,
            ['Content-Type' => RenderTextFormat::MIME_TYPE],
        );
    }
}
