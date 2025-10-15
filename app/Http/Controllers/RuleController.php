<?php

namespace App\Http\Controllers;

use App\Contracts\Interface\RuleInterface;
use App\Helpers\PaginateHelper;
use App\Helpers\ResponseHelper;
use App\Http\Handlers\AuthHandler;
use App\Http\Requests\Rule\StoreRuleRequest;
use App\Http\Requests\Rule\UpdateRuleRequest;
use App\Http\Resources\Rule\RuleResource;
use App\Http\Resources\Rule\RuleResourcePaginate;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Throwable;

class RuleController extends Controller
{
    protected $ruleInterface;
    public function __construct(RuleInterface $ruleInterface)
    {
        $this->ruleInterface = $ruleInterface;
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
                'budget',
                'capacity',
                'sort_by',
                'sort_direction',
                'created_from',
                'created_to',
                'month',
                'year',
            ]);

            $perPage = (int) ($params['per_page'] ?? 15);

            $query = $this->ruleInterface->getWithFilters($params);
            $result = $query->paginate($perPage);

            $paginate = PaginateHelper::getPaginate($result);
            $resourceCollection = new RuleResourcePaginate($result, $paginate);

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
            $rule = $this->ruleInterface->findById($id);
            return ResponseHelper::success(new RuleResource($rule), __('alert.data_found'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }


    public function store(StoreRuleRequest $request): JsonResponse
    {
        try {
            $data = $request->validated();

            $rule = $this->ruleInterface->store($data);

            return ResponseHelper::success(
                new RuleResource($rule),
                __('alert.add_success'),
                Response::HTTP_CREATED
            );
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.add_failed'), $e->getMessage(), 400);
        }
    }


    public function update(UpdateRuleRequest $request, string $id): JsonResponse
    {
        try {
            $data = $request->validated();

            $ingredient = $this->ruleInterface->update($id, $data);

            return ResponseHelper::success(
                new RuleResource($ingredient),
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
            $this->ruleInterface->delete($id);
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
                'budget',
                'capacity',
                'created_from',
                'published_from',
                'per_page',
            ]);

            $perPage = (int) ($filters['per_page'] ?? 15);
            $articles = $this->ruleInterface->trash($filters); // handler->trash should return paginator or collection
            $paginate = PaginateHelper::getPaginate($articles);
            $resourceCollection = new RuleResourcePaginate($articles, $paginate);

            return ResponseHelper::success($resourceCollection, __('alert.data_found'));
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.fetch_data_failed'), $e->getMessage(), 400);
        }
    }

    public function restore(string $id): JsonResponse
    {
        try {
            $this->ruleInterface->restore($id);
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
            $this->ruleInterface->forceDelete($id);
            return ResponseHelper::success(null, __('alert.delete_success'));
        } catch (ModelNotFoundException $e) {
            return ResponseHelper::error(__('alert.data_not_found'), 404);
        } catch (Throwable $e) {
            return ResponseHelper::error(__('alert.delete_failed'), $e->getMessage(), 400);
        }
    }
}
