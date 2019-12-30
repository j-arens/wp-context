<?php

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

const CONT_NAME = 'wp-context-wordpress';
const CONT_URL = 'http://localhost:80';
const FIX_DIR = __DIR__ . '/fixtures';
const PLUGIN_DIR = '/var/www/html/wp-content/plugins/wp-context';

function loadFixture(string $fixture)
{
    $source = FIX_DIR . "/$fixture.php";
    if (!file_exists($source)) {
        throw new Exception("fixture $fixture not found");
    }
    $dest = PLUGIN_DIR . '/fixture.php';
    if (!copy($source, $dest)) {
        throw new Exception("failed to copy fixture $fixture");
    }
}

function client(): Client
{
    return new Client([
        'base_uri' => CONT_URL,
        'http_errors' => false,
    ]);
}

function login(Client $client, string $user): CookieJar
{
    $jar = new CookieJar();
    $client->post("/?lol-login=$user", [
        'cookies' => $jar,
    ]);
    return $jar;
}
