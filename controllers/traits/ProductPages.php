<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Controllers\Traits;

use Arikaim\Core\Db\Model;

/**
 * Product pages controler trait
*/
trait ProductPages 
{
    /**
     * Product details page
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
     * @return mixed
    */
    public function productDetailsPage($request, $response, $data)
    {
        $language = $this->getPageLanguage($data) ?? $this->get('page')->getLanguage();
        
        $product = Model::Products('products')->findBySlug($data['slug']);
        if ($product == null) {
            return $this->pageLoad($request,$response,$data,'current>store.products.not-found');          
        }

        if ($product->status != 1) {
            return $this->pageLoad($request,$response,$data,'current>store.products.not-found');          
        }

        $data['categories'] = $product->getCategoriesList();
        $metaTags = $product->getMetaTags($language);      
        $description = $product->getOptionValue('description','');

        // schema def
        $this->withService('schema',function($service) use($product,$request,$data) {
            $service->graph()
                ->product()
                    ->name($product->display_title)
                    ->category($data['categories'][0])
                    ->if(($product->hasImage() == true),function($schema) use($product) {
                        $schema->image($product->image->src);
                    })
                    ->url($this->getUrl($request))        
                    ->sku($product->slug)     
                    ->description($product->description);

            $service->addToPageHead();
        });

        $this->get('page')->head()  
            ->setMetaTags($metaTags)     
            ->applyDefault('title',['title' => $product->title])
            ->applyDefault('description',['description' => $description])
            ->applyDefaultKeywors($product->title,$data['categories'],$description)                               
            ->applyTwitterProperty('title',$product->title)   
            ->applyTwitterProperty('description',$description)   
            ->applyOgProperty('title',$product->title)   
            ->applyOgProperty('description',$description)                
            ->ogUrl($this->getUrl($request))
            ->ogType('product') 
            ->twitterSite($this->getUrl($request));    
            
        $data['product'] = $product;

        return $this->pageLoad($request,$response,$data,'current>store.products.details'); 
    }
}
