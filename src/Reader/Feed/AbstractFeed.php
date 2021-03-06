<?php
/**
 */

namespace Mf\FeedYML\Reader\Feed;

use DOMDocument;
use DOMElement;
use DOMXPath;
use Mf\FeedYML\Reader\Reader;


/**
*/
abstract class AbstractFeed implements FeedInterface
{
    /**
     * Parsed feed data
     *
     * @var array
     */
    protected $data = [];

    /**
     * Parsed feed data in the shape of a DOMDocument
     *
     * @var DOMDocument
     */
    protected $domDocument = null;

    /**
     * An array of parsed feed entries
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
     * The base XPath query used to retrieve feed data
     *
     * @var DOMXPath
     */
    protected $xpath = null;

    /**
     * Array of loaded extensions
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Original Source URI (set if imported from a URI)
     *
     * @var string
     */
    protected $originalSourceUri = null;

    /**
     * Constructor
     *
     * @param DOMDocument $domDocument The DOM object for the feed's XML
     * @param string $type Feed type
     */
    public function __construct(DOMDocument $domDocument, $type = null)
    {
        $this->domDocument = $domDocument;
        $this->xpath = new DOMXPath($this->domDocument);

        if ($type !== null) {
            $this->data['type'] = $type;
        } else {
            $this->data['type'] = Reader\Reader::detectType($this->domDocument);
        }
        $this->indexEntries();
    }

    /**
     * Set an original source URI for the feed being parsed. This value
     * is returned from getFeedLink() method if the feed does not carry
     * a self-referencing URI.
     *
     * @param string $uri
     */
    public function setOriginalSourceUri($uri)
    {
        $this->originalSourceUri = $uri;
    }

    /**
     * Get an original source URI for the feed being parsed. Returns null if
     * unset or the feed was not imported from a URI.
     *
     * @return string|null
     */
    public function getOriginalSourceUri()
    {
        return $this->originalSourceUri;
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
     */
    public function current()
    {
    }

    /**
     * Get the DOM
     *
     * @return DOMDocument
     */
    public function getDomDocument()
    {
        return $this->domDocument;
    }

    /**
     * Get the Feed's encoding
     *
     * @return string
     */
    public function getEncoding()
    {
        $assumed = $this->getDomDocument()->encoding;
        if (empty($assumed)) {
            $assumed = 'UTF-8';
        }
        return $assumed;
    }

    /**
     * Get feed as xml
     *
     * @return string
     */
    public function saveXml()
    {
        return $this->getDomDocument()->saveXML();
    }

    /**
     * Get the DOMElement representing the items/feed element
     *
     * @return DOMElement
     */
    public function getElement()
    {
        return $this->getDomDocument()->documentElement;
    }

    /**
     * Get the DOMXPath object for this feed
     *
     * @return DOMXPath
     */
    public function getXpath()
    {
        return $this->xpath;
    }

    /**
     * Get the feed type
     *
     * @return string
     */
    public function getType()
    {
        return $this->data['type'];
    }

    /**
     * Return the current feed key
     *
     * @return int
     */
    public function key()
    {
        return $this->entriesKey;
    }

    /**
     * Move the feed pointer forward
     *
     */
    public function next()
    {
        ++$this->entriesKey;
    }

    /**
     * Reset the pointer in the feed object
     *
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
     * Read all entries to the internal entries array
     *
     */
    abstract protected function indexEntries();

}
