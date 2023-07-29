<?php

use App\Services\Filerepo\Models\ModelInstance;

if (!function_exists('getModelDetails')) {
    function getModelDetails($record)
    {
        $model_instance_id = null;
        $model_id = null;
        if ($record) {
            $model = get_class($record);
            $model_id = $record->id;
            $arr = ['name' => $model];
            $model_instance_id = ModelInstance::updateOrCreate($arr, $arr)->id;
        }

        return [0 => $model_instance_id, 1 => $model_id];
    }
}


if (!function_exists('previewFileIconSettings')) {
    function previewFileIconSettings()
    {
        return [];
    }
}
