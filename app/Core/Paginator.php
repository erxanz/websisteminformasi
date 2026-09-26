<?php

declare(strict_types=1);

namespace App\Core;

/**
 * Menghitung halaman, offset, dan URL untuk daftar yang dipaginasi.
 */
final class Paginator
{
    private int $total;
    private int $perPage;
    private int $currentPage;
    private int $lastPage;

    /** @var array<string, scalar> */
    private array $query;

    /** @param array<string, scalar> $query Parameter lain yang harus dipertahankan di URL. */
    public function __construct(int $total, int $perPage, int $currentPage, array $query = [])
    {
        $this->perPage = max(1, $perPage);
        $this->total   = max(0, $total);
        $this->lastPage = max(1, (int) ceil($this->total / $this->perPage));

        // Halaman di luar rentang otomatis dijepit ke halaman valid terdekat.
        $this->currentPage = min(max(1, $currentPage), $this->lastPage);

        unset($query['page']);
        /** @var array<string, scalar> $query */
        $this->query = $query;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    public function currentPage(): int
    {
        return $this->currentPage;
    }

    public function lastPage(): int
    {
        return $this->lastPage;
    }

    public function offset(): int
    {
        return ($this->currentPage - 1) * $this->perPage;
    }

    /** Nomor baris pertama pada halaman ini (0 bila tidak ada data). */
    public function from(): int
    {
        return $this->total === 0 ? 0 : $this->offset() + 1;
    }

    /** Nomor baris terakhir pada halaman ini. */
    public function to(): int
    {
        return min($this->offset() + $this->perPage, $this->total);
    }

    public function hasPages(): bool
    {
        return $this->lastPage > 1;
    }

    public function url(int $page): string
    {
        $params         = $this->query;
        $params['page'] = $page;

        return '/?' . http_build_query($params);
    }

    /**
     * Daftar nomor halaman untuk ditampilkan, dengan null sebagai penanda elipsis.
     *
     * @return array<int, int|null>
     */
    public function window(int $onEachSide = 2): array
    {
        if ($this->lastPage <= 7) {
            return range(1, $this->lastPage);
        }

        $pages = [1];

        if ($this->currentPage - $onEachSide > 2) {
            $pages[] = null;
        }

        $start = max(2, $this->currentPage - $onEachSide);
        $end   = min($this->lastPage - 1, $this->currentPage + $onEachSide);

        for ($page = $start; $page <= $end; $page++) {
            $pages[] = $page;
        }

        if ($this->currentPage + $onEachSide < $this->lastPage - 1) {
            $pages[] = null;
        }

        $pages[] = $this->lastPage;

        return $pages;
    }
}
