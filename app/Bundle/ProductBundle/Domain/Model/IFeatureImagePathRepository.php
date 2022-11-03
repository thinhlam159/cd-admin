<?php
namespace App\Bundle\ProductBundle\Domain\Model;

use App\Bundle\Common\Domain\Model\Pagination;

interface IFeatureImagePathRepository
{
    /**
     * @param FeatureImagePath $featureImagePath
     * @return FeatureImagePathId
     */
    public function create(FeatureImagePath $featureImagePath): FeatureImagePathId;
}
