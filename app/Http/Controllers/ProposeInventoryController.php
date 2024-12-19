<?php

namespace App\Http\Controllers;

use App\Models\ProposedRequest;
use App\Services\ProposeRequestService;
use Illuminate\Http\Request;

class ProposeInventoryController extends Controller
{
    protected $proposeRequestService;

    public function __construct(ProposeRequestService $proposeRequestService)
    {
        $this->proposeRequestService = $proposeRequestService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Propose Inventory List';
        $proposes = $this->proposeRequestService->getAllProposeRequestPending();
        return view('pages.propose_inventory.index', compact('title', 'proposes'));
    }
    public function search(Request $request)
    {

        $sanitize = handleSanitize(request()->input('search', ''));
        if ($sanitize) {
            $title = 'Propose Inventory List';
            $proposes = $this->proposeRequestService->searchProposeRequest($sanitize);
            switch ($proposes->status) {
                case 'pending':
                    return view('pages.propose_inventory.index', compact('title', 'proposes'));
                    break;
                case 'approved':
                    return view('pages.propose_inventory.approved', compact('title', 'proposes'));
                    break;
                case 'resubmitted':
                    return view('pages.propose_inventory.resubmitted', compact('title', 'proposes'));
                    break;
                case 'rejected':
                    return view('pages.propose_inventory.rejected', compact('title', 'proposes'));
                    break;
                default:
                    return view('pages.propose_inventory.index', compact('title', 'proposes'));
                    break;
            }
        }
    }

    public function approved()
    {
        $title = 'Approved Propose Inventory List';
        $proposes = $this->proposeRequestService->getAllProposeRequestApproved();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(ProposedRequest $proposedRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProposedRequest $proposedRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProposedRequest $proposedRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProposedRequest $proposedRequest)
    {
        //
    }
}
