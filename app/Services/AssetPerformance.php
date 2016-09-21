<?php

namespace App\Service;


use App\Models\AssetPerformance as AssetPerformanceModel;

class AssetPerformance
{
    use DatatableParameters;

    protected $baseUrl = 'asset-performance';

    public function getPerformanceById($id)
    {
        return AssetPerformanceModel::find($id);
    }

    public function store(array $inputs)
    {
        return AssetPerformanceModel::create($inputs);
    }

    public function update($id, array $inputs)
    {
        $performance = AssetPerformanceModel::find($id);
        $performance->name = $inputs['name'];
        $performance->description = $inputs['description'];
        $performance->save();
    }

    public function destroy($id)
    {
        return AssetPerformanceModel::destroy($id);
    }

    public function datatableData()
    {
        $users = $this->getPerformances();
        $actions = $this->actionParameters(['edit','delete']);

        return (new DatatableGenerator($users))
            ->addActions($actions)
            ->generate();
    }

    private function getPerformances()
    {
        return AssetPerformanceModel::all();
    }
}