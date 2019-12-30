<?php

describe('->isRest', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('early rest request detection', function () {
        beforeAll(function () {
            loadFixture('early-rest');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('wp-json/wp/v2');
            $header = $res->getHeader('wp-context-early-rest');
            expect($header[0])->toBe('true');
        });
    });

    describe('late rest request detection', function () {
        beforeAll(function () {
            loadFixture('late-rest');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('wp-json/wp/v2');
            $header = $res->getHeader('wp-context-late-rest');
            expect($header[0])->toBe('true');
        });
    });
});
