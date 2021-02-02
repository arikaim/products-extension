<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Classes;

/**
 * External product order
*/
interface ExternalProductInterface 
{
    /**
     * Import product
     *
     * @param string $id
     * @param string $driverName
     * @return Model|null
     */
    public function import(string $id, string $driverName);

    /**
     * Get product list
     *
     * @param integer $page
     * @param string $driverName
     * @return array|null
     */
    public function getList(int $page = 1, string $driverName): ?array;

    /**
     * Get product details
     *
     * @param integer $id
     * @param string $driverName
     * @return array|null
     */
    public function getDetails(int $id, string $driverName): ?array;

    /**
     * Create product
     *
     * @param string $id
     * @param string $driverName
     * @return ProductInterface|null
     */
    public function create(string $id, string $driverName);
}
