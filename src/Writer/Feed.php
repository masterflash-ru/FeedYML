<?php
/**
 */
namespace Mf\FeedYML\Writer;

use Countable;
use Iterator;
use Exception;
use DateTime;
use Mf\FeedYML\Uri;

/**
*/
class Feed implements Iterator, Countable
{
    /**
     * Contains all Feed level date to append in feed output
     *
     * @var array
     */
    protected $data = [];

    /**
     * Contains all entry objects
     *
     * @var array
     */
    protected $entries = [];

    /**
     * A pointer for the iterator to keep track of the entries array
     *
     * @var int
     */
    protected $entriesKey = 0;
    
    /**
     * Set the feed modification date
     *
     * @param null|int|DateTime
     * @throws Exception
     * @return this
     */
    public function setDateModified($date = null)
    {
        if ($date === null) {
            $date = new DateTime();
        } elseif (is_int($date)) {
            $date = new DateTime('@' . $date);
        } elseif (! $date instanceof DateTime) {
            throw new Exception('Недопустимое значение времени');
        }
        $this->data['dateModified'] = $date;

        return $this;
    }
    /**
     * Get the feed modification date
     *
     * @return string|null
     */
    public function getDateModified()
    {
        if (! array_key_exists('dateModified', $this->data)) {
            return;
        }
        return $this->data['dateModified'];
    }

    /**
     * Set the feed company
     *
     * @param string $description
     * @throws Exception
     * @return this
     */
    public function setCompany($company)
    {
        if (empty($company) || ! is_string($company)) {
            throw new Exception('Недопустимое тип, разрешается только строка');
        }
        $this->data['company'] = $company;

        return $this;
    }
    /**
     * Get the feed Company entry
     *
     * @return string|null
     */
    public function getCompany()
    {
        if (! array_key_exists('company', $this->data)) {
            return;
        }
        return $this->data['company'];
    }

    
    /**
     * Set the feed name
     *
     * @param string $description
     * @throws Exception
     * @return this
     */
    public function setName($name)
    {
        if (empty($name) || ! is_string($name)) {
            throw new Exception('Недопустимое тип, разрешается только строка');
        }
        if (mb_strlen($name)>20) {
            throw new Exception('Слишком длинное наименование, максимальный размер 20 символов');
        }
        $this->data['name'] = $name;
        return $this;
    }
    /**
     * Get the feed name entry
     *
     * @return string|null
     */
    public function getName()
    {
        if (! array_key_exists('name', $this->data)) {
            return;
        }
        return $this->data['name'];
    }

    
    /**
     * Set the feed url
     *
     * @param string $description
     * @throws Exception
     * @return this
     */
    public function setUrl($url)
    {
        if (empty($url) || ! is_string($url)) {
            throw new Exception('Недопустимое тип, разрешается только строка');
        }
        if (mb_strlen($url)>50) {
            throw new Exception('Слишком длинный URL, максимальный размер 50 символов');
        }
        if (! Uri::factory($url)->isValid() || ! Uri::factory($url)->isAbsolute()) {
                throw new Exception(
                    'Invalid parameter: параметр "url" должен быть в полном формате'
                );
        }

        $this->data['url'] = $url;

        return $this;
    }
    /**
     * Get the url name entry
     *
     * @return string|null
     */
    public function getUrl()
    {
        if (! array_key_exists('url', $this->data)) {
            return;
        }
        return $this->data['url'];
    }

    /**
     * Add a feed currency
     *
     * @param array $currency
     * @throws Exception
     * @return this
     */
    public function addCurrency(array $currency)
    {
        if (! isset($currency['id'])) {
            throw new Exception('Пропущен параметр id в секции currency');
        }
        if (! isset($currency['rate'])) {
            throw new Exception('Пропущен параметр rate в секции currency');
            }

        if (! isset($this->data['currencies'])) {
            $this->data['currencies'] = [];
        }
        $this->data['currencies'][] = $currency;

        return $this;
    }

    /**
     * Set an array of feed currencies
     *
     * @param array $currencies
     * @return this
     */
    public function addCurrencies(array $currencies)
    {
        foreach ($currencies as $currencie) {
            $this->addCurrency($currencie);
        }

        return $this;
    }
    /**
     * Get the feed Currencies
     *
     * @return array|null
     */
    public function getCurrencies()
    {
        if (! array_key_exists('currencies', $this->data)) {
            return;
        }
        return $this->data['currencies'];
    }

    /**
     * Set the feed character encoding
     *
     * @param string $encoding
     * @throws Exception\InvalidArgumentException
     * @return this
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
     * Add a feed category
     *
     * @param array $category
     * @throws Exception
     * @return this
     */
    public function addCategory(array $category)
    {
        if (! isset($category['id'])) {
            throw new Exception('Пропущен параметр id в секции category');
        }
        if (! isset($category['label'])) {
            throw new Exception('Пропущен параметр label (название категории) в секции category');
        }

        if (! isset($this->data['categories'])) {
            $this->data['categories'] = [];
        }
        $this->data['categories'][] = $category;

        return $this;
    }

    /**
     * Set an array of feed category
     *
     * @param array $categorys
     * @return this
     */
    public function setCategories(array $categories)
    {
        foreach ($categories as $category) {
            $this->addCategory($category);
        }

        return $this;
    }
    /**
     * Get the feed Categories
     *
     * @return array|null
     */
    public function getCategories()
    {
        if (! array_key_exists('categories', $this->data)) {
            return;
        }
        return $this->data['categories'];
    }

    
    
    /**
     * Creates a new Mf\FeedYML\Writer\Entry data container for use. This is NOT
     * added to the current feed automatically, but is necessary to create a
     * container with some initial values preset based on the current feed data.
     *
     * @return Mf\FeedYML\Writer\Entry
     */
    public function createEntry(array $attr)
    {
        $entry = new Entry($attr);
        if ($this->getEncoding()) {
            $entry->setEncoding($this->getEncoding());
        }
        return $entry;
    }

    /**
     * Appends a Laminas\Feed\Writer\Entry object representing a new entry/item
     * the feed data container's internal group of entries.
     *
     * @param Entry $entry
     * @return Feed
     */
    public function addEntry(Entry $entry)
    {
        $this->entries[] = $entry;
        return $this;
    }

    /**
     * Removes a specific indexed entry from the internal queue. Entries must be
     * added to a feed container in order to be indexed.
     *
     * @param int $index
     * @throws Exception
     * @return Feed
     */
    public function removeEntry($index)
    {
        if (! isset($this->entries[$index])) {
            throw new Exception('Undefined index: ' . $index . '. Entry does not exist.');
        }
        unset($this->entries[$index]);

        return $this;
    }

    /**
     * Retrieve a specific indexed entry from the internal queue. Entries must be
     * added to a feed container in order to be indexed.
     *
     * @param int $index
     * @throws Exception
     */
    public function getEntry($index = 0)
    {
        if (isset($this->entries[$index])) {
            return $this->entries[$index];
        }
        throw new Exception('Undefined index: ' . $index . '. Entry does not exist.');
    }

    /**
     * Get the number of feed entries.
     * Required by the Iterator interface.
     *
     * @return int
     */
    public function count()
    {
        return count($this->entries);
    }

    /**
     * Return the current entry
     *
     * @return Entry
     */
    public function current()
    {
        return $this->entries[$this->key()];
    }

    /**
     * Return the current feed key
     *
     * @return mixed
     */
    public function key()
    {
        return $this->entriesKey;
    }

    /**
     * Move the feed pointer forward
     *
     * @return void
     */
    public function next()
    {
        ++$this->entriesKey;
    }

    /**
     * Reset the pointer in the feed object
     *
     * @return void
     */
    public function rewind()
    {
        $this->entriesKey = 0;
    }

    /**
     * Check to see if the iterator is still valid
     *
     * @return bool
     */
    public function valid()
    {
        return 0 <= $this->entriesKey && $this->entriesKey < $this->count();
    }

    /**
     * Attempt to build and return the feed resulting from the data set
     *
     * @param  bool    $ignoreExceptions
     * @throws Exception
     * @return string
     */
    public function export($ignoreExceptions = false)
    {
        $renderer = new Renderer\Feed\Yml($this);
        if ($ignoreExceptions) {
            $renderer->ignoreExceptions();
        }
        return $renderer->render()->saveXml();
    }
}
