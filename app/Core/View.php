<?php
namespace App\Core;

/**
 * Simple PHP view renderer
 */
class View
{
    public static function render(string $view, array $data = [], ?string $layout = 'layouts/admin'): void
    {
        $viewsPath = dirname(__DIR__, 2) . '/resources/views/';
        $file = $viewsPath . str_replace('.', '/', $view) . '.php';

        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: $view");
        }

        extract($data, EXTR_SKIP);
        $content = self::capture($file, $data);

        if ($layout) {
            $layoutFile = $viewsPath . str_replace('.', '/', $layout) . '.php';
            if (!file_exists($layoutFile)) {
                echo $content;
                return;
            }
            extract(array_merge($data, ['content' => $content]), EXTR_SKIP);
            require $layoutFile;
            return;
        }

        echo $content;
    }

    public static function partial(string $view, array $data = []): void
    {
        self::render($view, $data, null);
    }

    public static function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    private static function capture(string $file, array $data): string
    {
        extract($data, EXTR_SKIP);
        ob_start();
        require $file;
        return (string) ob_get_clean();
    }
}
