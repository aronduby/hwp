<?php
/**
 * Created by PhpStorm.
 * User: Duby
 * Date: 8/15/2016
 * Time: 9:14 PM
 */

namespace App\Models\Recent\Render;


use App\Models\Recent;

abstract class Renderer
{

    protected mixed $data;

    protected string $view;

    protected Recent $recent;

    public function __construct($content, Recent $recent)
    {
        $this->recent = $recent;
        $this->process($content);
    }

    public function render(): string
    {
        return view($this->view, $this->data)->render();
    }

    /**
     * Go from string $content to the $this->data for the render call
     *
     * @param string $content
     * @return void
     */
    abstract public function process(string $content): void;
}
