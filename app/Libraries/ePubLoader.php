<?php

namespace App\Libraries;

use Exception;
use ZipArchive;

class EpubLoader
{

        public $book;
        public $bookRoot;
        public $zip;
        public $zip_open = false;

        public function __construct()
        {
                $this->zip = new ZipArchive;
        }

        public function Loader()
        {

                $file = htmlentities();
        }

        public function parseEpub()
        {
                if (!@$this->zip->open($this->book)) {
                        throw new \Exception('Failed to read epub file');
                }
                $this->zip_open = true;
                $mimetype = $this->zip->getFromName('mimetype');
                if ($mimetype === false) {
                        throw new \Exception('Failed to access epub mimetype file');
                }
                $toc = array();
                $chapterDir = NULL;

                if ($mimetype === 'application/epub+zip') {
                        $tidy_config = array(
                                'output-xml' => true,
                                'input-xml' => true
                        );

                        $container = $this->zip->getFromName("META-INF/container.xml");

                        $xContainer = new \SimpleXMLElement(tidy_repair_string($container, $tidy_config));
                        $opfPath = $xContainer->rootfiles->rootfile['full-path'];
                        $opfType = $xContainer->rootfiles->rootfile['media-type'];
                        $this->bookRoot = dirname($opfPath) . "/";
                        if ($this->bookRoot === "./") {
                                $this->bookRoot = "";
                        }
                        if ($this->zip->locateName($opfPath) === false) {
                                throw new Exception('Book' . $this->book . ' does not have a proper OPF file');
                        }

                        $opf = new \SimpleXMLElement(tidy_repair_string($this->zip->getFromName($opfPath), $tidy_config));
                        $book_title = $opf->metadata->children('dc', true)->title;
                        $book_author = $opf->metadata->children('dc', true)->creator;
                        $book_description = $opf->metadata->children('dc', true)->description;
                        $epub_version = (string)$opf->attributes()->version;

                        $manifest = $opf->manifest;
                        foreach ($manifest->item as $item) {
                                $href = (string)$item['href'];
                                if ($this->zip->locateName($this->bookRoot . $href) !== false) {
                                        $id = (string)$item["id"];
                                        $media = (string)$item['media-type'];
                                        $properties = (string)$item->attributes()->properties;
                                        $chapterDir = dirname($href);

                                        $this->filesIds[$id] = $href;
                                        $this->fileLocations[$href] = $id;
                                        $this->fileTypes[$id] = $media;
                                        if ($media == "text/css") {
                                                $this->css[$href] = $chapterDir;
                                        }
                                        if (strpos($media, 'image/') === 0) {
                                                $this->images[$id] = $href;
                                                if ($properties == 'cover-image') {
                                                        $this->cover = rawurlencode($href);
                                                }
                                        }
                                        if ($media == "application/xhtml+xml") {
                                                if ($properties == 'nav') {
                                                        $toc = array(
                                                                'dir' => $chapterDir,
                                                                'id' => $id,
                                                                'ncx' => false,
                                                        );
                                                }
                                        }

                                        if (!$toc && $media == 'application/x-dtbncx+xml') {
                                                $toc = array(
                                                        'dir' => $chapterDir,
                                                        'id' => $id,
                                                        'ncx' => true,
                                                );
                                        }
                                }
                        }

                        $chapterNum = 1;
                        foreach ($opf->spine->itemref as $order => $itemref) {
                                $id = (string) $itemref->attributes()->idref;
                                if (isset($this->fileTypes[$id]) && $this->fileTypes[$id] == "application/xhtml+xml") {
                                        $this->chaptersId[$this->filesIds[$id]] = $chapterNum++;
                                        $chapterDir = dirname($this->filesIds[$id]);
                                        $this->chapters[] = array(
                                                'dir' => $chapterDir,
                                                'content' => $this->bookRoot . $this->filesIds[$id],
                                                'itemref' => $id
                                        );
                                }
                        }

                        if ($toc) {
                                $id = $toc['id'];
                                if ($toc['ncx']) {
                                        $xNcx = new \SimpleXMLElement(tidy_repair_string($this->zip->getFromName($this->bookRoot . $this->filesIds[$id]), $tidy_config));
                                        $this->toc = $this->updateLinks($this->makeNCXNav($xNcx->navMap), $chapterDir, $this->chaptersId);
                                } else {
                                        $xhtml = $this->updateLinks($this->zip->getFromName($this->bookRoot . $this->filesIds[$id]), dirname($this->filesIds[$id]), $this->chaptersId);
                                        $start = strpos($xhtml, "<body");
                                        $start = strpos($xhtml, ">", $start) + 1;
                                        $end = strpos($xhtml, "</body", $start);
                                        $content =  substr($xhtml, $start, ($end - $start));
                                        $this->toc = $content;
                                }
                        } else {
                                $this->toc = $this->updateLinks($this->makeSpineNav($opf->spine), $chapterDir, $this->chaptersId);
                        }

                        if (!$this->cover) {
                                foreach ($opf->metadata->meta as $key => $meta) {
                                        $attributes = $meta->attributes();
                                        if ((isset($attributes['name']) && strval($attributes['name']) == 'cover') && isset($attributes['content'])) {
                                                $cover_id = strval($attributes['content']);
                                                if (isset($this->filesIds[$cover_id])) {
                                                        $this->cover = rawurlencode($this->filesIds[$cover_id]);
                                                }
                                        }
                                }
                        }

                        $this->bookInfo();
                        return TRUE;
                }
                return FALSE;
        }
}
