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
 * Product order
*/
interface ProductInterface 
{
    /**
     * Create product
     *
     * @param array $data
     * @return ProductInterface|null 
     */
    public function create(array $data);

    /**
     * Create product
     *
     * @param array $data
     * @return ProductInterface|null
     */
    public static function createFromArray(array $data);
    
    /**
    * Get id
    *    
    * @return string 
    */
    public function getId(): string;

    /**
     * Set id
     * 
     * @param string|null $id
     * @return void
     */
    public function setId(?string $id): void;

    /**
    * Get title
    *    
    * @return string 
    */
    public function getTitle(): string;
    
    /**
    * Get description
    *    
    * @return string|null 
    */
    public function getDescription(): ?string;

    /**
    * Get price
    *    
    * @return float 
    */
    public function getPrice(): float;

    /**
    * Get curency code
    *    
    * @return string 
    */
    public function getCurrency(): string;

    /**
    * Get product options
    *    
    * @return array 
    */
    public function getOptions(): array;

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array;
}
