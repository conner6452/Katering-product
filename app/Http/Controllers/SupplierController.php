<?php

namespace App\Http\Controllers;

use App\Contracts\Interface\SupplierInterface;
use App\Helpers\PaginateHelper;
use App\Helpers\ResponseHelper;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\Supplier\SupplierResource;
use App\Http\Resources\Supplier\SupplierResourcePaginate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class SupplierController extends Controller
{
    public function __construct(
        protected SupplierInterface $repo
    ) {}

    /**
     * List ingredients (with filters & pagination)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->only([
                'per_page', 'page', 'search', 'detail',
                'sort_by', 'sort_dir', 'created_from', 'created_to', 'month', 'year',
            ]);

            $perPage = (int) ($params['per_page'] ?? 10);

            $query = $this->repo->getWithFilters($params);
            $result = $query->paginate($perPage);

            $paginate = PaginateHelper::getPaginate($result);
            $resourceCollection = new SupplierResourcePaginate($result, $paginate);

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
            $ingredient = $this->repo->findById($id);
            return ResponseHelper::success(new SupplierResource($ingredient), __('alert.data_found'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }
    public function showBySlug(string $id): JsonResponse
    {
        try {
            $ingredient = $this->repo->findBySlug($id);
            return ResponseHelper::success(new SupplierResource($ingredient), __('alert.data_found'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }


    public function store(StoreSupplierRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $ingredient = $this->repo->store($data);

            return ResponseHelper::success(
                new SupplierResource($ingredient),
                __('alert.add_success'),
                Response::HTTP_CREATED
            );
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.add_failed'), $e->getMessage(), 400);
        }
    }


    public function update(UpdateSupplierRequest $request, string $id): JsonResponse
    {
        try {
            $data = $request->validated();

            $ingredient = $this->repo->update($id, $data);

            return ResponseHelper::success(
                new SupplierResource($ingredient),
                __('alert.update_success')
            );
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.update_failed'), $e->getMessage(), 400);
        }
    }


    public function destroy(string $id): JsonResponse
    {
        try {
            $this->repo->delete($id);
            return ResponseHelper::success(null, __('alert.delete_success'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.delete_failed'), $e->getMessage(), 400);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $this->repo->restore($id);
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
            $this->repo->forceDelete($id);
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
                'search', 'sort', 'detail', 'created_from', 'published_from', 'per_page'
            ]);

            $perPage = (int) ($filters['per_page'] ?? 10);
            $articles = $this->repo->trash($filters);
            $paginate = PaginateHelper::getPaginate($articles);
            $resourceCollection = new SupplierResourcePaginate($articles, $paginate);

            return ResponseHelper::success($resourceCollection, __('alert.data_found'));
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }
}
