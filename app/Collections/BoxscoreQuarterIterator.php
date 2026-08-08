<?php

namespace App\Collections;

use Illuminate\Support\Collection;
use Iterator;

class BoxscoreQuarterIterator implements Iterator
{

    /**
     * The collection of Boxscore items
     *
     * @var BoxscoresCollection
     */
    protected BoxscoresCollection $collection;

    /**
     * The max quarter number
     *
     * @var Integer
     */
    protected mixed $lastQuarter;

    /**
     * The currently looped quarter
     *
     * @var Integer
     */
    private int $quarter;

    /**
     * BoxscoreQuarterIterator constructor.
     *
     * @param BoxscoresCollection $collection
     */
    public function __construct(BoxscoresCollection $collection)
    {
        $this->collection = $collection;
        $this->lastQuarter = $this->collection->getQuarters();
        $this->quarter = 1;
    }

    public function chunk($count): Collection
    {
        return $this->collection
            ->chunk($count)
            ->map(function($collection) {
                return new self($collection);
            });
    }

    /**
     * Rewind the Iterator to the first element
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind(): void
    {
        $this->quarter = 1;
    }

    /**
     * Return the current element
     *
     * @link http://php.net/manual/en/iterator.current.php
     * @return BoxscoresCollection Can return any type.
     * @since 5.0.0
     */
    public function current(): BoxscoresCollection
    {
        return $this->collection->quarter($this->quarter);
    }

    /**
     * Return the key of the current element
     *
     * @link http://php.net/manual/en/iterator.key.php
     * @return int scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key(): int
    {
        return $this->quarter;
    }

    /**
     * Move forward to next element
     *
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next(): void
    {
        ++$this->quarter;
    }

    /**
     * Checks if current position is valid
     *
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid(): bool
    {
        return $this->quarter <= $this->lastQuarter;
    }
}
