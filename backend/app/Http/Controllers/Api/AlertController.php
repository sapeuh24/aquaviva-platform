<?php

namespace App\Http\Controllers\Api;

use App\Actions\Alerts\DismissAlertAction;
use App\Actions\Alerts\ListAlertsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\AlertResource;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class AlertController extends Controller
{
    public function index(Request $request, ListAlertsAction $action): AnonymousResourceCollection
    {
        $alerts = $action->execute(
            filters: $request->only(['status', 'type', 'indicator_id']),
            perPage: (int) $request->get('per_page', 15),
        );

        return AlertResource::collection($alerts);
    }

    public function show(Alert $alert): AlertResource
    {
        return new AlertResource($alert->load('indicator'));
    }

    public function dismiss(Alert $alert, DismissAlertAction $action): AlertResource
    {
        $updated = $action->execute($alert);

        return new AlertResource($updated);
    }
}
