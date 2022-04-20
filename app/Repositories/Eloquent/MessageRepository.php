<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class MessageRepository extends BaseRepository implements MessageRepositoryInterface
{
    /**
     * BookRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(Message::class);
    }

    /**
     * @return Collection
     */
    public function getMessages(): Collection
    {
        return $this->model::orderBy('id', 'desc')->get();
    }

    public function clear()
    {
        $this->model::truncate();
    }

    /**
     * @param Message $message
     * @return bool
     * @throws Exception
     */
    public function destroy(Message $message): bool
    {
        return $message->delete();
    }

    public function readAllMessages(): bool
    {
        return $this->model::where('read', '!=', 1)->update([
            'read' => 1
        ]) > 0;
    }
}
