<?php

namespace App\Models\Recent\Render;

use App\Models\Note as NoteModel;

class Note extends Renderer
{

    /**
     * The blade template to use
     *
     * @var string
     */
    protected string $view = 'recent.note';

    /**
     * Process the content and save to $this->data
     *
     * @param $content string
     */
    public function process(string $content): void
    {
        $ids = json_decode($content);
        $note = NoteModel::whereIn('id', $ids)->first();

        $this->data = [
            'note' => $note,
            'recent' => $this->recent
        ];
    }

}
