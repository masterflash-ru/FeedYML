<?php
/**
 
 */

namespace Mf\FeedYML\Writer;

use DateTime;
use Exception;
use Mf\FeedYML\Uri;

/**
*/
class Entry
{
    /**
     * Internal array containing all data associated with this entry or item.
     *
     * @var array
     */
    protected $data = [];

    /**
     * Registered extensions
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * when last exported.
     *
     * @var string
     */
    protected $type = "yml";

    /**
     * Constructor: 
     * $attr - атрибуты в элемент offer
     * как минимум должен быть один id
     */
    public function __construct(array $attr)
    {
        if (!isset($attr["id"])){
            throw new Exception("Не указан ID товара");
        }

        $this->setId($attr["id"]);
        if (isset($attr["available"])){
            $this->setAvailable($attr["available"]);
        }
        if (isset($attr["bid"])){
            $this->setBid($attr["bid"]);
        }
        if (isset($attr["cbid"])){
            $this->setCbid($attr["cbid"]);
        }
    }

    /**
     * Add a feed param
     *
     * @param array $currency
     * @throws Exception
     * @return this
     */
    public function addParam(array $param)
    {
        if (! isset($param['name'])) {
            throw new Exception('Пропущен параметр name в секции param');
        }
        if (! isset($param['label'])) {
            throw new Exception('Пропущен параметр label в секции param');
            }

        if (! isset($this->data['params'])) {
            $this->data['params'] = [];
        }
        $this->data['params'][] = $param;

        return $this;
    }

    /**
     * Set an array of feed params
     *
     * @param array $currencies
     * @return this
     */
    public function setParams(array $params)
    {
        foreach ($params as $param) {
            $this->addParam($param);
        }

        return $this;
    }

    
    /**
     * Set a barcode
     *
     * @param string $sales_notes
     * @throws Exception If any value of $author not follow the format.
     * @return Entry
     */
    public function setBarcode($barcode)
    {
        if (empty($barcode) || ! is_string($barcode)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }

        $this->data['barcode'] = $barcode;

        return $this;
    }


    /**
     * Set a sales_notes
     *
     * @param string $sales_notes
     * @throws Exception If any value of $author not follow the format.
     * @return Entry
     */
    public function setSales_notes($sales_notes)
    {
        if (empty($sales_notes) || ! is_string($sales_notes)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }

        $this->data['sales_notes'] = $sales_notes;

        return $this;
    }


    /**
     * Set a Description
     *
     * @param string $description
     * @throws Exception If any value of $author not follow the format.
     * @return Entry
     */
    public function setDescription($description)
    {
        if (empty($description) || ! is_string($description)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }

        $this->data['description'] = $description;

        return $this;
    }

     /**
     * Set a vendor
     *
     * @param string $description
     * @throws Exception If any value of $author not follow the format.
     * @return Entry
     */
    public function setVendor($vendor)
    {
        if (empty($vendor) || ! is_string($vendor)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }

        $this->data['vendor'] = $vendor;

        return $this;
    }
     /**
     * Set a Model
     *
     * @param string $description
     * @throws Exception If any value of $author not follow the format.
     * @return Entry
     */
    public function setModel($model)
    {
        if (empty($model) || ! is_string($model)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }

        $this->data['model'] = $model;

        return $this;
    }

     /**
     * Set a name
     *
     * @param string $description
     * @throws Exception If any value of $author not follow the format.
     * @return Entry
     */
    public function setName($name)
    {
        if (empty($name) || ! is_string($name)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }

        $this->data['name'] = $name;

        return $this;
    }
   
    /**
     * Set a single picture
     *
     * @param string $picture
     * @throws Exception If any value of $author not follow the format.
     * @return Entry
     */
    public function addPicture($picture)
    {
        if (isset($picture)) {
            if (empty($picture) || ! is_string($picture) ||
                ! Uri::factory($picture)->isValid()
            ) {
                throw new Exception(
                    'Invalid parameter: "uri" array value must be a non-empty string and valid URI/IRI'
                );
            }
        }

        $this->data['picture'][] = $picture;

        return $this;
    }

    /**
     * Set an array picture
     *
     * @see addAuthor
     * @param array $picture
     * @return Entry
     */
    public function addPictures(array $pictures)
    {
        foreach ($pictures as $picture) {
            $this->addPicture($picture);
        }

        return $this;
    }

    /**
     * Set the feed character encoding
     *
     * @param string $encoding
     * @throws Exception
     * @return Entry
     */
    public function setEncoding($encoding)
    {
        if (empty($encoding) || ! is_string($encoding)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }
        $this->data['encoding'] = $encoding;

        return $this;
    }

    /**
     * Get the feed character encoding
     *
     * @return string|null
     */
    public function getEncoding()
    {
        if (! array_key_exists('encoding', $this->data)) {
            return 'UTF-8';
        }
        return $this->data['encoding'];
    }

    /**
     * Set price
     *
     * @param string $price
     * @throws Exception
     * @return Entry
     */
    public function setPrice($price)
    {
        if (empty($price) || ! (is_integer($price) || is_float($price))) {
            throw new Exception('Invalid parameter: допускается только число');
        }
        $this->data['price'] = $price;

        return $this;
    }

    /**
     * Set age
     *
     * @param string $age
     * @throws Exception
     * @return Entry
     */
    public function setAge($age)
    {
        if ( ! is_integer($age)) {
            throw new Exception('Invalid parameter: допускается только число');
        }
        $this->data['age'] = (int)$age;

        return $this;
    }


    /**
     * Set currencyId
     *
     * @param string $content
     * @throws Exception
     * @return Entry
     */
    public function setCurrencyId($currencyId)
    {
        if (empty($currencyId) || ! is_string($currencyId)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }
        $this->data['currencyId'] = $currencyId;

        return $this;
    }

    /**
     * Set categoryId
     *
     * @param int $categoryId
     * @throws Exception
     * @return Entry
     */
    public function addCategoryId($categoryId)
    {
        if (empty($categoryId) || ! is_integer($categoryId)) {
            throw new Exception('Invalid parameter: допускается только целое число');
        }
        $this->data['categoryId'][] = $categoryId;

        return $this;
    }
    
    /**
     * Set categoryId
     *
     * @param array $categoryId
     * @throws Exception
     * @return Entry
     */
    public function setCategoriesId(array $categories)
    {
        foreach ($categories as $category){
            $this->addCategoryId($category);
        }
        return $this;
    }

    /**
     * Set the feed ID
     *
     * @param string $id
     * @throws Exception
     * @return Entry
     */
    public function setId($id)
    {
        if (empty($id) || ! is_integer($id)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }
        $this->data['id'] = $id;

        return $this;
    }

     /**
     * Set the feed manufacturer_warranty
     *
     * @param string $manufacturer_warranty
     * @throws Exception
     * @return Entry
     */
    public function setManufacturer_warranty($manufacturer_warranty)
    {
        if (!is_bool($manufacturer_warranty)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty bollean');
        }

        $this->data['manufacturer_warranty'] = $manufacturer_warranty;

        return $this;
    }
    
    
     /**
     * Set the feed available
     *
     * @param string $available
     * @throws Exception
     * @return Entry
     */
    public function setAvailable($available)
    {
        if (!is_bool($available)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty bollean');
        }

        $this->data['available'] = $available;

        return $this;
    }
   
    /**
     * Set the feed bid
     *
     * @param string $id
     * @throws Exception
     * @return Entry
     */
    public function setBid($id)
    {
        if (empty($id) || ! is_integer($id)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }
        $this->data['bid'] = $id;

        return $this;
    }
    /**
     * Set the feed Cdib
     *
     * @param string $id
     * @throws Exception
     * @return Entry
     */
    public function setCbid($id)
    {
        if (empty($id) || ! is_integer($id)) {
            throw new Exception('Invalid parameter: parameter must be a non-empty string');
        }
        $this->data['cbid'] = $id;

        return $this;
    }

    
    
    /**
     * Set a url to the HTML source of this entry
     *
     * @param string $url
     * @throws Exception
     * @return Entry
     */
    public function setUrl($url)
    {
        if (empty($url) || ! is_string($url) || ! Uri::factory($url)->isValid()) {
            throw new Exception(
                'Invalid parameter: parameter must be a non-empty string and valid URI/IRI'
            );
        }
        $this->data['url'] = $url;

        return $this;
    }


    /**
     * Set the feed store
     *
     * @param boolean $store
     * @throws Exception
     * @return Entry
     */
    public function setStore($store)
    {
        if ((empty($store) && ! is_boolean($store)) ) {
            throw new Exception('Invalid parameter: допускается только логический тип true/false');
        }
        $this->data['store'] = $store;
        return $this;
    }

    /**
     * Set the feed delivery
     *
     * @param boolean $delivery
     * @throws Exception
     * @return Entry
     */
    public function setDelivery($delivery)
    {
        if ((empty($delivery) && ! is_boolean($delivery)) ) {
            throw new Exception('Invalid parameter: допускается только логический тип true/false');
        }
        $this->data['delivery'] = $delivery;
        return $this;
    }
    
    /**
     * Get an array with feed picture
     *
     * @return array
     */
    public function getPicture()
    {
        if (! array_key_exists('picture', $this->data)) {
            return;
        }
        return $this->data['picture'];
    }

    /**
     * Get the entry content
     *
     * @return string
     * /
    public function getContent()
    {
        if (! array_key_exists('content', $this->data)) {
            return;
        }
        return $this->data['content'];
    }

    /**
     * Get the entry price
     *
     * @return string
     */
    public function getPrice()
    {
        if (! array_key_exists('price', $this->data)) {
            return;
        }
        return $this->data['price'];
    }

    /**
     * Get currencyId
     *
     * @return string
     */
    public function getCurrencyId()
    {
        if (! array_key_exists('currencyId', $this->data)) {
            return;
        }
        return $this->data['currencyId'];
    }

    /**
     * Get categoryId
     *
     * @return integer
     */
    public function getCategoryId()
    {
        if (! array_key_exists('categoryId', $this->data)) {
            return;
        }
        return $this->data['categoryId'];
    }

    /**
     * Get the entry description
     *
     * @return string
     */
    public function getDescription()
    {
        if (! array_key_exists('description', $this->data)) {
            return;
        }
        return $this->data['description'];
    }

    /**
     * Get the entry sales_notes
     *
     * @return string
     */
    public function getSales_notes()
    {
        if (! array_key_exists('sales_notes', $this->data)) {
            return;
        }
        return $this->data['sales_notes'];
    }
    /**
     * Get the entry age
     *
     * @return string
     */
    public function getAge()
    {
        if (! array_key_exists('age', $this->data)) {
            return;
        }
        return $this->data['age'];
    }


    /**
     * Get the entry barcode
     *
     * @return string
     */
    public function getBarcode()
    {
        if (! array_key_exists('barcode', $this->data)) {
            return;
        }
        return $this->data['barcode'];
    }

    /**
     * Get the entry name
     *
     * @return string
     */
    public function getName()
    {
        if (! array_key_exists('name', $this->data)) {
            return;
        }
        return $this->data['name'];
    }
    /**
     * Get the entry vendor
     *
     * @return string
     */
    public function getVendor()
    {
        if (! array_key_exists('vendor', $this->data)) {
            return;
        }
        return $this->data['vendor'];
    }
    /**
     * Get the entry model
     *
     * @return string
     */
    public function getModel()
    {
        if (! array_key_exists('model', $this->data)) {
            return;
        }
        return $this->data['model'];
    }

    /**
     * Get the entry manufacturer_warranty
     *
     * @return boolean
     */
    public function getManufacturer_warranty()
    {
        if (! array_key_exists('manufacturer_warranty', $this->data)) {
            return;
        }
        return $this->data['manufacturer_warranty'];
    }


    /**
     * Get the entry ID
     *
     * @return string
     */
    public function getId()
    {
        if (! array_key_exists('id', $this->data)) {
            return;
        }
        return $this->data['id'];
    }

    /**
     * Get available
     *
     * @return string|null
     */
    public function getAvailable()
    {
        if (! array_key_exists('available', $this->data)) {
            return;
        }
        return $this->data['available'];
    }
    /**
     * Get bid
     *
     * @return string|null
     */
    public function getBid()
    {
        if (! array_key_exists('bid', $this->data)) {
            return;
        }
        return $this->data['bid'];
    }
    /**
     * Get cbid
     *
     * @return string|null
     */
    public function getCbid()
    {
        if (! array_key_exists('cbid', $this->data)) {
            return;
        }
        return $this->data['cbid'];
    }


    /**
     * Get all url
     *
     * @return string
     */
    public function getUrl()
    {
        if (! array_key_exists('url', $this->data)) {
            return;
        }
        return $this->data['url'];
    }

    /**
     * Get the entry store
     *
     * @return boolean
     */
    public function getStore()
    {
        if (! array_key_exists('store', $this->data)) {
            return;
        }
        return $this->data['store'];
    }

    /**
     * Get the entry params
     *
     * @return boolean
     */
    public function getParams()
    {
        if (! array_key_exists('params', $this->data)) {
            return;
        }
        return $this->data['params'];
    }

    
    /**
     * Get the entry delivery
     *
     * @return boolean
     */
    public function getDelivery()
    {
        if (! array_key_exists('delivery', $this->data)) {
            return;
        }
        return $this->data['delivery'];
    }


    /**
     * Unset a specific data point
     *
     * @param string $name
     * @return Entry
     */
    public function remove($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }

        return $this;
    }


    /**
     * Retrieve the current or last feed type exported.
     *
     * @return string Value will be "rss" or "atom"
     */
    public function getType()
    {
        return $this->type;
    }



}
