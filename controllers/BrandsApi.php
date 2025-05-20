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
use Arikaim\Core\Controllers\Traits\Status;

/**
 * Product brands api controller
*/
class BrandsApi extends ApiController
{
    use 
        Status;

    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('current>products.brands.messages');
        $this->setModelClass('Brands');
        $this->setExtensionName('products');
    }

    /**
     * Delete product brand
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function delete($request, $response, $data) 
    {         
        $data
            ->validate(true);

        $uuid = $data->get('uuid',null);

        $brand = Model::Brands('products')->findById($uuid); 
        if ($brand === null) {
            $this->error('errors.id','Not valid brand id.');
            return;
        }
        
        // check access
        $this->requireUserOrControlPanel($brand->user_id);

        $result = $brand->delete();
        if ($result === false) {
            $this->error('errors.delete','Error delete brand');
            return;
        }

        $this
            ->message('delete','Brand was deleted successfully.')
            ->field('uuid',$brand->uuid);                                                          
    }

    /**
     * Update product brand
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function update($request, $response, $data) 
    {         
        $data
            ->validate(true);
        
        $uuid = $data->get('uuid',null);

        $brand = Model::Brands('products')->findById($uuid); 
        if ($brand === null) {
            $this->error('errors.id','Not valid brand id.');
            return;
        }

        // check access
        $this->requireUserOrControlPanel($brand->user_id);

        $result = $brand->update([         
            'icon'        => $data['icon'],       
            'title'       => $data['title'],
            'description' => $data['description']
        ]);               

        if ($result === false) {
            $this->error('errors.update','Error save brand.');
            return;
        }

        $this
            ->message('update','Brand was saved successfully.')
            ->field('uuid',$brand->uuid);                                                          
    }

    /**
     * Create product brand
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param \Arikaim\Core\Validator\Validator $data
    */
    public function add($request, $response, $data) 
    {         
        $data
            ->addRule('text:min=2|required','title')  
            ->validate(true);

        
        $description = $data->get('description',null);
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

        $model = Model::Brands('products');
        if ($model->hasBrand($data['title'],$userId) == true) {
            $this->error('errors.exist','Brand with this name exists.');
            return;
        }
 
        $brand = $model->create([         
            'icon'        => $data['icon'],
            'title'       => $data['title'],
            'user_id'     => $userId,
            'description' => $description
        ]);

        if ($brand == null) {
            $this->error('errors.add','Error create brend');
            return;
        }

        $this
            ->message('add','Brand created successfully.')
            ->field('uuid',$brand->uuid);                                             
    }
}
