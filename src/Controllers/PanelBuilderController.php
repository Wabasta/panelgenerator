<?php
namespace Wabasta\PanelBuilder\Controllers;

use App\Http\Controllers\Controller;

class PanelBuilderController extends Controller
{
    /**
     * Show PanelBuilder dashboard page
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.dashboard');
    }
}
