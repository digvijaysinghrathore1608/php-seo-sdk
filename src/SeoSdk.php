<?php

namespace Apni\SeoSdk;

class SeoSdk
{
    protected array $data = [];

    public function set(array $data): static
    {
        $this->data = $data;
        return $this;
    }

    public function renderHead(): string
    {
        $html = "";

        if (!empty($this->data['title'])) {
            $html .= "<title>{$this->data['title']}</title>\n";
        }
        if (!empty($this->data['author'])) {
            $html .= "<meta name='author' content='{$this->data['author']}'>\n";
        }
        if (!empty($this->data['site'])) {
            $html .= "<meta property='og:site_name' content='{$this->data['site']}'>\n";
        }

        return $html;
    }
}
