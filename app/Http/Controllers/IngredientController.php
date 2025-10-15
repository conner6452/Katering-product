<?php

namespace App\Http\Controllers;

use App\Contracts\Interface\IngredientRepositoryInterface;
use App\Helpers\PaginateHelper;
use App\Helpers\ResponseHelper;

use App\Http\Handlers\IngredientHandler;
use App\Http\Requests\Ingredient\StoreIngredientRequest;
use App\Http\Requests\Ingredient\UpdateIngredientRequest;
use App\Http\Resources\Ingredient\IngredientResource;
use App\Http\Resources\Ingredient\IngredientResourcePaginate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class IngredientController extends Controller
{
    public function __construct(
        protected IngredientRepositoryInterface $repo,
        protected IngredientHandler $handler
    ) {}

    /**
     * List ingredients (with filters & pagination)
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $params = $request->only([
                'per_page', 'page', 'search', 'detail', 'stock_min', 'stock_max',
                'sort_by', 'sort_dir', 'created_from', 'created_to', 'month', 'year',
            ]);

            $perPage = (int) ($params['per_page'] ?? 10);

            $query = $this->repo->getWithFilters($params);
            $result = $query->paginate($perPage);

            $paginate = PaginateHelper::getPaginate($result);
            $resourceCollection = new IngredientResourcePaginate($result, $paginate);

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
            return ResponseHelper::success(new IngredientResource($ingredient), __('alert.data_found'));
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
            return ResponseHelper::success(new IngredientResource($ingredient), __('alert.data_found'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }


    public function store(StoreIngredientRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $ingredient = $this->handler->create($data);

            return ResponseHelper::success(
                new IngredientResource($ingredient),
                __('alert.add_success'),
                Response::HTTP_CREATED
            );
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.add_failed'), $e->getMessage(), 400);
        }
    }


    public function update(UpdateIngredientRequest $request, string $id): JsonResponse
    {
        try {
            $data = $request->validated();

            $ingredient = $this->handler->update($id, $data);

            return ResponseHelper::success(
                new IngredientResource($ingredient),
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
                'search', 'sort', 'detail', 'created_from', 'published_from', 'per_page', 'stock_min', 'stock_max'
            ]);

            $perPage = (int) ($filters['per_page'] ?? 10);
            $articles = $this->repo->trash($filters); // handler->trash should return paginator or collection
            $paginate = PaginateHelper::getPaginate($articles);
            $resourceCollection = new IngredientResourcePaginate($articles, $paginate);

            return ResponseHelper::success($resourceCollection, __('alert.data_found'));
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }
}
