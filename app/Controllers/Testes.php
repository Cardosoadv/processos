<?php

namespace App\Controllers;

use ZipArchive;
use SimpleXMLElement;
use CodeIgniter\Exceptions\PageNotFoundException;

class Testes extends BaseController
{
    protected $zip;
    protected $bookPath;
    protected $bookRoot;
    protected $filesIds = [];
    protected $fileLocations = [];
    protected $fileTypes = [];
    protected $toc;
    protected $bookInfo;
    protected $chapters = [];
    protected $chaptersId = [];
    protected $bookTitle;
    protected $bookAuthor;
    protected $bookDescription;
    protected $cover;

    public function __construct()
    {
        $this->zip = new ZipArchive();
        $this->bookPath = ROOTPATH . "public/epub-reader/books/KJV.epub";

        if (!file_exists($this->bookPath)) {
            throw PageNotFoundException::forPageNotFound("Arquivo EPUB não encontrado: " . $this->bookPath);
        }
        if (!$this->zip->open($this->bookPath)) {
            throw new \RuntimeException('Falha ao abrir o arquivo EPUB: ' . $this->bookPath);
        }
        $this->parseEpub();
    }

    public function __destruct()
    {
        if ($this->zip) {
            $this->zip->close();
        }
    }

    public function index()
    {
        $apiUrl = 'https://comunicaapi.pje.jus.br/api/v1/comunicacao';
        $params = [
            'numeroOab' => '164136',
            'ufOab' => 'mg'
        ];
        $query = http_build_query($params);
        $apiUrl .= '?' . $query;
        $data['apiUrl'] = $apiUrl;
        // Iniciando a sessão cURL
        return view('receberintimacoesjs', $data);
    }

    public function processarIntimacoes()
    {
        $json = $this->request->getPost('json');
        $data = json_decode($json, true);
        $data['success'] = true; // Simulate a successful response
        $data['message'] = 'Intimações recebidas com sucesso!';
        return json_encode($data);
    }

    public function index2()
    {
        // Example usage: Display the first chapter
        $showPage = $this->request->getGet('showPage');
        $page = $this->getPage($showPage);
        $page['showPage'] = $showPage;
        return view('epub_page', $page); // Create a view for displaying the page
    }

    protected function parsePage($xhtml, $chapterDir, $chapterOrder = 0)
    {
        // Encontra o início da tag <head>
        $headStart = strpos($xhtml, "<head>");
        if ($headStart === false) {
            $headStart = 0; // Se não houver <head>, começa do início do arquivo
            $head = '<meta charset="UTF-8">'; // Define um charset padrão
        } else {
            $headStart += 6; // Avança para depois do ">" de <head>
            $headEnd = strpos($xhtml, "</head>", $headStart);
            if ($headEnd === false) $headEnd = strlen($xhtml); //trata caso de head mal formado
            $head = substr($xhtml, $headStart, ($headEnd - $headStart));
            $head = trim(preg_replace('/\s+/', ' ', $head)); // Remove espaços em branco extras
        }


        // Garante que haja uma declaração de charset
        if (!preg_match('/\<meta.*?charset=.*?([^"\']+)/siU', $head)) {
            $head = '<meta charset="UTF-8">' . "\n" . $head; // Adiciona meta charset UTF-8
        }

        // Extrai o título da página
        preg_match("/\<title\>(.*)\<\/title\>/siU", $head, $title);
        $pageTitle = isset($title[1]) ? trim($title[1]) : null;

        // Define o título da página caso não exista
        if (empty($pageTitle)) {
            $pageTitle = 'Chapter ' . $chapterOrder . ":|:" . $this->bookTitle;
            $head = preg_replace('/<title>(.*)<\/title>/i', '<title>' . $pageTitle . '</title>', $head);
        }

        // Remove links para templates de página Adobe (não são necessários para renderização básica)
        $head = preg_replace('/<link\s[^>]*type\s*=\s*"application\/vnd.adobe-page-template\+xml\"[^>]*\/>/i', '', $head);

        // Encontra o conteúdo dentro da tag <body>
        $bodyStart = strpos($xhtml, "<body");
        if ($bodyStart === false) {
            $bodyStart = 0;
        } else {
            $bodyStart = strpos($xhtml, ">", $bodyStart) + 1;
        }
        $bodyEnd = strpos($xhtml, "</body", $bodyStart);
        if ($bodyEnd === false) $bodyEnd = strlen($xhtml); //trata body mal formado

        $content = substr($xhtml, $bodyStart, ($bodyEnd - $bodyStart));

        return [
            'chapter_dir' => $chapterDir,
            'title' => $pageTitle,
            'head' => $head,
            'content' => $content,
            'book' => $this->bookTitle,
            'percentage' => null
        ];
    }

    protected function getPage($chapterOrder)
    {
        if (isset($this->chapters[$chapterOrder])) {
            $chapterData = $this->chapters[$chapterOrder];
            $chapter = $this->zip->getFromName($chapterData["content"]);

            if ($chapter === false) {
                throw new \RuntimeException("Failed to get chapter content: " . $chapterData["content"]);
            }

            $page = $this->parsePage($chapter, $chapterData['dir'], $chapterOrder);
            return array_merge($page, [
                'file' => $chapterData["content"], // Use the already constructed path
                'percentage' => number_format((($chapterOrder + 1) / count($this->chapters)) * 100, 2, '.', '')
            ]);
        }
        return [ // Simplified 404 return
            'title' => '404 - Page Not Found',
            'head' => '<title>404</title><meta charset="UTF-8">',
            'content' => '<div style="text-align: center;"><p>404 Page Not Found</p></div>',
            'book' => $this->bookTitle,
            'percentage' => 0
        ];
    }

    protected function parseEpub()
    {
        $container = $this->zip->getFromName('META-INF/container.xml');
        if ($container === false) {
            throw new \RuntimeException('META-INF/container.xml not found.');
        }

        $xContainer = new SimpleXMLElement($container);
        $opfPath = (string)$xContainer->rootfiles->rootfile['full-path'];
        $opf = $this->zip->getFromName($opfPath);
        if ($opf === false) {
            throw new \RuntimeException("OPF file not found: " . $opfPath);
        }
        $opf = new SimpleXMLElement($opf);
        $this->bookRoot = dirname($opfPath) . "/";
        $this->bookTitle = (string)$opf->metadata->children('dc', true)->title;
        $this->bookAuthor = (string)$opf->metadata->children('dc', true)->creator;
        $this->bookDescription = (string)$opf->metadata->children('dc', true)->description;

        $manifest = $opf->manifest;
        foreach ($manifest->item as $item) {
            $id = (string)$item["id"];
            $href = (string)$item['href'];
            $media = (string)$item['media-type'];
            $properties = (string)$item->attributes()->properties;
            $this->filesIds[$id] = $href;
            $this->fileLocations[$href] = $id;
            $this->fileTypes[$id] = $media;

            if (strpos($media, 'image/') === 0 && $properties === 'cover-image') {
                $this->cover = $this->bookRoot . $href; // Store the full path
            }
        }

        $chapterNum = 1;
        foreach ($opf->spine->itemref as $itemref) {
            $id = (string)$itemref->attributes()->idref;
            if (isset($this->fileTypes[$id]) && $this->fileTypes[$id] === "application/xhtml+xml") {
                $filePath = $this->bookRoot . $this->filesIds[$id];
                if ($this->zip->locateName($filePath) !== false) { // Check if the file exists in the zip
                    $this->chaptersId[$this->filesIds[$id]] = $chapterNum++;
                    $this->chapters[] = [
                        'dir' => dirname($this->filesIds[$id]),
                        'content' => $filePath, // Store the full path
                        'itemref' => $id
                    ];
                }
            }
        }

        // TOC parsing (simplified for now - needs further improvement if you need complex TOC handling)
        $navId = null;
        foreach ($manifest->item as $item) {
            if ((string)$item->attributes()->properties === 'nav') {
                $navId = (string)$item['id'];
                break;
            }
        }
        if ($navId) {
            $navPath = $this->bookRoot . $this->filesIds[$navId];
            $navContent = $this->zip->getFromName($navPath);
            if ($navContent !== false) {
                $this->toc = $navContent; // Store the raw TOC content for now
            }
        }
    }
}
