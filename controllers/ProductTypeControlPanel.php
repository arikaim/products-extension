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
 * Products type control panel api controller
*/
class ProductTypeControlPanel extends ControlPanelApiController
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
     * Add product type
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $model = Model::ProductType('products');            
            if ($model->findBySlug($data['title']) == true) {
                $this->error('errors.type.exist');
                return;
            }

            $productType = $model->create($data->toArray());

            $this->setResponse(\is_object($productType),function() use($productType) {                  
                $this
                    ->message('type.add')
                    ->field('uuid',$productType->uuid);                  
            },'errors.type.add');                                    
        });
        $data->validate();
    }

    /**
     * Update product type
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $uuid = $data['uuid'];
            $model = Model::ProductType('products')->findById($uuid); 
            if (\is_object($model) == false) {
                $this->error('errors.type.exist');
                return;
            }

            if ($model->readonly == 1) {
                $this->error('errors.type.readonly');
                return;
            }

            $result = (bool)$model->update($data->toArray());

            $this->setResponse($result,function() use($uuid) {                  
                $this
                    ->message('type.update')
                    ->field('uuid',$uuid);                  
            },'errors.type.update');                                    
        });
        $data->validate();
    }

    /**
     * Delete product type
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $uuid = $data['uuid'];
            $model = Model::ProductType('products')->findById($uuid);
            
            if ($model->hasProducts() == true) {
                $this->error('errors.type.used');
                return;
            }
           
            if ($model->readonly == 1) {
                $this->error('errors.type.readonly');
                return;
            }

            $result = $model->delete();
            $this->setResponse($result,function() use($uuid) {                  
                $this
                    ->message('type.delete')
                    ->field('uuid',$uuid);                  
            },'errors.type.delete');                                    
        });
        $data->validate();
    }
}
