<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Mf\FeedYML\Reader\Feed;

use DateTime;
use DOMDocument;
use Mf\FeedYML\Reader;
use Zend\Feed\Reader\Collection;
use Exception;
use Zend\Feed\Reader\Feed\AbstractFeed;

/**
*/
class Yml extends AbstractFeed
{
    /**
     * Constructor
     *
     * @param  DOMDocument $dom
     * @param  string $type
     */
    public function __construct(DOMDocument $dom, $type = null)
    {
        parent::__construct($dom, $type);
    }

    /**
     * Get a single author
     *
     * @param  int $index
     * @return string|null
     */
    public function getAuthor($index = 0)
    {
        return null;
    }

    /**
     * Get an array with feed authors
     *
     * @return array
     */
    public function getAuthors()
    {
        return [];
    }

    /**
     * Get the copyright entry
     *
     * @return string|null
     */
    public function getCopyright()
    {
        return "";
    }

    /**
     * Get the feed creation date
     *
     * @return DateTime|null
     */
    public function getDateCreated()
    {
        return $this->getDateModified();
    }

    /**
     * Get the feed modification date
     *
     * @return DateTime
     * @throws Exception\RuntimeException
     */
    public function getDateModified()
    {
        if (array_key_exists('datemodified', $this->data)) {
            return $this->data['datemodified'];
        }

        $date = null;

        if ($this->getType() == Reader\Reader::TYPE_YML) {
            $dateModified = $this->xpath->evaluate('string(/yml_catalog/@date)');
            if ($dateModified) {
                $dateModifiedParsed = strtotime($dateModified);
                if ($dateModifiedParsed) {
                    $date = new DateTime('@' . $dateModifiedParsed);
                } else {
                    $dateStandards = ["Y-m-d H:i","Y-m-d H:i:s"];
                    foreach ($dateStandards as $standard) {
                        try {
                            $date = DateTime::createFromFormat($standard, $dateModified);
                            break;
                        } catch (\Exception $e) {
                            if ($standard === null) {
                                throw new Exception(
                                    'Could not load date due to unrecognised'
                                    .' format (should follow RFC 822 or 2822):'
                                    . $e->getMessage(),
                                    0,
                                    $e
                                );
                            }
                        }
                    }
                }
            }
        }
        $this->data['datemodified'] = $date;

        return $this->data['datemodified'];
    }


    /**
     * Get the feed description
     *
     * @return string|null
     */
    public function getDescription()
    {
        if (array_key_exists('name', $this->data)) {
            return $this->data['name'];
        }

        if ($this->getType() == Reader\Reader::TYPE_YML) {
            $name = $this->xpath->evaluate('string(/yml_catalog/shop/name)');
        }

        if (! $name) {
            $name = null;
        }

        $this->data['name'] = $name;

        return $this->data['name'];
    }
    
    /**
     * Get the feed company
     *
     * @return string|null
     */
    public function getCompany()
    {
        if (array_key_exists('company', $this->data)) {
            return $this->data['company'];
        }

        if ($this->getType() == Reader\Reader::TYPE_YML) {
            $company = $this->xpath->evaluate('string(/yml_catalog/shop/company)');
        }

        if (! $company) {
            $company = null;
        }

        $this->data['company'] = $company;

        return $this->data['company'];
    }

    /**
     * Get the feed ID
     *
     * @return string|null
     */
    public function getId()
    {
        return null;
    }


    /**
     * Get the feed language
     *
     * @return string|null
     */
    public function getLanguage()
    {
        return null;
    }

    /**
     * Get a link to the feed
     *
     * @return string|null
     */
    public function getLink()
    {
        if (array_key_exists('link', $this->data)) {
            return $this->data['link'];
        }

        if ($this->getType() == Reader\Reader::TYPE_YML) {
            $link = $this->xpath->evaluate('string(/yml_catalog/shop/url)');
        }

        if (! $link) {
            $link = null;
        }

        $this->data['link'] = $link;

        return $this->data['link'];
    }

    /**
     * Get a link to the feed XML
     *
     * @return string|null
     */
    public function getFeedLink()
    {
        return $this->getLink();
    }

    /**
     * Get the feed generator entry
     *
     * @return string|null
     */
    public function getGenerator()
    {
        return null;
    }

    /**
     * Get the feed title
     *
     * @return string|null
     */
    public function getTitle()
    {
        return null;
    }

    /**
     * Get all currencies
     *
     * @return Reader\Collection\Category
     */
    public function getCurrencies()
    {
        if (array_key_exists('currencies', $this->data)) {
            return $this->data['currencies'];
        }

        if ($this->getType() == Reader\Reader::TYPE_YML) {
            $list = $this->xpath->query('/yml_catalog/shop/currencies/*');
        }
        if ($list->length) {
            $currenciesCollection = new Collection\Category;
            foreach ($list as $currencies) {
                $currenciesCollection[] = [
                    'id' => $currencies->getAttribute('id'),
                    'rate' => $currencies->getAttribute('rate'),
                ];
            }
        } 

        $this->data['currencies'] = $currenciesCollection;

        return $this->data['currencies'];
    }

    /**
     * Get all categories
     *
     * @return Reader\Collection\Category
     */
    public function getCategories()
    {
        if (array_key_exists('categories', $this->data)) {
            return $this->data['categories'];
        }

        if ($this->getType() == Reader\Reader::TYPE_YML) {
            $list = $this->xpath->query('/yml_catalog/shop/categories/*');
        }
        if ($list->length) {
            $categoryCollection = new Collection\Category;
            foreach ($list as $category) {
                $categoryCollection[] = [
                    'id' => $category->getAttribute('id'),
                    'parentId' => $category->getAttribute('parentId'),
                    'label' => $category->nodeValue,
                ];
            }
        } 

        $this->data['categories'] = $categoryCollection;

        return $this->data['categories'];
    }

    /**
     * Read all entries to the internal entries array
     *
     */
    protected function indexEntries()
    {
        if ($this->getType() == Reader\Reader::TYPE_YML) {
            $entries = $this->xpath->evaluate('//offer');
        }

        foreach ($entries as $index => $entry) {
            $this->entries[$index] = $entry;
        }

    }

    /**
     * Register the default namespaces for the current feed format
     *
     */
    protected function registerNamespaces()
    {
    }
    /**
     * Return the current entry
     *
     * @return \Zend\Feed\Reader\Entry\EntryInterface
     */
    public function current()
    {
        $reader = new Reader\Entry\Yml($this->entries[$this->key()], $this->key(), $this->getType());

        $reader->setXpath($this->xpath);

        return $reader;
    }

}
