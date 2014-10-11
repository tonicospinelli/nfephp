<?php

require_once dirname(__FILE__).'/libs/Common/Requirements.php';

if(!empty($_SERVER['SERVER_SOFTWARE'])){
    echo "<pre>";
}

$nfeRequirements = new NFePHPRequirements();

$iniPath = $nfeRequirements->getPhpIniConfigPath();

echo "********************************\n";
echo "*                              *\n";
echo "*  Requisitos para NFePHP      *\n";
echo "*                              *\n";
echo "********************************\n\n";

echo $iniPath ? sprintf("* Arquivo de configuração usado pelo PHP: %s\n\n", $iniPath) : "* WARNING: No configuration file (php.ini) used by PHP!\n\n";

echo "** ATENÇÃO **\n";
echo "*  O PHP CLI pode usar um arquivo php.ini diferente do\n";
echo "*  arquivo usado pelo seu servidor web.\n";
if ('\\' == DIRECTORY_SEPARATOR) {
    echo "*  (especialmente com a plataforma Windows)\n";
}
echo "*  Para ter certeza, por favor faça a chamada da análise de requisitos\n";
echo "*  a partir do seu servidor web, usando este script.\n";

echo_title('Requisitos Mandatórios');

$checkPassed = true;
foreach ($nfeRequirements->getRequirements() as $req) {
    /** @var $req Requirement */
    echo_requirement($req);
    if (!$req->isFulfilled()) {
        $checkPassed = false;
    }
}

echo_title('Recomendações opcionais');

foreach ($nfeRequirements->getRecommendations() as $req) {
    echo_requirement($req);
}

exit($checkPassed ? 0 : 1);

/**
 * Prints a Requirement instance
 */
function echo_requirement(Requirement $requirement)
{
    $result = $requirement->isFulfilled() ? 'OK' : ($requirement->isOptional() ? 'ATENÇÃO' : 'ERRO');
    echo ' ' . str_pad($result, 9);
    echo $requirement->getTestMessage() . "\n";

    if (!$requirement->isFulfilled()) {
        echo sprintf("          %s\n\n", $requirement->getHelpText());
    }
}

function echo_title($title)
{
    echo "\n** $title **\n\n";
}
