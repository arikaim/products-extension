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
use Arikaim\Core\Controllers\ApiController;
use Arikaim\Extensions\Products\Controllers\Traits\Products;
use Arikaim\Extensions\Products\Controllers\Traits\Options;
use Arikaim\Core\Controllers\Traits\Status;
use Arikaim\Core\Controllers\Traits\SoftDelete;
use Arikaim\Core\Controllers\Traits\MetaTags;

/**
 * Products api controller
*/
class ProductsApi extends ApiController
{
    use 
        Status,
        Products,
        SoftDelete,
        MetaTags,
        Options;

    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('current>products.messages');
        $this->setModelClass('Products');
        $this->setExtensionName('products');
    }

    /**
     * Update product price list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function updatePrice($request, $response, $data) 
    {         
        $data
            ->validate(true);

        $uuid = $data->get('uuid',null);
        $prices = $data->get('prices',[]);
        
        $product = Model::Products('products')->findById($uuid); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id');
            return false;
        }
        
        // check access
        $this->requireUserOrControlPanel($product->user_id);

        $priceList = Model::ProductPriceList('products');

        foreach ($prices as $key => $value) {
            $priceList->savePrice($product->id,$value,$key,null);
        }
        
        $this
            ->message('price.update','Price saved successfully')
            ->field('uuid',$product->uuid);                                                  
    }

    /**
     * Delete user product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function deleteUserProduct($request, $response, $data) 
    {         
        $data->validate(true);

        $uuid = $data->get('uuid',null);
        $product = Model::Products('products')->findById($uuid); 
        if ($product === null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }
        
        // only product created from logged user
        if (empty($product->user_id) == true) {
            $this->error('errors.access','Access denied');
            return;
        }

        // check access
        $this->requireUserOrControlPanel($product->user_id);

        $result = $product->deleteProduct();
        if ($result === false) {
            $this->error('errors.delete','Error delete product');
            return;
        }

        $this->get('event')->dispatch('product.delete',$product->toArray());        

        $this
            ->message('delete')
            ->field('uuid',$product->uuid);                                                          
    }

    /**
     * Update product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function update($request, $response, $data) 
    {         
        $data
            ->validate(true);
        
        $price = $data->get('price',null);

        if (isset($data['product_type']) == true) {
            $data['type_id'] = $data['product_type'];
        }
        
        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }
        
        // check access
        $this->requireUserOrControlPanel($product->user_id);

        $result = $product->update([         
            'type_id'     => $data['product_type'],       
            'title'       => $data['title'],
            'description' => $data['description']
        ]);               

        if ($result === false) {
            $this->error('errors.update','Error save product.');
            return;
        }

        if (empty($price) == false) {
            // save price
            Model::ProductPriceList('products')->savePrice($product->id,'price',$price,"USD"); 
        }

        // dispatch event
        $this->get('event')->dispatch('product.update',$product->toArray()); 

        $this
            ->message('update','Product was saved successfully.')
            ->field('uuid',$product->uuid);                                                          
    }

    /**
     * Add product
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function add($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','title')
            ->addRule('number:required','product_type')            
            ->validate(true);

        
        $description = $data->get('description',null);
        $price = $data->get('price',null);
        $userId = $this->getUserId();
      
        // check access
        if ($this->get('access')->hasAccessOneFrom([
                'ControlPanel:full',
                'Products:write'
            ]) == false) {
            // deny
            $this->error('errors.access','Access Denied');
            return false;
        }

        $model = Model::Products('products');
        if ($model->hasProduct($data['title'],$userId) == true) {
            $this->error('errors.exist','Product with this name exists.');
            return;
        }
 
        $product = $model->create([
            'type_id'     => $data['product_type'],
            'title'       => $data['title'],
            'user_id'     => $userId,
            'description' => $description
        ]);

        if ($product == null) {
            $this->error('errors.add','Error add product');
            return;
        }

        if (empty($price) == false) {
            // save price
            Model::ProductPriceList('products')->savePrice($product->id,$price); 
        }

        // dispatch event
        $this->get('event')->dispatch('product.add',$product->toArray());    

        $this
            ->message('add','Product created successfully.')
            ->field('uuid',$product->uuid);                                             
    }

    /**
     * Get products list
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function getList($request, $response, $data) 
    {          
        $products = $this->getProductsList($request,$response,$data);
        $result = $products->toArray();
             
        $this
            ->field('paginator',$result['paginator']) 
            ->field('items',$result['rows']);
    }
    
    /**
     * Get products list (for products dropdown)
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function getDropdownList($request, $response, $data) 
    {       
        $data->validate(true);

        $dataField = $data->get('data_field','uuid');
        $search = $data->get('query','');
        $size = $data->get('size',15);
        $userId = (int)$data->get('user',$this->getUserId());

        $products = Model::Products('products');

        if (empty($userId) == false) {
            $products->userQuery($userId);
        }

        if (empty($search) == false) {
            $products = $products->searchIgnoreCase('title',$search)->get();
        } else {
            $products = $products->take($size)->get();
        }

        if ($products == null) {
            $this->error('errors.list');
            return;
        }
           
        $items = [];
        foreach ($products as $item) {
            $items[] = [
                'name'  => $item['title'],
                'value' => $item[$dataField]
            ];
        }

        $this                    
            ->field('success',true)
            ->field('results',$items);  
                                        
        return $this->getResponse(true);
    }

    /**
     * Get product details
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
     * @return mixed
    */
    public function productDetails($request, $response, $data) 
    {          
        $data
            ->addRule('text:required','uuid')
            ->validate(true);    

        $product = Model::Products('products')->findById($data['uuid']);
        if ($product == null) {
            $this->error('Product not exist.');
            return false;
        }

        if ($product->status != $product->ACTIVE()) {
            $this->error('Product not exist or not published.');
            return false;
        }

        $this->setResultFields($product->toArray());

        $categories = [];
        foreach ($product->categories as $item) {
            $categories[] = [
                'title' => $item->getTitle(),
                'slug'  => $item->getSlug()
            ];
        }
        
        $this->setResultFields($categories,'categories');         
    }

    /**
     * Update product description
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
     * @return mixed
    */
    public function updateDescription($request, $response, $data) 
    {         
        $data                     
            ->validate(true);

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }

        // check access
        $this->requireUserOrControlPanel($product->user_id);

        $result = $product->update([
            'description'         => $data['description'],
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
     * @param \Arikaim\Core\Validator\Validator $data
     * @return mixed
    */
    public function addExternalId($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','external_id')                
            ->validate(true);

        $product = Model::Products('products')->findById($data['uuid']); 
        if ($product == null) {
            $this->error('errors.id','Not valid product id.');
            return;
        }

        // check access
        $this->requireUserOrControlPanel($product->user_id);


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
     * @param \Arikaim\Core\Validator\Validator $data
     * @return mixed
    */
    public function deleteExternalId($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','uuid')                
            ->validate(true);

        $model = Model::ProductId('products')->findById($data['uuid']); 
        if ($model == null) {
            $this->error('errors.id','Not valid extenal product Id');
            return;
        }

        // check access
        $this->requireUserOrControlPanel($model->product->user_id);

        $result = $model->delete();

        $this->setResponse($result,function() use($model) {                  
            $this
                ->message('product.delete')
                ->field('uuid',$model->uuid);                  
        },'errors.delete');                                    
    }
}
