<?php
/**
 */

namespace Mf\FeedYML\Reader\Entry;

use DateTime;
use DOMElement;
use DOMXPath;
use Mf\FeedYML\Reader;
use Exception;
use Mf\FeedYML\Reader\Entry\AbstractEntry;


class Yml extends AbstractEntry
{
    /**
     * XPath query for YML
     *
     * @var string
     */
    protected $xpathQueryYml = '';


    /**
     * Constructor
     *
     * @param  DOMElement $entry
     * @param  string $entryKey
     * @param  string $type
     */
    public function __construct(DOMElement $entry, $entryKey, $type = null)
    {
        if ($type !== Reader\Reader::TYPE_YML){
            throw new Exception("Элемент документа не является частью YML документа");
        }
        parent::__construct($entry, $entryKey, $type);
        $this->xpathQueryYml = '//offer[' . ($this->entryKey + 1) . ']';
    }

    /**
     * Get an url entry
     * получить параметр url 
     * 
     * @return string
     */
    public function getUrl()
    {
        if (array_key_exists('offer_url', $this->data)) {
            return $this->data['offer_url'];
        }

         $url = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/url)');

        if (!$url) {
            $url = null;
        }

        $this->data['offer_url'] = $url;

        return $this->data['offer_url'];

    }

    /**
     * Get an price entry
     * получить параметр price
     * 
     * @return string
     */
    public function getPrice()
    {
        if (array_key_exists('offer_price', $this->data)) {
            return $this->data['offer_price'];
        }

        $price = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/price)');

        if (!$price) {
            $price = null;
        }

        $this->data['offer_price'] = $price;

        return $this->data['offer_price'];
    }

    
    
    /**
     * Get an currencyId entry
     * получить параметр currencyId
     * 
     * @return string
     */
    public function getCurrencyId()
    {
        if (array_key_exists('offer_currencyId', $this->data)) {
            return $this->data['offer_currencyId'];
        }
        $currencyId = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/currencyId)');

        if (!$currencyId) {
            $currencyId = null;
        }

        $this->data['offer_currencyId'] = $currencyId;

        return $this->data['offer_currencyId'];
    }
    
    
    /**
     * Get an categoryId entry
     * получить параметр currencyId
     * 
     * @return integer
     */
    public function getCategoryId()
    {
        if (array_key_exists('offer_categoryId', $this->data)) {
            return $this->data['offer_categoryId'];
        }

        $categoryId = (int)$this->xpath->evaluate('string(' .$this->xpathQueryYml . '/categoryId)');
        if (!$categoryId) {
            $currencyId = null;
        }

        $this->data['offer_categoryId'] = $categoryId;

        return $this->data['offer_categoryId'];
    }
    
    
    /**
     * Get an array with feed pictures
     *
     * @return array
     */
    public function getPicturies()
    {
        if (array_key_exists('offer_pictures', $this->data)) {
            return $this->data['offer_pictures'];
        }
        $list = $this->xpath->query($this->xpathQueryYml . '//picture');

        if ($list->length) {
            foreach ($list as $picture) {
                $data = trim($picture->nodeValue);
                    $pictures[] = $data;
            }
        } else {
            $pictures=[];
        }

        $pictures = new Reader\Collection\Picture(
            Reader\Reader::arrayUnique($pictures)
            );
        
        if (count($pictures) == 0) {
            $pictures = null;
        }

        $this->data['offer_pictures'] = $pictures;

        return $this->data['offer_pictures'];
    }

    
    /**
     * Get a specific picture
     *
     * @param  int $index
     * @return string
     */
    public function getPicture($index = 0)
    {
        if (isset($this->data['offer_pictures'][$index])) {
            return $this->data['offer_pictures'][$index];
        }

        return;
    }

    
    /**
     * Get an store entry
     * получить параметр store
     * 
     * @return boolean/null
     */
    public function getStore()
    {
        if (array_key_exists('offer_store', $this->data)) {
            return $this->data['offer_store'];
        }


        $store = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/store)');
        if ($store==="true") {
            $store=true;
        }elseif ($store==="false") {
            $store=false;
        } else {
            $store=null;
        }

        $this->data['offer_store'] = $store;

        return $this->data['offer_store'];
    }

 
     /**
     * Get an delivery entry
     * получить параметр delivery
     * 
     * @return boolean/null
     */
    public function getDelivery()
    {
        if (array_key_exists('offer_delivery', $this->data)) {
            return $this->data['offer_delivery'];
        }
        $delivery = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/delivery)');
        if ($delivery==="true") {
            $delivery=true;
        }elseif ($delivery==="false") {
            $delivery=false;
        } else {
            $delivery=null;
        }


        $this->data['offer_delivery'] = $delivery;

        return $this->data['offer_delivery'];
    }
   
    
    
    /**
     * Get an name entry
     * получить параметр name
     * 
     * @return string
     */
    public function getName()
    {
        if (array_key_exists('offer_name', $this->data)) {
            return $this->data['offer_name'];
        }
        $name = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/name)');

        if (!$name) {
            $name = null;
        }

        $this->data['offer_name'] = $name;

        return $this->data['offer_name'];
    }
    
    /**
     * Get an vendor entry
     * получить параметр name
     * 
     * @return string
     */
    public function getVendor()
    {
        if (array_key_exists('offer_vendor', $this->data)) {
            return $this->data['offer_vendor'];
        }
        $vendor = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/vendor)');

        if (!$vendor) {
            $vendor = null;
        }

        $this->data['offer_vendor'] = $vendor;

        return $this->data['offer_vendor'];
    }

    /**
     * Get an model entry
     * получить параметр name
     * 
     * @return string
     */
    public function getModel()
    {
        if (array_key_exists('offer_model', $this->data)) {
            return $this->data['offer_model'];
        }
        $model = $this->xpath->evaluate('string(' . $this->xpathQueryYml . '/model)');

        if (!$model) {
            $model = null;
        }

        $this->data['offer_model'] = $model;

        return $this->data['offer_model'];
    }
    

    /**
     * Get the entry description
     *
     * @return string
     */
    public function getDescription()
    {
        if (array_key_exists('description', $this->data)) {
            return $this->data['description'];
        }

        $description = $this->xpath->evaluate('string(' . $this->xpathQueryYml . '/description)');

        if (! $description) {
            $description = null;
        }

        $this->data['description'] = $description;

        return $this->data['description'];
    }

    
    /**
     * Get the entry sales_notes
     *
     * @return string
     */
    public function getSales_notes()
    {
        if (array_key_exists('sales_notes', $this->data)) {
            return $this->data['sales_notes'];
        }

        $sales_notes = $this->xpath->evaluate('string(' . $this->xpathQueryYml . '/sales_notes)');

        if (! $sales_notes) {
            $sales_notes = null;
        }

        $this->data['sales_notes'] = $sales_notes;

        return $this->data['sales_notes'];
    }

    /**
     * Get the entry barcode
     *
     * @return string
     */
    public function getBarcode()
    {
        if (array_key_exists('barcode', $this->data)) {
            return $this->data['barcode'];
        }

        $barcode = $this->xpath->evaluate('string(' . $this->xpathQueryYml . '/barcode)');

        if (! $barcode) {
            $barcode = null;
        }

        $this->data['barcode'] = $barcode;

        return $this->data['barcode'];
    }

    
    /**
     * Get the entry age
     *
     * @return string
     */
    public function getAge()
    {
        if (array_key_exists('age', $this->data)) {
            return $this->data['age'];
        }

        $age = $this->xpath->evaluate('string(' . $this->xpathQueryYml . '/age)');

        if (! $age && $age!=="0") {
            $age = null;
        }

        $this->data['age'] = $age;

        return $this->data['age'];
    }

    
     /**
     * Get an manufacturer_warranty entry
     * получить параметр manufacturer_warranty
     * 
     * @return boolean/null
     */
    public function getManufacturer_warranty()
    {
        if (array_key_exists('manufacturer_warranty', $this->data)) {
            return $this->data['manufacturer_warranty'];
        }
        $manufacturer_warranty = $this->xpath->evaluate('string(' .$this->xpathQueryYml . '/manufacturer_warranty)');
        if ($manufacturer_warranty==="true") {
            $manufacturer_warranty=true;
        }elseif ($manufacturer_warranty==="false") {
            $manufacturer_warranty=false;
        } else {
            $manufacturer_warranty=null;
        }


        $this->data['manufacturer_warranty'] = $manufacturer_warranty;

        return $this->data['manufacturer_warranty'];
    }


    /**
     * Get offer attributes
     *
     * @return Reader\Collection\Offer
     */
    public function getOffer()
    {
        if (array_key_exists('offer', $this->data)) {
            return $this->data['offer'];
        }
        
        $a=$this->entry->getAttribute('available');
        if ($a==="true") {
            $a=true;
        }elseif ($a==="false") {
            $a=false;
        } else {
            $a=null;
        }

        $offerCollection = [
                    'id' => $this->entry->getAttribute('id'),
                    'available' => $a,
                    'bid' => $this->entry->getAttribute('bid'),
                    'cbid' => $this->entry->getAttribute('cbid'),
                ];
        $offerCollection = new Reader\Collection\Offer(
            Reader\Reader::arrayUnique($offerCollection)
            );

        $this->data['offer'] = $offerCollection;

        return $this->data['offer'];
    }

    /**
     * Get an array with feed param
     *
     * @return array
     */
    public function getParams()
    {
        if (array_key_exists('params', $this->data)) {
            return $this->data['params'];
        }
        $list = $this->xpath->query($this->xpathQueryYml . '//param');

        if ($list->length) {
            foreach ($list as $p) {
                $params[$p->getAttribute("name")] = $p->nodeValue;
            }
        } else {
            $params=[];
        }

        $params = new Reader\Collection\Param(
            Reader\Reader::arrayUnique($params)
            );
        
        if (count($params) == 0) {
            $param = null;
        }

        $this->data['params'] = $params;

        return $this->data['params'];
    }

    /**
     * Set the XPath query (incl. on all Extensions)
     *
     * @param DOMXPath $xpath
     * @return void
     */
    public function setXpath(DOMXPath $xpath)
    {
        parent::setXpath($xpath);
        foreach ($this->extensions as $extension) {
            $extension->setXpath($this->xpath);
        }
    }
}
