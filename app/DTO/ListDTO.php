<?php


namespace App\DTO;


class ListDTO
{
    /**
     * @var int
     */
    public int $currentPage;

    /**
     * @var int
     */
    public int $perPage;

    /**
     * @var int
     */
    public int $total;

    /**
     * @var int
     */
    public int $lastPage;

    /**
     * @var string
     */
    public string $orderBy;

    /**
     * @var array
     */
    public array $list;

    /**
     * ListDTO constructor.
     * @param int $currentPage
     * @param int $perPage
     * @param int $total
     * @param int $lastPage
     * @param string $orderBy
     * @param array $list
     */
    public function __construct(int $currentPage, int $perPage, int $total, int $lastPage, string $orderBy, array $list)
    {
        $this->currentPage = $currentPage;
        $this->perPage = $perPage;
        $this->total = $total;
        $this->lastPage = $lastPage;
        $this->orderBy = $orderBy;
        $this->list = $list;
    }
}
