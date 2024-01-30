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
 * Products list trait
*/
trait Options 
{
    /**
     * Update product options
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
    */
    public function updateOptions($request, $response, $data) 
    {         
        $data
            ->validate(true);

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id');
            return false;
        }
        
        // check access
        $this->requireUserOrControlPanel($product->user_id);
        
        $data->offsetUnset('uuid');
        $options = Model::ProductOptions('products');

        foreach ($data->toArray() as $key => $value) {
            $options->saveOption($product->id,$key,$value);
        }
         
        $this->setResponse(true,function() use($product) {                  
            $this
                ->message('options.update')
                ->field('uuid',$product->uuid);                  
        },'errors.options.update');                                    
    }
}
