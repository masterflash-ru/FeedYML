<?php
/**
 */

namespace Mf\FeedYML\Writer\Renderer\Feed;

use DateTime;
use DOMDocument;
use DOMElement;
use Laminas\Feed\Uri;
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
     * @param  Writer\Feed $container
     */
    public function __construct(Writer\Feed $container)
    {
        parent::__construct($container);
    }

    /**
     * Render YML feed
     *
     * @return self
     */
    public function render()
    {
        $this->dom = new DOMDocument('1.0', $this->container->getEncoding());
        $this->dom->formatOutput = true;
        $this->dom->substituteEntities = false;
        $yml_catalog = $this->dom->createElement('yml_catalog');
        $this->_setDateModified($yml_catalog);
        $this->setRootElement($yml_catalog);
        
        $shop = $this->dom->createElement('shop');
        
        $this->_setName($this->dom,$shop);
        $this->_setCompany($this->dom,$shop);
        $this->_setUrl($this->dom,$shop);
        
        $this->_setCurrencies($this->dom, $shop);
        $this->_setCategories($this->dom, $shop);
        
        
        
        
        
        $yml_catalog->appendChild($shop);
        $this->dom->appendChild($yml_catalog);
        $offers = $this->dom->createElement('offers');
        $yml_catalog->appendChild($offers);

        foreach ($this->container as $entry) {
            if ($this->getDataContainer()->getEncoding()) {
                $entry->setEncoding($this->getDataContainer()->getEncoding());
            }
            if ($entry instanceof Writer\Entry) {
                $renderer = new Renderer\Entry\Yml($entry);
            } else {
                continue;
            }
            if ($this->ignoreExceptions === true) {
                $renderer->ignoreExceptions();
            }
            $renderer->setRootElement($this->dom->documentElement);
            $renderer->render();
            $element = $renderer->getElement();
            $deep = version_compare(PHP_VERSION, '7', 'ge') ? 1 : true;
            $imported = $this->dom->importNode($element, $deep);
            $offers->appendChild($imported);
        }
        return $this;
    }

    /**
     * Set feed name
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $shop
     * @return void
     */
    protected function _setName(DOMDocument $dom, DOMElement $shop)
    {
        if ($this->getDataContainer()->getName()) {
            $name = $dom->createElement('name');
            
            $text = $dom->createTextNode($this->getDataContainer()->getName());
            $name->appendChild($text);
            $shop->appendChild($name);
        } else {
            throw new Exception('Пропущен обязательный элемент name в секции shop');
        }
    }
    /**
     * Set feed company
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $shop
     * @return void
     */
    protected function _setCompany(DOMDocument $dom, DOMElement $shop)
    {
        if ($this->getDataContainer()->getCompany()) {
            $name = $dom->createElement('company');
            
            $text = $dom->createTextNode($this->getDataContainer()->getCompany());
            $name->appendChild($text);
            $shop->appendChild($name);
        } else {
            throw new Exception('Пропущен обязательный элемент company в секции shop');
        }
    }
    /**
     * Set feed url
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $shop
     * @return void
     */
    protected function _setUrl(DOMDocument $dom, DOMElement $shop)
    {
        if ($this->getDataContainer()->getUrl()) {
            $name = $dom->createElement('url');
            
            $text = $dom->createTextNode($this->getDataContainer()->getUrl());
            $name->appendChild($text);
            $shop->appendChild($name);
        }
    }


    /**
     * Set date feed was last modified
     *@param  DOMElement $element
     * @return void
     */
    // @codingStandardsIgnoreStart
    protected function _setDateModified(DOMElement $element)
    {
        // @codingStandardsIgnoreEnd
        if (! $this->getDataContainer()->getDateModified()) {
            return;
        }
        $element->setAttribute("date",$this->getDataContainer()->getDateModified()->format("Y-m-d H:m"));

    }


    /**
     * Set feed Currencies
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $shop
     * @return void
     */
    protected function _setCurrencies(DOMDocument $dom, DOMElement $shop)
    {
        $currencies = $this->getDataContainer()->getCurrencies();
        if (! $currencies) {
           throw new Exception('Пропущен обязательный элемент currencies в секции shop');
        }
        $currencys = $dom->createElement('currencies');
        foreach ($currencies as $c) {
            $currency = $dom->createElement('currency');
            
            $currency->setAttribute('id', $c['id']);
            $currency->setAttribute('rate', $c['rate']);

            $currencys->appendChild($currency);
        }
        $shop->appendChild($currencys);
    }
    /**
     * Set feed categorys
     *
     * @param  DOMDocument $dom
     * @param  DOMElement $shop
     * @return void
     */
    protected function _setCategories(DOMDocument $dom, DOMElement $shop)
    {
        $cats = $this->getDataContainer()->getCategories();
        if (! $cats) {
            throw new Exception('Пропущен обязательный элемент categories в секции shop');
        }
        $categories = $dom->createElement('categories');
        foreach ($cats as $c) {
            $category = $dom->createElement('category');
            
            $category->setAttribute('id', $c['id']);
            if (!empty($c['parentId'])){
                $category->setAttribute('parentId', $c['parentId']);
            }
            $text = $dom->createTextNode($c['label']);
            $category->appendChild($text);

            $categories->appendChild($category);
        }
        $shop->appendChild($categories);
    }
}
