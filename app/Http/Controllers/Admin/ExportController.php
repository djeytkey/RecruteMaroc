<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Exports\UsersExport;
use App\Exports\CompaniesExport;
use App\Exports\JobOffersExport;
use App\Exports\ApplicationsExport;
use App\Exports\RewardsExport;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        return view('admin.exports.index');
    }

    public function users(): BinaryFileResponse
    {
        return (new UsersExport)->download('utilisateurs-' . date('Y-m-d') . '.xlsx');
    }

    public function companies(): BinaryFileResponse
    {
        return (new CompaniesExport)->download('entreprises-' . date('Y-m-d') . '.xlsx');
    }

    public function offers(): BinaryFileResponse
    {
        return (new JobOffersExport)->download('offres-' . date('Y-m-d') . '.xlsx');
    }

    public function applications(): BinaryFileResponse
    {
        return (new ApplicationsExport)->download('candidatures-' . date('Y-m-d') . '.xlsx');
    }

    public function rewards(): BinaryFileResponse
    {
        return (new RewardsExport)->download('recompenses-' . date('Y-m-d') . '.xlsx');
    }
}
