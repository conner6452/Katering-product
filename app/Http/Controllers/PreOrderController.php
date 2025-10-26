<?php

namespace App\Http\Controllers;

use App\Contracts\Interface\PreOrderInterface;
use App\Helpers\PaginateHelper;
use App\Helpers\ResponseHelper;
use App\Http\Handlers\PreOrderHandler;
use App\Http\Requests\PreOrder\StorePreOrderRequest;
use App\Http\Requests\PreOrder\UpdatePreOrderRequest;
use App\Http\Resources\NotificationResource;
use App\Http\Resources\PreOrder\PreOrderResource;
use App\Http\Resources\PreOrder\PreOrderResourcePaginate;
use App\Models\PreOrder;
use App\Notifications\PreOrderStatusNotification;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class PreOrderController extends Controller
{
    protected $preOrderInterface;
    protected $preOrderHandler;
    public function __construct(PreOrderInterface $preOrderInterface, PreOrderHandler $preOrderHandler)
    {
        $this->preOrderInterface = $preOrderInterface;
        $this->preOrderHandler = $preOrderHandler;
    }

    /**
     * List pre orders (with filters & pagination)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->only([
                'per_page',
                'page',
                'search',
                'supplier.name',
                'preOrderLists.ingredient.name',
                'total_price',
                'sort_by',
                'sort_direction',
                'created_from',
                'created_to',
                'month',
                'year',
            ]);

            $perPage = (int) ($params['per_page'] ?? 15);

            $query = $this->preOrderInterface->getWithFilters($params);
            $result = $query->paginate($perPage);

            $paginate = PaginateHelper::getPaginate($result);
            $resourceCollection = new PreOrderResourcePaginate($result, $paginate);

            return ResponseHelper::success(
                $resourceCollection,
                __('alert.data_found'),
                Response::HTTP_OK,
                true
            );
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }


    public function show(string $id): JsonResponse
    {
        try {
            $rule = $this->preOrderInterface->findById($id);
            return ResponseHelper::success(new PreOrderResource($rule), __('alert.data_found'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }


    public function store(StorePreOrderRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $rule = $this->preOrderHandler->store($data);

            return ResponseHelper::success(
                new PreOrderResource($rule),
                __('alert.add_success'),
                Response::HTTP_CREATED
            );
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.add_failed'), $e->getMessage(), 400);
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->preOrderInterface->delete($id);
            return ResponseHelper::success(null, __('alert.delete_success'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.delete_failed'), $e->getMessage(), 400);
        }
    }
    public function updateStatus(UpdatePreOrderRequest $request, $id)
    {
        $data = $request->validated();

        try {
            $po = $this->preOrderInterface->findById($id);

            $po->update(['status' => $data['status']]);

            if ($po->supplier?->user) {
                $po->supplier->user->notify(new PreOrderStatusNotification($po));
            }

            return ResponseHelper::success(
                new PreOrderResource($po),
                __('alert.update_success')
            );

        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);

        } catch (Throwable $e) {
            \Log::error($e);
            return ResponseHelper::error(__('alert.update_failed'), 400);
        }
    }

    public function setStatusDone(string $id): JsonResponse
    {
        try {
            $this->preOrderHandler->setStatusDone($id);
            return ResponseHelper::success(null, __('alert.set_status_done'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.set_status_failed'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.set_status_failed'), $e->getMessage(), 400);
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'search',
                'sort',
                'supplier.name',
                'preOrderLists.ingredient.name',
                'total_price',
                'created_from',
                'published_from',
                'per_page',
            ]);

            $perPage = (int) ($filters['per_page'] ?? 15);
            $articles = $this->preOrderInterface->trash($filters); // handler->trash should return paginator or collection
            $paginate = PaginateHelper::getPaginate($articles);
            $resourceCollection = new PreOrderResourcePaginate($articles, $paginate);

            return ResponseHelper::success($resourceCollection, __('alert.data_found'));
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $this->preOrderInterface->restore($id);
            return ResponseHelper::success(null, __('alert.user_restore_success'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.user_restore_failed'), $e->getMessage(), 400);
        }
    }


    public function forceDelete(string $id): JsonResponse
    {
        try {
            $this->preOrderInterface->forceDelete($id);
            return ResponseHelper::success(null, __('alert.delete_success'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.delete_failed'), $e->getMessage(), 400);
        }
    }
}
