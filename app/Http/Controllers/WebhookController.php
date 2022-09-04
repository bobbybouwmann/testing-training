<?php

namespace App\Http\Controllers;

use App\Enums\WebhookType;
use App\Events\CreateNewUserRequested;
use App\Events\OrderCancelled;
use App\Events\OrderPaid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        if ($request->missing('token') || config('app.token') !== $request->get('token')) {
            return response('Invalid token', Response::HTTP_UNAUTHORIZED);
        }

        $type = $request->get('type');

        if (! in_array($type, WebhookType::values())) {
            return response('Invalid type', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        match ($type) {
            WebhookType::ORDER_PAID => event(new OrderPaid($request->all())),
            WebhookType::ORDER_CANCELLED => event(new OrderCancelled($request->all())),
            WebhookType::CREATE_CUSTOMER => event(new CreateNewUserRequested($request->all())),
            default => null,
        };

        return response('', Response::HTTP_OK);
    }
}
