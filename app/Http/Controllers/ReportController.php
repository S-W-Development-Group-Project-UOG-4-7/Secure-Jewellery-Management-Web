<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Excel as ExcelWriter;
use App\Exports\ArrayExport;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller
{
    public function __construct(private ReportService $reports) {}

    /**
     * Display the report view with filter options
     */
    public function index(Request $request)
    {
        $from = $request->get('from', now()->subDays(7)->toDateString());
        $to   = $request->get('to', now()->toDateString());
        $type = $request->get('type', 'deliveries');

        $data = $this->reports->getReport($type, $from, $to);

        return view('reports.index', compact('from', 'to', 'type', 'data'));
    }

    /**
     * Export the report as a PDF file
     */
    public function exportPdf(Request $request): Response
    {
        $from = $request->get('from', now()->subDays(7)->toDateString());
        $to   = $request->get('to', now()->toDateString());
        $type = $request->get('type', 'deliveries');

        $data = $this->reports->getReport($type, $from, $to);

        $pdf = Pdf::loadView('reports.pdf', compact('from', 'to', 'type', 'data'));

        return $pdf->download("report_{$type}_{$from}_{$to}.pdf");
    }

    /**
     * Export the report as a CSV file
     */
    public function exportCsv(Request $request)
    {
        $from = $request->get('from', now()->subDays(7)->toDateString());
        $to   = $request->get('to', now()->toDateString());
        $type = $request->get('type', 'deliveries');

        $data = $this->reports->getReport($type, $from, $to);

        $rows = $data['table'] ?? [];

        // Ensure it's a clean array (not collection/objects)
        $arrayRows = collect($rows)->map(fn ($row) => (array) $row)->toArray();

        return Excel::download(
            new ArrayExport($arrayRows),
            "report_{$type}_{$from}_{$to}.csv",
            ExcelWriter::CSV
        );
    }
}
