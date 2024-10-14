<?php

namespace App\Http\Traits;

use App\Exceptions\CustomException;
use Illuminate\Support\Facades\DB;
use Modules\Language\Models\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

trait crudTrait
{
    public function allActive($request, $model, $modelName, $translationName = null, $foreignKeyName = null)
    {
        $language_id = null;
        if ($request->has("language_id")) {
            $language_id = (int) $request->query('language_id');
        } else if ($request->has("iso_code")) {
            $language = new Language();
            $iso_code = (string) $request->query('iso_code');
            $language_id = $language->language_id($iso_code);
        } else {
            $language_id = Language::where("is_default", 1)->first()->id;
        }

        $query = $model->select($modelName . '.*');

        if ($translationName !== null) {
            $query->addSelect($translationName . '.*')
                ->join($translationName, $modelName . '.id', '=', $translationName . '.' . $foreignKeyName)
                ->where($translationName . '.language_id', $language_id);
        }

        $query->where($modelName . '.status', 'active');

        if ($request->has("id")) {
            $id = (int) $request->query('id');
            $query->where($modelName . '.id', '=', $id);
        }

        if ($request->has('per_page')) {
            if (1 > (int)$request->query('page')) {
                throw new CustomException('page can not be less than 1');
            }
            $perPage = (int) $request->query('per_page');
            if ($perPage < 1) {
                throw new CustomException('per_page can not be less than 1');
            }
            $data = $query->paginate($perPage);
        } else {
            $data = $query->get();
        }

        if ($data->isEmpty()) {
            throw new CustomException('Not found items');
        }
        return $data;
    }
    public function all($request, $model, $modelName, $translationName, $foreignKeyName)
    {
        $language_id = null;
        if ($request->has("language_id")) {
            $language_id = (int) $request->query('language_id');
        } else if ($request->has("iso_code")) {
            $language = new Language();
            $iso_code = (string) $request->query('iso_code');
            $language_id = $language->language_id($iso_code);
        } else {
            $language_id = Language::where("is_default", 1)->first();
            $language_id = $language_id->id;
        }

        $query = $model->select($modelName . '.*');

        if ($translationName !== null) {
            $query->addSelect($translationName . '.*')
                ->join($translationName, $modelName . '.id', '=', $translationName . '.' . $foreignKeyName)
                ->where($translationName . '.language_id', $language_id);
        }
        if ($request->has("id")) {
            $id = (int) $request->query('id');
            $query->where($modelName . '.id', '=', $id);
        }


        if ($request->has('per_page')) {
            if (1 > (int)$request->query('page')) {
                throw new CustomException('page can not be less than 1');
            }
            $perPage = (int) $request->query('per_page');
            if ($perPage < 1) {
                throw new CustomException('per page can not be less than 1');
            }
            $data = $query->paginate($perPage);
        } else {

            $data = $query->get();
        }
        if ($data->isEmpty()) {
            throw new CustomException('Not found items');
        }
        return $data;
    }
    public function searchAllActive($request, $model, $modelName, $translationName = null, $foreignKeyName = null)
    {
        $language_id = null;
        if ($request->has("language_id")) {
            $language_id = (int) $request->query('language_id');
        } else if ($request->has("iso_code")) {
            $language = new Language();
            $iso_code = (string) $request->query('iso_code');
            $language_id = $language->language_id($iso_code);
        } else {
            $language_id = Language::where("is_default", 1)->first();
            $language_id = $language_id->id;
        }

        if ($request->has("name") && $request->query("name")) {
            $name = $request->query("name");
        } else {
            throw new CustomException("Name not found", 404);
        }

        $query = $model->select($modelName . '.*');

        if ($translationName !== null) {
            $query->addSelect($translationName . '.*')
                ->join($translationName, $modelName . '.id', '=', $translationName . '.' . $foreignKeyName)
                ->where($translationName . '.language_id', $language_id);
        }
        $query->where($modelName . '.status', 'active');

        if ($request->has("id")) {
            $id = (int) $request->query('id');
            $query->where($modelName . '.id', '=', $id);
        } else {

            $query->where(function ($query) use ($name, $translationName) {
                $query->Where($translationName . '.name', 'like', '%' . $name . '%');
            });
        }

        if ($request->has('per_page')) {
            if (1 > (int)$request->query('page')) {
                throw new CustomException('page can not be less than 1');
            }
            $perPage = (int) $request->query('per_page');
            if ($perPage < 1) {
                throw new CustomException('per_page can not be less than 1');
            }
            $data = $query->paginate($perPage);
        } else {
            $data = $query->get();
        }
        if ($data->isEmpty()) {
            throw new CustomException('Not found items');
        }
        return $data;
    }
    public function searchAll($request, $model, $modelName, $translationName, $foreignKeyName)
    {
        $language_id = null;
        if ($request->has("language_id")) {
            $language_id = (int) $request->query('language_id');
        } else if ($request->has("iso_code")) {
            $language = new Language();
            $iso_code = (string) $request->query('iso_code');
            $language_id = $language->language_id($iso_code);
        } else {
            $language_id = Language::where("is_default", 1)->first();
            $language_id = $language_id->id;
        }

        if ($request->has("name")) {
            $name = $request->query("name");
        } else {
            throw new CustomException("Name not found", 404);
        }
        $query = $model->select($modelName . '.*');

        if ($translationName !== null) {
            $query->addSelect($translationName . '.*')
                ->join($translationName, $modelName . '.id', '=', $translationName . '.' . $foreignKeyName)
                ->where($translationName . '.language_id', $language_id);
        }

        if ($request->has("id")) {
            $id = (int) $request->query('id');
            $query->where($modelName . '.id', '=', $id);
        } else {

            $query->where(function ($query) use ($name, $translationName) {
                $query->Where($translationName . '.name', 'like', '%' . $name . '%');
            });
        }
        if ($request->has('per_page')) {
            if (1 > (int)$request->query('page')) {
                throw new CustomException('page can not be less than 1');
            }
            $perPage = (int) $request->query('per_page');
            if ($perPage < 1) {
                throw new CustomException('per_page can not be less than 1');
            }
            $data = $query->paginate($perPage);
        } else {
            $data = $query->get();
        }

        if ($data->isEmpty()) {
            throw new CustomException('Not found items');
        }
        return $data;
    }

    public function store($request, $model, $translationModel, $modelName, $translationName, $foreignKeyName)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        $language = new Language();

        $languages = $language->all();

        $existingTranslation =  $translationModel->where('name', $request->name)
            ->first();
        $row_id = null;
        if (!$existingTranslation) {

            $NewRow = new $model([
                'status' => $request->status
            ]);
            $NewRow->save();
            $row_id = $NewRow->id;
        } else {
            throw new CustomException("name is exist");
        }
        foreach ($languages as $language) {
            $existingTranslation =  $translationModel->where('language_id', $language->id)
                ->where('name', $request->name)
                ->first();
            if (!$existingTranslation) {
                $row_translation = new  $translationModel([
                    'language_id' => $language->id,
                    $foreignKeyName => $row_id,
                    'name' => $request->name
                ]);

                $row_translation->save();
            }
        }
        return true;
    }
    public function update($request, $model, $translationModel, $modelName, $translationName, $foreignKeyName)
    {

        $language_id = null;
        if ($request->has("language_id")) {
            $language_id = (int) $request->query('language_id');
        } else if ($request->has("iso_code")) {
            $language = new Language();
            $iso_code = (string) $request->query('iso_code');
            $language_id = $language->language_id($iso_code);
        } else {
            $language_id = Language::where("is_default", 1)->first();
            $language_id = $language_id->id;
        }

        if ($request->has("id")) {
            $row = $translationModel->where('language_id', $language_id)
                ->where($foreignKeyName, $request->id)
                ->first();
        } else {
            throw new CustomException($modelName . ' not found.');
        }


        if ($row) {
            if ($request->has('name')) {
                $row->name = $request->name;
            }
            if ($request->has('status')) {
                $model_id = $model->where('id', $row->$foreignKeyName)->first();
                $model_id->status = $request->status;
                $model_id->save();
            }
            $row->save();
        } else {
            throw new CustomException($modelName . ' not found.');
        }
        return $row;
    }

    public function delete($request, $model, $translationModel, $modelName, $translationName, $foreignKeyName)
    {
        $deleted = null;
        if ($request->has("id")) {
            $deleted = $model->where('id', $request->id)->first();
        } else {
            throw new CustomException($modelName . ' not found.');
        }

        if ($deleted) {
            $deleted->delete();
            return true;
        } else {
            throw new CustomException('not found ' . $modelName . ' to delete it');
        }
    }
}
