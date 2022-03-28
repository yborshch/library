<?php

namespace App\Services\Import\FileType;

use App\Traits\FileNameGenerate;
use App\ValueObject\Book;
use App\ValueObject\File;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
use Mpdf\MpdfException;

class PDF implements FileTypeInterface
{
    use FileNameGenerate;

    const EXTENSION = 'pdf';

    /**
     * @var string
     */
    public string $filename;

    public function __construct()
    {
        $this->filename = $this->getFileName();
    }

    /**
     * @throws MpdfException
     */
    public function save(Book &$book): bool
    {
        $mpdf = new Mpdf([
            'default_font_size' => 10,
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);

        $mpdf->setFooter('{PAGENO}');
        $mpdf->SetHTMLFooter('<div style="text-align: center">- {PAGENO} -</div>');

        foreach ($book->context as $index => $page) {
            if ($index === 0) {
                $mpdf->WriteHTML('<p style="align-content: center; font-size: 16px; font-style: oblique; vertical-align: center">' . $book->title . '</p><hr>');
            }

            foreach ($page as $line) {
                $mpdf->WriteHTML($line);
            }
        }

        $book->pages = $mpdf->page;

        try {
            $path = public_path(sprintf("/storage/%s.pdf", $this->filename));
            $mpdf->Output($path,'F');
            $book->file = new File();
            $book->file->filename = $this->filename;
            $book->file->extension = self::EXTENSION;
            $book->file->size = filesize($path);

            return true;
        } catch (\Exception $e) {
            Log::critical(__METHOD__, [__LINE__ => $e->getMessage()]);
        }

        return false;
    }
}
