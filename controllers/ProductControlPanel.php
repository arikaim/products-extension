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

use Arikaim\Core\Controllers\Traits\Status;
use Arikaim\Core\Controllers\Traits\SoftDelete;
use Arikaim\Core\Controllers\Traits\MetaTags;

/**
 * Products control panel api controller
*/
class ProductControlPanel extends ControlPanelApiController
{
    use Status,
        MetaTags,
        SoftDelete;

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
     * Constructor
     */
    public function __construct($container = null) 
    {
        parent::__construct($container);
        $this->setModelClass('Products');
        $this->setExtensionName('products');
    }

    /**
     * Creeate product options
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function createOptionsController($request, $response, $data) 
    { 
        $this->onDataValid(function($data) { 
            $product = Model::Products('products')->findById($data['uuid']);
            $result = $product->createOptions();

            $this->setResponse($result,function() use($product) {                  
                $this
                    ->message('options')
                    ->field('uuid',$product->uuid);                  
            },'errors.options'); 
        });
        $data
            ->addRule('text:required','uuid')          
            ->validate();
    }

    /**
     * Add product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $model = Model::Products('products');
            $data = [
                'type_id'     => $data['product_type'],
                'title'       => $data['title']
            ];

            if ($model->hasProduct($data['title']) == true) {
                $this->error('errors.exist');
                return;
            }
            $product = $model->create($data);
            $this->setResponse(is_object($product),function() use($product) {                  
                $this
                    ->message('add')
                    ->field('uuid',$product->uuid);                  
            },'errors.add');                                    
        });
        $data
            ->addRule('text:min=2|required','title')
            ->addRule('number|required','product_type')            
            ->validate();
    }

    /**
     * Update product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $product = Model::Products('products')->findById($data['uuid']); 
            if (\is_object($product) == false) {
                $this->error('errors.update');
                return;
            }
           
            $result = $product->update([
                'type_id'     => $data['product_type'],
                'title'       => $data['title']
            ]);

            $this->setResponse(($result !== false),function() use($product) {                  
                $this
                    ->message('update')
                    ->field('uuid',$product->uuid);                  
            },'errors.update');                                    
        });
        $data
            ->addRule('text:min=2|required','title')
            ->addRule('number|required','product_type')            
            ->validate();
    }

    /**
     * Update product description
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateDescriptionController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $product = Model::Products('products')->findById($data['uuid']); 
            if (\is_object($product) == false) {
                $this->error('errors.update');
                return;
            }

            $result = $product->update([
                'description' => $data['description']           
            ]);

            $this->setResponse(($result !== false),function() use($product) {                  
                $this
                    ->message('description')
                    ->field('uuid',$product->uuid);                  
            },'errors.description');                                    
        });
        $data                     
            ->validate();
    }

    /**
     * Add external id
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function addExternalIdController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $product = Model::Products('products')->findById($data['uuid']); 
            if (is_object($product) == false) {
                $this->error('errors.id');
                return;
            }

            $model = Model::ProductId('products');
            $result = $model->addId($product->id,$data['external_id'],$data['api_driver']);

            $this->setResponse($result,function() use($product) {                  
                $this
                    ->message('id.add')
                    ->field('uuid',$product->uuid);                  
            },'errors.id.exists');                                    
        });
        $data
            ->addRule('text:min=2|required','external_id')                
            ->validate();
    }

    /**
     * Delete external id
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteExternalIdController($request, $response, $data) 
    {         
        $this->onDataValid(function($data) { 
            $model = Model::ProductId('products')->findById($data['uuid']); 
            if (is_object($model) == false) {
                $this->error('errors.id.delete');
                return;
            }

            $result = $model->delete();

            $this->setResponse($result,function() use($model) {                  
                $this
                    ->message('id.delete')
                    ->field('uuid',$model->uuid);                  
            },'errors.id.delete');                                    
        });
        $data
            ->addRule('text:min=2|required','uuid')                
            ->validate();
    }
}
