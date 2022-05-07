<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Request;

interface MessageRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getMessages(): Collection;

    /**
     * @param Request $request
     * @return int
     */
    public function destroy(Request $request): int;

    /**
     * @return bool
     */
    public function readAllMessages(): bool;
}
