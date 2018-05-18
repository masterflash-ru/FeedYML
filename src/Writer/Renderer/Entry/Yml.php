<?php
/**
 */
namespace Mf\FeedYML\Writer\Renderer\Entry;

use DateTime;
use DOMDocument;
use DOMElement;
use Zend\Feed\Uri;
use Mf\FeedYML\Writer;
use Mf\FeedYML\Writer\Renderer;
use Exception;

/**
*/
class Yml extends Renderer\AbstractRenderer 
{
    /**
     * Constructor
     *
     * @param  Writer\Entry $container
     */
    public function __construct(Writer\Entry $container)
    {
        parent::__construct($container);
    }

    /**
     * Render RSS entry
     *
     * @return Rss
     */
    public function render()
    {
        $this->dom = new DOMDocument('1.0', $this->container->getEncoding());
        $this->dom->formatOutput = true;
        $this->dom->substituteEntities = false;
        $offers = $this->dom->createElement('offers');
        $offer=$this->_createOffer($this->dom, $offers);
        $this->_setUrl($this->dom, $offer);
        $this->_setPrice($this->dom, $offer);
        $this->_setCurrencyId($this->dom, $offer);
        $this->_setCategoryId($this->dom, $offer);
        $this->_setPicture($this->dom, $offer);
        $this->_setStore($this->dom, $offer);
        $this->_setDelivery($this->dom, $offer);
        $this->_setName($this->dom, $offer);
        $this->_setVendor($this->dom, $offer);
        $this->_setModel($this->dom, $offer);
        $this->_setDescription($this->dom, $offer);
        $this->_setSales_notes($this->dom, $offer);
        $this->_setBarcode($this->dom, $offer);
        $this->_setAge($this->dom, $offer);
        $this->_setManufacturer_warranty($this->dom, $offer);
        $this->_setParams($this->dom, $offer);
        
        $offers->appendChild($offer);
        $this->dom->appendChild($offers);

        return $this;
    }
    /**
     * создает элемент offer и возвращает его
     *@param  DOMElement $element
     * @return void
     */
    protected function _createOffer(DOMDocument $dom,DOMElement $element)
    {
        $offer=$this->dom->createElement('offer');
       
        $offer->setAttribute("id",$this->getDataContainer()->getId());
        $bid=$this->getDataContainer()->getBid();
        if ($bid){
            $offer->setAttribute("bid",$bid);
        }
        $cbid=$this->getDataContainer()->getCbid();
        if ($cbid){
            $offer->setAttribute("cbid",$cbid);
        }
        $available=$this->getDataContainer()->getAvailable();
        if ($available){
            $offer->setAttribute("available",($available)?"true":"false"  );
        }

        return $offer;
    }
    
    /**
     * Set url to entry
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */

    protected function _setUrl(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getUrl()) {
            throw new Exception('Пропущен обязательный элемент url в секции offer');
        }
        $link = $dom->createElement('url');
        $root->appendChild($link);
        $text = $dom->createTextNode($this->getDataContainer()->getUrl());
        $link->appendChild($text);
    }


    /**
     * Set price to entry
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */

    protected function _setPrice(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getPrice()) {
            throw new Exception('Пропущен обязательный элемент price в секции offer');
        }
        $price = $dom->createElement('price');
        $root->appendChild($price);
        $text = $dom->createTextNode($this->getDataContainer()->getPrice());
        $price->appendChild($text);
    }

     /**
     * Set name
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setName(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getName()) {
            throw new Exception('Пропущен обязательный элемент name в секции offer');
        }
        $name = $dom->createElement('name');
        $root->appendChild($name);
        $text = $dom->createTextNode($this->getDataContainer()->getName());
        $name->appendChild($text);
    }
     /**
     * Set vendor
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setVendor(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getVendor()) {
            return;
        }
        $vendor = $dom->createElement('vendor');
        $root->appendChild($vendor);
        $text = $dom->createTextNode($this->getDataContainer()->getVendor());
        $vendor->appendChild($text);
    }
     /**
     * Set model
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setModel(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getModel()) {
            return;
        }
        $model = $dom->createElement('model');
        $root->appendChild($model);
        $text = $dom->createTextNode($this->getDataContainer()->getModel());
        $model->appendChild($text);
    }

     /**
     * Set Description
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setDescription(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getDescription()) {
            return;
        }
        $Description = $dom->createElement('description');
        $root->appendChild($Description);
        $text = $dom->createTextNode($this->getDataContainer()->getDescription());
        $Description->appendChild($text);
    }

     /**
     * Set sales_notes
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setSales_notes(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getSales_notes()) {
            return;
        }
        $sales_notes = $dom->createElement('sales_notes');
        $root->appendChild($sales_notes);
        $text = $dom->createTextNode($this->getDataContainer()->getSales_notes());
        $sales_notes->appendChild($text);
    }

     /**
     * Set barcode
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setBarcode(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getBarcode()) {
            return;
        }
        $barcode = $dom->createElement('barcode');
        $root->appendChild($barcode);
        $text = $dom->createTextNode($this->getDataContainer()->getBarcode());
        $barcode->appendChild($text);
    }

     /**
     * Set age
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setAge(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getAge()) {
            return;
        }
        $age = $dom->createElement('age');
        $root->appendChild($age);
        $text = $dom->createTextNode($this->getDataContainer()->getAge());
        $age->appendChild($text);
    }

     /**
     * Set currencyId
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setCurrencyId(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getCurrencyId()) {
            throw new Exception('Пропущен обязательный элемент currencyId в секции offer');
        }
        $currencyId = $dom->createElement('currencyId');
        $root->appendChild($currencyId);
        $text = $dom->createTextNode($this->getDataContainer()->getCurrencyId());
        $currencyId->appendChild($text);
    }
   
       /**
     * Set categoryId
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setCategoryId(DOMDocument $dom, DOMElement $root)
    {
        $cc=$this->getDataContainer()->getCategoryId();
        if (! $cc) {
            throw new Exception('Пропущен обязательный элемент categoryId в секции offer');
        }
        foreach ($cc as $c){
            $categoryId = $dom->createElement('categoryId');
            $root->appendChild($categoryId);
            $text = $dom->createTextNode($c);
            $categoryId->appendChild($text);

        }
    }
  
        /**
     * Set picture
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setPicture(DOMDocument $dom, DOMElement $root)
    {
        $p=$this->getDataContainer()->getPicture();
        if (! $p) {
            return;
        }
        foreach ($p as $picitem){
            $picture = $dom->createElement('picture');
            $root->appendChild($picture);
            $text = $dom->createTextNode($picitem);
            $picture->appendChild($text);

        }
    }
   
     /**
     * Set Store
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setStore(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getStore()) {
            return;
        }
        $store = $dom->createElement('store');
        $root->appendChild($store);
        $text = $dom->createTextNode(($this->getDataContainer()->getStore())?"true":"false");
        $store->appendChild($text);
    }


     /**
     * Set delivery
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setDelivery(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getDelivery()) {
            return;
        }
        $delivery = $dom->createElement('delivery');
        $root->appendChild($delivery);
        $text = $dom->createTextNode(($this->getDataContainer()->getDelivery())?"true":"false");
        $delivery->appendChild($text);
    }

     /**
     * Set manufacturer_warranty
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $root
     * @return void
     */
    protected function _setManufacturer_warranty(DOMDocument $dom, DOMElement $root)
    {
        if (! $this->getDataContainer()->getManufacturer_warranty()) {
            return;
        }
        $manufacturer_warranty = $dom->createElement('manufacturer_warranty');
        $root->appendChild($manufacturer_warranty);
        $text = $dom->createTextNode(($this->getDataContainer()->getManufacturer_warranty())?"true":"false");
        $manufacturer_warranty->appendChild($text);
    }

    /**
     * Set feed Params
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $shop
     * @return void
     */
    protected function _setParams(DOMDocument $dom, DOMElement $shop)
    {
        $p = $this->getDataContainer()->getParams();
        if (! $p) {
            return;
        }

        foreach ($p as $c) {
            $params = $dom->createElement('param');
            $text = $dom->createTextNode($c['label']);
            $params->appendChild($text);
            $params->setAttribute('name', $c['name']);
            if (isset($c['unit'])){
                $params->setAttribute('unit', $c['unit']);
            }

            $shop->appendChild($params);
        }
        
    }

}
