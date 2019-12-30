<?php

describe('->isTrackback', function () {
    beforeAll(function () {
        $this->client = client();
    });

    describe('early trackback', function () {
        beforeAll(function () {
            loadFixture('early-trackback');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('hello-world/trackback');
            $header = $res->getHeader('wp-context-early-trackback');
            expect($header[0])->toBe('true');
        });
    });

    describe('late trackback', function () {
        beforeAll(function () {
            loadFixture('late-trackback');
        });

        it('should return the correct response if the context was successfully detected', function () {
            $res = $this->client->get('hello-world/trackback');
            $header = $res->getHeader('wp-context-late-trackback');
            expect($header[0])->toBe('true');
        });
    });
});
