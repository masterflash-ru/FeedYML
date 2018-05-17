<?php
/**
 * 
 */
namespace Mf\FeedYML\Reader\Collection;

use Zend\Feed\Reader\Collection\AbstractCollection;

class Offer extends AbstractCollection
{
    /**
     * Return a simple array of the most relevant slice of
     * the author values, i.e. all author names.
     *
     * @return array
     */
    public function getValues()
    {
        $offer = [];
        foreach ($this->getIterator() as $element) {
            $offer[] = $element;
        }
        return array_unique($offer);
    }
}
