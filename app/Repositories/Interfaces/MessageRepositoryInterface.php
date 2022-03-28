<?php
declare( strict_types = 1 );

namespace App\Repositories\Interfaces;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;

interface MessageRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * @return Collection
     */
    public function getMessages(): Collection;

    /**
     * @param Message $message
     * @return bool
     */
    public function destroy(Message $message): bool;
}
