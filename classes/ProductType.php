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

use Arikaim\Core\Extension\Extension;
use Arikaim\Core\Db\Traits\Options\OptionType;
use Arikaim\Core\Utils\Uuid;
use Arikaim\Core\Db\Seed;

/**
 * Product type options  
*/
class ProductType
{
    /**
     * Product type
     *
     * @param string $configFile
     * @param string $extensionName
     * @return boolean
     */
    public static function importTypes(string $configFile, string $extensionName): bool
    {
        // Products types
        $items = Extension::loadJsonConfigFile($configFile,$extensionName);   
        if (\is_array($items) == false) {
            return false;
        }

        Seed::withModel('ProductType','products',function($seed) use($items) {     
            $seed->updateOrCreateFromArray(['slug'],$items,function($item) {
                $item['uuid'] = Uuid::create();        
                return $item;
            });
        });

        return true;
    }

    /**
     * Import product type
     *
     * @param string $configFile
     * @param string $extensionName
     * @return boolean
     */
    public static function import(string $configFile, string $extensionName): bool
    {
        // Product type options
        $items = Extension::loadJsonConfigFile($configFile,$extensionName);   
        if (\is_array($items) == false) {
            return false;
        }

        Seed::withModel('ProductOptionsList','products',function($seed) use($items) {     
            $seed->updateOrCreateFromArray(['key','type_name'],$items,function($item) {
                $item['uuid'] = Uuid::create();        
                return $item;
            });
        });

        return true;
    } 

    /**
     * Import options type
     *
     * @param string $configFile
     * @param string $extensionName
     * @return boolean
     */
    public static function importOptionsType(string $configFile, string $extensionName): bool
    {
        $items = Extension::loadJsonConfigFile($configFile,$extensionName);
        if (\is_array($items) == false) {
            return false;
        }

        Seed::withModel('ProductOptionType','products',function($seed) use($items) {
            $seed->updateOrCreateFromArray(['key'],$items,function($item) {
                $item['uuid'] = Uuid::create();
                $item['type'] = OptionType::getOptionTypeId($item['type']);
                $items = $item['items'] ?? null;
                $item['items'] = (empty($items) == false) ? \json_encode($items) : null;

                return $item;
            });
        });

        return true;
    }
}
