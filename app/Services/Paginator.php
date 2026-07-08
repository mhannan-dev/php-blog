<?php

namespace App\Services;

class Paginator
{
    private int $currentPage;
    private int $perPage;
    private int $totalItems;
    private int $totalPages;

    public function __construct(int $totalItems, int $perPage = 10, int $currentPage = 1)
    {
        $this->totalItems = $totalItems;
        $this->perPage = max(1, $perPage);
        $this->totalPages = (int) ceil($totalItems / $this->perPage);
        $this->currentPage = max(1, min($currentPage, $this->totalPages));
    }

    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    public function limit(): int
    {
        return $this->perPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function totalPages(): int
    {
        return $this->totalPages;
    }

    public function totalItems(): int
    {
        return $this->totalItems;
    }

    public function hasPrevious(): bool
    {
        return $this->currentPage > 1;
    }

    public function hasNext(): bool
    {
        return $this->currentPage < $this->totalPages;
    }

    public function previousPage(): int
    {
        return max(1, $this->currentPage - 1);
    }

    public function nextPage(): int
    {
        return min($this->totalPages, $this->currentPage + 1);
    }

    public function toArray(): array
    {
        return [
            'currentPage' => $this->currentPage,
            'perPage'     => $this->perPage,
            'totalItems'  => $this->totalItems,
            'totalPages'  => $this->totalPages,
            'hasPrevious' => $this->hasPrevious(),
            'hasNext'     => $this->hasNext(),
            'previousPage' => $this->previousPage(),
            'nextPage'    => $this->nextPage(),
            'offset'      => $this->offset(),
            'limit'       => $this->limit(),
        ];
    }

    public static function fromRequest(int $totalItems, int $perPage = 10): static
    {
        $currentPage = max(1, (int) ($_GET['page'] ?? 1));
        return new static($totalItems, $perPage, $currentPage);
    }
}
