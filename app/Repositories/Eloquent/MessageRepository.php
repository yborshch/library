<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\Models\Message;
use App\Repositories\Interfaces\MessageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Symfony\Component\HttpFoundation\Request;

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
     * @param Request $request
     * @return int
     */
    public function destroy(Request $request): int
    {
        return $this->model::where('book_id', $request->get('id'))->delete();
    }

    public function readAllMessages(): bool
    {
        return $this->model::where('read', '!=', 1)->update([
            'read' => 1
        ]) > 0;
    }
}
