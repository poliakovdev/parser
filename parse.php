<?php
spl_autoload_register(function ($class_name) {
    include __DIR__ . '/service/' . $class_name . '.php';
});

$availableCommands = ['parse', 'report', 'help'];

$inputCommand = readline('Input your command:');
while (!in_array($inputCommand, $availableCommands)) {
    $inputCommand = readline("Wrong command!\nPlease input command 'help' to see commands list\nOr press 'n' to exit:");
    if ($inputCommand == 'n') {
        exit('exit');
    }
}

switch ($inputCommand) {
    case('parse'):
        $line = readline('Input target url:');
        $inputUrl = strlen($line) > 5 ? $line : "";

        if ($inputUrl === "") {
            echo "Wrong url given\n";
            exit(1);
        }

        $url = Parser::checkProtocol($inputUrl);
        $fileName = hash('sha256', $url);

        $fp = fopen('public/files/' . $fileName . '.csv', 'a');

        $parser = new ImgParser($url);
        $doc = $parser->getDocument();

        $picsInfo = $parser->getImgsPath($doc);
        if (count($picsInfo) > 0) {
            foreach ($picsInfo as $fields) {
                fputcsv($fp, $fields);
            }
        }

        $linkParser = new LinkParser($url);
        $linksList = $linkParser->getDomainLinks($doc);
        if (count($linksList) > 0) {
            foreach ($linksList as $link) {
                $imgParser = new ImgParser($link);
                $imgDoc = $imgParser->getDocument();
                $imgInfo = $imgParser->getImgsPath($imgDoc);

                foreach ($imgInfo as $item) {
                    fputcsv($fp, $item);
                }
            }
        }

        fclose($fp);

        $filePath = __DIR__ . '/public/files/' . $fileName . '.csv';
        if (file_exists($filePath)) {
            echo 'Your link: ' . $filePath;
        } else {
            echo 'Coudn\'t find file!';
        }

        break;

    case('report'):
        $domain = readline('Input domain:');

        $url = Parser::checkProtocol($domain);
        $fileName = hash('sha256', $url);
        $filePath = __DIR__ . '/public/files/' . $fileName . '.csv';

        if (file_exists($filePath)) {
            fopen($filePath, "rb");
            $handle = fopen($filePath, "rb");
            $contents = fread($handle, filesize($filePath));
            fclose($handle);

            var_dump($contents);
        }

        break;

    case('help'):
        echo "\nparse - start parser, takes parameter URL (with or without protocol);\n" .
             "report - display page analysis data for domain для;\n" .
             "help - list of console commands with explanations;\n" .
             "n - close application.\n";
        break;

}