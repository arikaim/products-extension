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
        $data
            ->addRule('text:required','uuid')          
            ->validate(true);

        $product = Model::Products('products')->findById($data['uuid']);
        $result = $product->createOptions();

        $this->setResponse($result,function() use($product) {                  
            $this
                ->message('options')
                ->field('uuid',$product->uuid);                  
        },'errors.options'); 
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
        $data
            ->addRule('text:min=2|required','title')
            ->addRule('number:required','product_type')            
            ->validate(true);

        $model = Model::Products('products');
        $data = [
            'type_id' => $data['product_type'],
            'title'   => $data['title'],
            'user_id' => $this->getUserId()
        ];

        if ($model->hasProduct($data['title']) == true) {
            $this->error('errors.exist');
            return;
        }

        $product = $model->create($data);

        $this->setResponse(($product != null),function() use($product) {                  
            $this
                ->message('add')
                ->field('uuid',$product->uuid);                  
        },'errors.add');                                    
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
        $data->validate(true);

        if (isset($data['product_type']) == true) {
            $data['type_id'] = $data['product_type'];
        }
        if (isset($data['image_id']) == true) {
            $data['image_id'] = (empty($data['image_id']) == true) ? null : $data['image_id'];
        }

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id');
            return;
        }
        
        $result = $product->update($data->toArray());               

        $this->setResponse(($result !== false),function() use($product) {                  
            $this
                ->message('update')
                ->field('uuid',$product->uuid);                  
        },'errors.update');                                    
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
        $data                     
            ->validate(true);

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }

        $result = $product->update([
            'description' => $data['description'],
            'description_summary' => $data['description_summary']                
        ]);

        $this->setResponse(($result !== false),function() use($product) {                  
            $this
                ->message('description')
                ->field('uuid',$product->uuid);                  
        },'errors.description');                                    
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
        $data
            ->addRule('text:min=2|required','external_id')                
            ->validate(true);

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }

        $model = Model::ProductId('products');
        $result = $model->addId($product->id,$data['external_id'],$data['api_driver']);

        $this->setResponse($result,function() use($product) { 
            $this->get('event')->dispatch('product.add',$product->toArray());                         
            $this
                ->message('id.add')
                ->field('uuid',$product->uuid);                  
        },'errors.id.exists');                                      
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
        $data
            ->addRule('text:min=2|required','uuid')                
            ->validate(true);

        $model = Model::ProductId('products')->findById($data['uuid']); 
        if ($model == null) {
            $this->error('errors.id','Not valid extenal product Id');
            return;
        }

        $result = $model->delete();

        $this->setResponse($result,function() use($model) {                  
            $this
                ->message('product.delete')
                ->field('uuid',$model->uuid);                  
        },'errors.delete');                                    
    }

    /**
     * Delete product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function deleteProduct($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','uuid')                
            ->validate(true);

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product Id');
            return;
        }

        $result = $product->deleteProduct();

        $this->setResponse($result,function() use($product) {                  
            $this
                ->message('product.delete')
                ->field('uuid',$product->uuid);                  
        },'errors.delete');                                    
    }
}
