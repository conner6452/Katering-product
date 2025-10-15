<?php

namespace App\Http\Controllers;

use App\Contracts\Interface\TransactionInterface;
use App\Helpers\PaginateHelper;
use App\Helpers\ResponseHelper;
use App\Http\Resources\Transaction\TransactionResource;
use App\Http\Resources\Transaction\TransactionResourcePaginate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class TransactionController extends Controller
{
    protected $transactionInterface;
    public function __construct(TransactionInterface $transactionInterface)
    {
        $this->transactionInterface = $transactionInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->only([
                'per_page',
                'page',
                'search',
                'user.name',
                'custom_id',
                'sort_by',
                'sort_direction',
                'created_from',
                'created_to',
                'month',
                'year',
            ]);

            $perPage = (int) ($params['per_page'] ?? 15);

            $query = $this->transactionInterface->getWithFilters($params);
            $result = $query->paginate($perPage);

            $paginate = PaginateHelper::getPaginate($result);
            $resourceCollection = new TransactionResourcePaginate($result, $paginate);

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
    public function show(string $id): JsonResponse
    {
        try {
            $rule = $this->transactionInterface->findById($id);
            return ResponseHelper::success(new TransactionResource($rule), __('alert.data_found'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->transactionInterface->delete($id);
            return ResponseHelper::success(null, __('alert.delete_success'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.delete_failed'), $e->getMessage(), 400);
        }
    }

    public function trash(Request $request): JsonResponse
    {
        try {
            $filters = $request->only([
                'search',
                'sort',
                'user.name',
                'custom_id',
                'created_from',
                'published_from',
                'per_page',
            ]);

            $perPage = (int) ($filters['per_page'] ?? 15);
            $articles = $this->transactionInterface->trash($filters); // handler->trash should return paginator or collection
            $paginate = PaginateHelper::getPaginate($articles);
            $resourceCollection = new TransactionResourcePaginate($articles, $paginate);

            return ResponseHelper::success($resourceCollection, __('alert.data_found'));
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $this->transactionInterface->restore($id);
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
            $this->transactionInterface->forceDelete($id);
            return ResponseHelper::success(null, __('alert.delete_success'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.delete_failed'), $e->getMessage(), 400);
        }
    }
}
