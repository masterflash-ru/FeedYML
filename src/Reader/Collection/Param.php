<?php
/**
 * 
 */
namespace Mf\FeedYML\Reader\Collection;

use Zend\Feed\Reader\Collection\AbstractCollection;

class Param extends AbstractCollection
{
    /**
     * Return a simple array of the most relevant slice of
     * the author values, i.e. all author names.
     *
     * @return array
     */
    public function getValues()
    {
        $pictures = [];
        foreach ($this->getIterator() as $k=>$element) {
            $pictures[$k] = $element;
        }
        return array_unique($pictures);
    }
}
