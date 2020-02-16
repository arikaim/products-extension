<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Models;

use Illuminate\Database\Eloquent\Model;

use Arikaim\Core\Db\Model as DbModel;
use Arikaim\Extensions\Products\Models\Currency;
use Arikaim\Extensions\Products\Models\Products;
use Arikaim\Core\Models\Users;
use Arikaim\Core\Http\Session;

use Arikaim\Core\Db\Traits\Uuid;
use Arikaim\Core\Db\Traits\Find;
use Arikaim\Core\Db\Traits\UserRelation;
use Arikaim\Core\Db\Traits\Status;
use Arikaim\Core\Db\Traits\DateCreated;
use Arikaim\Core\Db\Traits\DateUpdated;

/**
 * Shopping Cart model class
 */
class ShoppingCart extends Model  
{
    use Uuid,
        Status,
        UserRelation,
        DateCreated,
        DateUpdated,
        Find;

    /**
     * Table name
     *
     * @var string
     */
    protected $table = "shopping_cart";

    /**
     * Fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'session_id',
        'currency_id',
        'title',
        'qty',
        'price'
    ];
    
    /**
     * Disable timestamps
     *
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Session key prefix
     *
     * @var string
     */
    protected $keyPrefix = 'shopping.cart';

    /**
     * Currency relation
     *
     * @return mixed
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class,'currency_id');
    }

    /**
     * Product relation
     *
     * @return mixed
     */
    public function product()
    {
        return $this->belongsTo(Products::class,'product_id');
    }

    /**
     * User relation
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(Users::class,'user_id');
    }

    /**
     * Update cart item
     *
     * @param integer $productId
     * @param float $price
     * @param integer $currencyId
     * @param integer $qty
     * @return boolean
     */
    public function updateItem($productId, $price, $currencyId, $qty = 1)
    {
        if ($this->hasItem($productId) == false) {
            return false;
        }
        $this->deleteItem($productId); 
       
        return $this->addItem($productId,$price,$currencyId,$qty);        
    }

    /**
     * Add cart item
     *
     * @param integer $productId
     * @param float $price
     * @param integer $currencyId
     * @param integer $qty
     * @return boolean
     */
    public function addItem($productId, $price, $currencyId, $qty = 1)
    {
        if ($this->hasItem($productId) == true) {
            return $this->updateItem($productId,$price,$currencyId,$qty);
        }

        $product = DbModel::Products('store')->findById($productId);
        if (is_object($product) == false) {
            return false;
        }

        $items = $this->getItems();
        $cartItem = [           
            'qty'         => $qty,
            'title'       => $product->title,
            'price'       => $price,
            'currency_id' => $currencyId 
        ];

        // set item
        $items[$productId] = $cartItem;
        Session::set($this->keyPrefix,$items);

        $cartItem['session_id'] = Session::getid();
        $cartItem['product_id'] = $productId;
        
        $item = $this->create($cartItem);

        return is_object($item);
    }

    /**
     * Get cart item
     *
     * @param integer $productId
     * @return array|null
     */
    public function getItem($productId)
    {
        $items = $this->getItems();
        $item = (isset($items[$productId]) == true) ? $items[$productId] : null; 

        return $item;
    }

    /**
     * Return cart items count
     *
     * @return integer
     */
    public function getItemsCount()
    {
        $items = Session::get($this->keyPrefix,[]);
        
        return count($items);
    }

    /**
     * Get cart items
     *
     * @return array
     */
    public function getItems()
    {
        return Session::get($this->keyPrefix,[]);
    }

    /**
     * Return true if item exists
     *
     * @param integer $productId
     * @return boolean
     */
    public function hasItem($productId)
    {
        $items = $this->getItems();
    
        return (isset($items[$productId]) == true);
    }

    /**
     * Delete cart item
     *
     * @param integer $productId
     * @return boolean
     */
    public function deleteItem($productId)
    {
        $items = $this->getItems();
        unset($items[$productId]);

        Session::set($this->keyPrefix,$items);
        // delete model 
        $sessionId = Session::getId();

        return (bool)$this->where('session_id','=',$sessionId)->where('product_id','=',$productId)->delete();
    }

    /**
     * Get cart total
     *
     * @return float
     */
    public function getTotal()
    {
        $items = $this->getItems();
        $total = 0;
        foreach ($items as $item) {
            $itemTotal = $item['price'] * $item['qty'];
            $total += $itemTotal;
        }

        return $total;
    }      
}
