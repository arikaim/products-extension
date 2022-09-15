<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Controllers;

use Arikaim\Core\Db\Model;
use Arikaim\Core\Controllers\ControlPanelApiController;

/**
 * Product price list control panel api controller
*/
class PriceListControlPanel extends ControlPanelApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('products::admin.messages');
    }

    /**
     * Update product price list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $price = $data->get('price',null);

            $product = Model::Products('products')->findById($data['uuid']); 
            if (is_object($product) == false) {
                $this->error('errors.price.update');
                return;
            }
            
            $result = Model::ProductPriceList('products')->savePriceList($product->id,$price); 
         
            $this->setResponse($result,function() use($product) {                  
                $this
                    ->message('price.update')
                    ->field('uuid',$product->uuid);                  
            },'errors.price.update');                                    
        });
        $data->validate();
    }

    /**
     * Create product price list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function createPriceListController($request, $response, $data) 
    {
        $this->onDataValid(function($data) { 
            $product = Model::Products('products')->findById($data['uuid']);
            $result = $product->createPriceList();

            $this->setResponse($result,function() use($product) {                  
                $this
                    ->message('pricelist')
                    ->field('uuid',$product->uuid);                  
            },'errors.pricelist'); 
        });
        $data->validate();
    }
}
