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

use Arikaim\Extensions\Products\Classes\ExternalProductInterface;

/**
 * External product factory
*/
class ExternalProductFactory 
{
    const ENVATO_API_DRIVER = 'envato';
  
    /**
     * Create external product service
     *
     * @param string $driverName
     * @return ExternalProductInterface|null
     */
    public static function create(string $driverName)
    {       
        switch($driverName) {            
            case Self::ENVATO_API_DRIVER:              
                return new \Arikaim\Extensions\Products\Classes\Envato\ExternalProduct();             
        }

        return null;
    }
}
