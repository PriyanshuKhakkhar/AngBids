<?php

namespace App\Services;

class CategorySpecificationService
{
    /**
     * Get specifications for a given category.
     * 
     * @param string|int $categoryIdentifier Slug or ID
     * @return array
     */
    public function getSpecifications($categoryIdentifier)
    {
        $allSpecs = config('auction_specs', []);
        
        if (isset($allSpecs[$categoryIdentifier])) {
            return $allSpecs[$categoryIdentifier]['fields'] ?? [];
        }

        return [];
    }

    /**
     * Get all categories that have specifications.
     *
     * @return array
     */
    public function getCategoriesWithSpecifications()
    {
        return array_keys(config('auction_specs', []));
    }
}
