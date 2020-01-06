<?php
/**
 * 
 */
namespace Mf\FeedYML\Reader\Collection;

use Laminas\Feed\Reader\Collection\AbstractCollection;

class Picture extends AbstractCollection
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
        foreach ($this->getIterator() as $element) {
            $pictures[] = $element;
        }
        return array_unique($pictures);
    }
}
