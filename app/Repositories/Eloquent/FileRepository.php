<?php
declare( strict_types = 1 );

namespace App\Repositories\Eloquent;

use App\Models\File;
use App\Repositories\Interfaces\FileRepositoryInterface;

class FileRepository extends BaseRepository implements FileRepositoryInterface
{
    /**
     * FileRepository constructor.
     */
    public function __construct()
    {
        parent::__construct(File::class);
    }
}
