<?php

namespace App\Http\Controllers;

use App\Contracts\Interface\NotificationInterface;
use App\Helpers\ResponseHelper;
use App\Http\Resources\NotificationResource;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationInterface $repo) {}


    public function index(){
        $notification = $this->repo->getAll(auth()->id());
          return ResponseHelper::success(
            NotificationResource::collection($notification),
            __('alert.data_found')
        );
    }
}
