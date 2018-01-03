<?php

namespace romanzipp\Twitch\Traits\Helpers;

use romanzipp\Twitch\Helpers\Paginator;

trait PaginatorTrait
{
    /**
     * Marge Paginator from current request
     * @param  array          &$parameters  Request parameters
     * @param  Paginator|null $paginator    Paginator object
     * @return [type]                       [description]
     */
    public function mergePaginator(array &$parameters, Paginator $paginator = null)
    {
        if ($paginator) {

            if ($paginator->after) {
                $parameters['after'] = $paginator->after;
            }

            if ($paginator->before) {
                $parameters['before'] = $paginator->before;
            }

            if ($paginator->first) {
                $parameters['first'] = $paginator->first;
            }
        }
    }
}
