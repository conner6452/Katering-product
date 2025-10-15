<?php

namespace App\Http\Handlers;

use App\Contracts\Interface\IngredientRepositoryInterface;
use App\Helpers\UploadHelper;
use App\Models\Ingredient;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Exception;

class IngredientHandler
{
    public function __construct(
        protected IngredientRepositoryInterface $repo,
    ) {}

    public function create(array $data): Ingredient
    {
        if (isset($data['image']) && $data['image']) {
            $imagePath = UploadHelper::uploadImage($data['image'], 'ingredient');
            $data['image'] = $imagePath;
        }

        $user = $this->repo->store($data);


        return $user;
    }

    public function update(string $id, array $data): Ingredient
    {
       DB::beginTransaction();
        try {
            $ingredient = $this->repo->findById($id);

            if (isset($data['image']) && $data['image']) {
                if ($ingredient->image) {
                    UploadHelper::deleteFile($ingredient->image);
                }
                $imagePath = UploadHelper::uploadImage($data['image'], 'ingredient');
                $data['image'] = $imagePath;
            }

            $updatedingredient = $this->repo->update($id, $data);

            DB::commit();
            return $updatedingredient;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
}
