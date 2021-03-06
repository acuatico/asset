<?php

namespace App\Service;


class ScadaAsset extends AssetHandler
{
    public $formTemplate = 'assets.asset-type-forms.m-e';
    public $editFormTemplate = 'assets.asset-type-forms.m-e-edit';
    public $detailTemplate = 'assets.asset-type-details.m-e';
    public $detailRelation = 'mechanical';

    /**
     * MechanicalAsset constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    function store(array $inputs)
    {
        $asset = $this->storeAsset($inputs);
        $assetParams = array_only(
            $inputs,
            [
                'specification', 'serial_number', 'install_date', 'function', 'asset_performance_id', 'asset_condition_id',
                'performance_detail', 'condition_detail', 'brand'
            ]
        );

        $params = [];
        if ($assetParams['specification'] != '') {
            $params['specification'] = $assetParams['specification'];
        }       
        if ($assetParams['serial_number'] != '') {
            $params['serial_number'] = $assetParams['serial_number'];
        }
        if ($assetParams['install_date'] != '') {
            $params['install_date'] = $assetParams['install_date'];
        }
        if ($assetParams['function'] != '') {
            $params['function'] = $assetParams['function'];
        }
        if ($assetParams['asset_performance_id'] != '') {
            $params['asset_performance_id'] = $assetParams['asset_performance_id'];
        }
        if ($assetParams['asset_condition_id'] != '') {
            $params['asset_condition_id'] = $assetParams['asset_condition_id'];
        }
        if ($assetParams['performance_detail'] != '') {
            $params['performance_detail'] = $assetParams['performance_detail'];
        }
        if ($assetParams['condition_detail'] != '') {
            $params['condition_detail'] = $assetParams['condition_detail'];
        }
         // Add by Erik, 5 Mei 2017
        if ($assetParams['brand'] != '') {
            $params['brand'] = $assetParams['brand'];
        }
        // End Add
        return $asset->mechanical()->create($assetParams);
    }

    function update($id, array $inputs)
    {
        $asset = $this->updateAsset($id, $inputs);

        $assetDetail = $asset->mechanical;
        $assetDetail->specification = $inputs['specification'];       
        $assetDetail->serial_number = $inputs['serial_number'];
        $assetDetail->install_date = $inputs['install_date'];
        $assetDetail->function = $inputs['function'];
        $assetDetail->asset_performance_id = $inputs['asset_performance_id'];
        $assetDetail->asset_condition_id = $inputs['asset_condition_id'];
        $assetDetail->performance_detail = $inputs['performance_detail'];
        $assetDetail->condition_detail = $inputs['condition_detail'];
         // Add by Erik, 5 Mei 2017
        $assetDetail->brand = $inputs['brand'];
        // End Add

        return $assetDetail->save();
    }
}