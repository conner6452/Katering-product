<?php

namespace App\Http\Controllers;

use App\Contracts\Interface\NotificationInterface;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Auth\FcmRequest;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct(protected NotificationInterface $repo) {}


    public function index(){
        try{
            $notification = $this->repo->getAll(auth()->id());
            return ResponseHelper::success(
                NotificationResource::collection($notification),
                __('alert.data_found')
            );
        }
    }
    public function fcmToken(FcmRequest $request)
    {
        try{
            $request->validated();
            $user = Auth::user();

            $user->update(['fcm_token' => $request->fcm_token]);
            return ResponseHelper::success(
                    __('alert.add_success'),
                    Response::HTTP_CREATED
                );
            } catch (\Throwable $e) {
                return ResponseHelper::error(__('alert.add_failed'), $e->getMessage(), 400);
            }

    }
}
