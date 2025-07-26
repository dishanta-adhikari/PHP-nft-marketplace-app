<?php

namespace App\Helpers;

class Paginator
{
    public array $data;
    public int $total;
    public int $pages;
    public int $page;
    public int $limit;

    public function __construct(array $data, int $total, int $page, int $limit)
    {
        $this->data  = $data;
        $this->total = $total;
        $this->page  = max(1, $page);
        $this->limit = $limit;
        $this->pages = (int) ceil($total / $limit);
    }
}
