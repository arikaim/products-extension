<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Products\Classes;

use Arikaim\Extensions\Products\Classes\ProductInterface;

/**
 * Product
*/
abstract class AbstractProduct implements ProductInterface
{
    /**
     * Product
     *
     * @var string
     */
    protected $title;

    /**
     * Description
     *
     * @var string|null
    */
    protected $description = null;

    /**
     * Price
     *
     * @var float
     */
    protected $price;

    /**
     * Currency code
     *
     * @var string
     */
    protected $currency;

    /**
     * Options 
     *
     * @var array
     */
    protected $options = [];

    /**
     * Create product
     *
     * @param array $data
     * @return ProductInterface 
     */
    abstract public function create(array $data);
    
    /**
     * Constructor
     *    
    */
    public function __construct()
    {       
    }

    /**
     * Create product
     *
     * @param array $data
     * @return ProductInterface
     */
    public static function createFromArray(array $data)
    {
        $instance = new static();

        return $instance->create($data);
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * Description
     *    
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Get Price
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price ?? 0.00;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Get currency code
     *
     * @return string
     */
    public function getCurrency(): string
    {
        return (empty($this->currency) == true) ? 'USD' : $this->currency;
    }

    /**
     * Get id
     *
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * Set id
     * 
     * @param string|null $id
     * @return void
     */
    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    /**
     * Convert to array
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id'          => $this->getId(),         
            'price'       => $this->getPrice(),
            'title'       => $this->getTitle(),
            'description' => $this->getDescription(),
            'options'     => $this->getOptions()
        ];
    }
}
