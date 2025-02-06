<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Subscribers;

use Arikaim\Core\Events\EventSubscriber;
use Arikaim\Core\Interfaces\Events\EventSubscriberInterface;
use Arikaim\Core\Db\Model;
use Arikaim\Core\Routes\Route;

/**
 * Sitemap subscriber class
 */
class SitemapSubscriber extends EventSubscriber implements EventSubscriberInterface
{
    /**
     * Constructor
     *
     */
    public function __construct()
    {       
        $this->subscribe('sitemap.pages');
    }
    
    /**
     * Subscriber code executed.
     *
     * @param EventInterface $event
     * @return mixed
     */
    public function execute($event)
    {     
        $params = $event->getParameters();
            
        if ($params['name'] == 'productDetails') {            
            return $this->getProductsPages($params);       
        } 
     
        $url = Route::getRouteUrl($params['pattern']);

        return (empty($url) == false) ? [$url] : null;  
    }

   

    /**
     * Get product pages url
     *
     * @param array $route
     * @param string $language
     * @return array
     */
    public function getProductsPages($route)
    {
        $pages = [];
        $products = Model::Products('products')->getActive()->get();               
         
        foreach ($products as $item) {  
            // skip roduct types with sitemap include off
            if ($item->type->include_in_sitemap != 1) continue;

            $url = Route::getRouteUrl($route['pattern'],[
                'slug' => $item->slug
            ]);
            $pages[] = $url;
        }      
       
        return $pages;
    }
}
