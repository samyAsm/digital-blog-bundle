<?php
/**
 * Date: 08/02/21
 * Time: 23:19
 */

namespace Dhi\BlogBundle\Services;


use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class PaginatorService
{
    /**
     * @var PaginatorInterface
     */
    private $paginator;

    /**
     * @var RequestStack
     */
    private $request;

    private $limit;

    public function __construct(PaginatorInterface $paginator, RequestStack $request)
    {
        $this->paginator = $paginator;
        $this->request = $request;
        $this->limit = isset($_ENV['LIMIT_ITEMS_PER_PAGE'])?
            intval($_ENV['LIMIT_ITEMS_PER_PAGE']):12;
    }

    public function paginate($data)
    {
        return $this->paginator->paginate($data,
            $this->request->getCurrentRequest()->query->getInt('page', 1),
            $this->limit);
    }

    public function paginateWithInterval($data, ?int $limit = null)
    {
        $page = $this->request->getCurrentRequest()->query->getInt('page', 1);

        if ($limit > $this->limit)
            $this->limit = $limit;

        $paginated = $this->paginator->paginate($data,
            $page,
            $this->limit);

        $total = count($data);

        $current = $page==1?$paginated->count():(($page - 1)*$this->limit + count($paginated));

        $next_paginated = $this->paginator->paginate($data,
            $page + 1,
            $this->limit);

        $next_page = $next_paginated->count();

        return [$paginated, $page, $current, $total, $this->limit, $next_page];

    }

    /**
     * @return PaginatorInterface
     */
    public function getPaginator(): PaginatorInterface
    {
        return $this->paginator;
    }
}