<?php declare(strict_types=1); # -*- coding: utf-8 -*-

namespace ChriCo\Fields\ChoiceList;

/**
 * Class CallbackChoiceList
 *
 * @package ChriCo\Fields\ChoiceList
 */
class CallbackChoiceList extends ArrayChoiceList
{

    /**
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * @var callable
     */
    protected $callback;

    /**
     * ArrayChoiceList constructor.
     *
     * @param $callback $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
        $this->isLoaded = false;
        $this->choices = [];

        parent::__construct([]);
    }

    /**
     * {@inheritdoc}
     */
    public function choices(): array
    {
        $this->maybeLoadChoices();

        return parent::choices();
    }

    /**
     * Internal function to ensure, that the choices are loaded the right time.
     *
     * @return bool
     */
    private function maybeLoadChoices(): bool
    {
        if (! $this->isLoaded()) {
            $callback = $this->callback;
            $this->choices = $callback();
            $this->isLoaded = true;

            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isLoaded(): bool
    {
        return $this->isLoaded;
    }
}
